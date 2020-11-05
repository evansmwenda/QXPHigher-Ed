<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestsRequest;
use App\Http\Requests\Admin\UpdateTestsRequest;
use App\CourseUser;
use App\Course;
use App\QuestionTest;
use App\Question;
use App\QuestionsOption;
use App\User;
use App\ExamAnswers;
use App\TestsResult;
use App\TestsResultsAnswer;

class TestsController extends Controller
{
    /**
     * Display a listing of Test.
     *
     * @return \Illuminate\Http\Response
     */
    public function attemptedQuizzesByStudent(String $test_id=null,String $student_id=null){
        //get student details
        $student_details = User::find($student_id);
        // dd($student_details);

        //get list of questions & ids
        $questions = QuestionTest::with(['test'])->where('test_id',$test_id)->get();
        
        //get the questions with their options for each of the test id supplied
        $question_ids="";
        foreach($questions as $key=>$question){
            // $question_ids .= $question->question_id. ',';
            $options = QuestionsOption::with('question')->where('question_id',$question->question_id)->get();
            // $question_options[$key] = $options;
            $question_options[$question->question_id] = $options;

        }
        
        //get the answers that the student submitted for a particular test
        $question_answers = DB::table('tests_results')
                    ->select('tests_results_answers.question_id as question_id',
                             'tests_results_answers.option_id as option_id',
                             'questions.question as question_title',
                             'questions_options.option_text as question_answer'
                             )
                    ->join('tests_results_answers', 'tests_results_answers.tests_result_id', '=', 'tests_results.id')         
                    ->join('questions_options', 'questions_options.id', '=', 'tests_results_answers.option_id')
                    ->join('questions', 'questions.id', '=', 'tests_results_answers.question_id')
                    ->where('tests_results.test_id',$test_id)
                    ->orderBy('tests_results.id','DESC')
                    ->get();
                    

        //get test result if any
        $test_result = TestsResult::where(['test_id'=>$test_id,'user_id'=>$student_id])->get();
        // dd($test_result);
        if(count($test_result) < 1){
            $test_result="N/A";
        }else{
            $test_result=$test_result[0]->test_result;
        }
        // dd($questions);
        // dd($question_answers);
       $index =0;
       $counter =0;
        return view('admin.tests.attempts_student')
        ->with(compact('question_options',
        'question_answers',
        'questions',
        'student_details',
        'index','counter',
        'test_result'));
    }
    public function attemptedQuizzes(String $id=null){
        //get list of students who attempt test
        $test = Test::where('id',$id)->get()->first();
        // $results = TestResults::with('user')->where('test_id',$id)->get();
        $students = DB::table('tests_results')
                    ->select('tests_results.test_id as test_id',
                             'tests_results.user_id as user_id',
                             'tests_results.test_result as test_result',
                             'users.name as user_name','tests.title as test_title')
                    ->join('users', 'users.id', '=', 'tests_results.user_id')
                    ->join('tests', 'tests.id', '=', 'tests_results.test_id')
                    ->where('tests_results.test_id',$id)
                    ->orderBy('tests_results.id','DESC')
                    ->get();
        // dd($students);

        return view('admin.tests.attempts')->with(compact('students','test'));
    }
    public function fetchExamTitles(String $course_id){
        $my_tests = Test::with('course')->where('course_id',$course_id)->where('lesson_id','!=',NULL)->get();
        return $my_tests;
    }

    public function fetchExamQuestions(String $exam_id){
        $my_questions = QuestionTest::with('test')->where('test_id',$exam_id)->get();
        return $my_questions;
    }
    public function fetchExamQuestionIDs(String $exam_id){
        $my_questions_ids = QuestionTest::with('test')->where('test_id',$exam_id)->pluck('question_id');
        return $my_questions_ids;
    }
    public function deleteExamQuestion(String $question_id){
        $question = Question::find($question_id);
        // dd($question);
        $question->delete();
        return redirect('/admin/tests')->with('flash_message_success','Question deleted');
    }

    public function getTestDetails(Request $request,String $exam_id=null){
        $titles_array= [];
        if($exam_id != null){
            $exam_details = Test::find($exam_id);

            // dd($exam_details->course_id);
            //fetches objects of quizzes
            $titles_array = $this->fetchExamTitles($exam_details->course_id);
            
            //get the questions in the exam_id provided
            $questions_array = $this->fetchExamQuestions($exam_id);
            if($questions_array->isEmpty()){
                //array is empty, exam has no question
                $question_ids = [];
                $my_questions = [];
            }else{
                //exam has questions
                $question_ids = $this->fetchExamQuestionIDs($exam_id);
                $my_questions = Question::whereIn('id',$question_ids)->get();
            }
        }

        //1.get all course_ids belonging to this user
        $my_courses = CourseUser::with('course')->where(['user_id'=> \Auth::id()])->get();
        // dd($questions_array);
        return view('admin.tests.index2')->with(compact('my_courses','exam_id','titles_array','questions_array','my_questions'));
    }
    public function index(Request $request)
    {
        $titles_array= [];
        $my_questions= [];
        $exam_id='';
        $questions_array= [];
        // $questions_array = $this->fetchExamQuestions($exam_id);
        if($request->isMethod('post')){
            //user is posting data
            // dd($request->all());
            $data= $request->all();
            // dd($data);
            $course_title = Course::where('id',$data['course_id'])->value('title');


            switch($request->type){
                case "title":
                    //get the exam titles in that course selected
                    $titles_array = $this->fetchExamTitles($data['course_id']);
                    // dd($titles_array);
                break;
            }
        }

        //1.get all course_ids belonging to this user
        $my_courses = CourseUser::with('course')->where(['user_id'=> \Auth::id()])->get();

        if (! Gate::allows('test_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('test_delete')) {
                return abort(401);
            }
            $tests = Test::onlyTrashed()->get();
        } else {
            // $tests = Test::all();
            $tests = Test::where('lesson_id','!=',NULL)->get();
            //only exams will have null lesson_id columns
            
        }
        // dd($tests);

        return view('admin.tests.index', 
            compact('tests',
            'my_courses',
            'titles_array',
            'my_questions',
            'questions_array',
            'course_title'
        ));
    }

    /**
     * Show the form for creating new Test.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('test_create')) {
            return abort(401);
        }
        $courses = \App\Course::ofTeacher()->get();
        $courses_ids = $courses->pluck('id');
        $courses = $courses->pluck('title', 'id')->prepend('Please select', '');
        $lessons = \App\Lesson::whereIn('course_id', $courses_ids)->get()->pluck('title', 'id')->prepend('Please select', '');

        return view('admin.tests.create', compact('courses', 'lessons'));
    }

    /**
     * Store a newly created Test in storage.
     *
     * @param  \App\Http\Requests\StoreTestsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTestsRequest $request)
    {
        if (! Gate::allows('test_create')) {
            return abort(401);
        }
        $test = Test::create($request->all());

        return redirect()->route('admin.tests.index');
    }


    /**
     * Show the form for editing Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('test_edit')) {
            return abort(401);
        }
        $courses = \App\Course::ofTeacher()->get();
        $courses_ids = $courses->pluck('id');
        $courses = $courses->pluck('title', 'id')->prepend('Please select', '');
        $lessons = \App\Lesson::whereIn('course_id', $courses_ids)->get()->pluck('title', 'id')->prepend('Please select', '');

        $test = Test::findOrFail($id);

        return view('admin.tests.edit', compact('test', 'courses', 'lessons'));
    }

    /**
     * Update Test in storage.
     *
     * @param  \App\Http\Requests\UpdateTestsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTestsRequest $request, $id)
    {
        if (! Gate::allows('test_edit')) {
            return abort(401);
        }
        $test = Test::findOrFail($id);
        $test->update($request->all());

        return redirect()->route('admin.tests.index');
    }


    /**
     * Display Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('test_view')) {
            return abort(401);
        }
        $test = Test::findOrFail($id);
        return view('admin.tests.show', compact('test'));
    }


    /**
     * Remove Test from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('test_delete')) {
            return abort(401);
        }
        $test = Test::findOrFail($id);
        $test->delete();

        return redirect()->route('admin.tests.index');
    }

    /**
     * Delete all selected Test at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('test_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Test::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Test from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('test_delete')) {
            return abort(401);
        }
        $test = Test::onlyTrashed()->findOrFail($id);
        $test->restore();

        return redirect()->route('admin.tests.index');
    }

    /**
     * Permanently delete Test from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('test_delete')) {
            return abort(401);
        }
        $test = Test::onlyTrashed()->findOrFail($id);
        $test->forceDelete();

        return redirect()->route('admin.tests.index');
    }
}
