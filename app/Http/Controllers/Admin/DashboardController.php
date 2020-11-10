<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events;
use App\CourseUser;
use App\Course;
use App\Assignments;
use App\Test;
use App\Lesson;
use App\Question;
use App\QuestionsOption;
use App\SubmittedAssignments;
use App\ExamSubmits;
use App\ExamAnswers;
use App\User;
use App\QuestionTest;
use App\TestsResult;
use App\LiveClasses;
use App\LiveClassRecordings;
use App\Media;
use DB;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fetch my courses
        $courses = CourseUser::with('course')
        ->where('user_id',\Auth::id())
        ->orderBy('course_id','DESC')
        ->get();

        $this->getSummaryCount();

        $course_ids = CourseUser::where('user_id',\Auth::id())->pluck('course_id');
        $count_courses = count($courses);

        //fetch my events
        $events = $this->fetchFutureEvents();
        $count_events = count($events);
      
        //fetch my assignments
        $assignments = Assignments::with('submitted_assignments')
        ->whereIn('course_id',$course_ids)
        ->orderBy('id','DESC')
        ->get();

        $submitted_assignments_array =[];
        $assignment_ids="";
        foreach ($assignments as $key => $value) {
            $assignment_ids .= $value->id .",";

            $submitted_assignments = SubmittedAssignments::with(['user'])
            ->where(['assignment_id'=>$value->id])->get();

            $submitted_assignments_array += [
                $value->id => $submitted_assignments,
            ];
        }
        // dd($submitted_assignments_array[1]);
        $count_assignments = count($assignments);

        //get number of resources
        $resources = $this->getResourcesList($course_ids);

        //fetch my exams
        $exams = Test::with('course')
        ->whereIn('course_id',$course_ids)
        ->orderBy('id','DESC')
        ->get();
        $count_exams = count($exams);
        // dd($exams);

        //fetch things needing grading
        //fetch resources for download
        return view('home')->with(compact(
            'courses',
            'count_courses',
            'events',
            'count_events',
            'assignments',
            'count_assignments',
            'submitted_assignments_array',
            'exams',
            'count_exams',
            'resources'
        ));
    }
    public function getSummaryCount(){
        //courses
        $courses = CourseUser::where('user_id',\Auth::id())->get();
        $count_courses = count($courses);

        //events
        $events = $this->fetchFutureEvents();
        $count_events = count($events);

        //assignments
        $course_ids =$this->fetchEnrolledCourseIDs();
        $assignments = Assignments::whereIn('course_id',$course_ids)
        ->orderBy('id','DESC')
        ->get();
        $count_assignments = count($assignments);

        //exams
        $exams = Test::with('course')
        ->whereIn('course_id',$course_ids)
        ->orderBy('id','DESC')
        ->get();
        $count_exams = count($exams);

        $my_summary_count = array(
            'courses' => $count_courses,
            'events' => $count_events,
            'exams' => $count_exams,
            'assignments' => $count_assignments
        );
        return $my_summary_count;
    }
    public function getAssignmentsb(Request $request){
        $course_ids = $this->fetchEnrolledCourseIDs();
        $my_assignments = Assignments::with(['course'])->whereIn('course_id',$course_ids)->get();

        return view('admin.assignments.index')->with(compact('my_assignments'));
    }
    public function updateAssignment(Request $request,$id=null){
        $my_courses = CourseUser::where(['user_id'=> \Auth::id()])->get();
        $assignment_course_id = Assignments::where('id',$id)->value('course_id');
        // dd($assignment_course_id);

        $course_ids = $this->fetchEnrolledCourseIDs();
        // dump($course_ids);
        $my_assignments = Assignments::with(['course'])->whereIn('course_id',$course_ids)->get();
        if(in_array($assignment_course_id,$course_ids)){
            //user is owner of the course
            $submitted_assignments_array = $this->assignmentDetails($id);
            // dd($submitted_assignments_array);
            $assignment = Assignments::find($id);
            if($request->isMethod('post')){
                //post request
                
                
                $data=$request->all();
                if($data['description']==null){
                    $description="";
                }
                // dd($data);
                if($data['course_id'] == "0"){
                    return back()->with('flash_message_error','Please choose a course from the dropdown');
                }
                $slug =  DB::table('courses')->where('id', $data['course_id'])->value('slug');
                // dd($slug);
                if($request->hasFile('assignment')){
                    $image_tmp = $request->file('assignment');

                    $extension = $image_tmp->getClientOriginalExtension();//txt,pdf,csv
                    $filename = time().'.'.$extension;//1592819807.txt

                    $storage_dir = 'uploads/assignments/'.$slug.'/';
                    // dd($storage_dir);

                    $uploaded = $image_tmp->move($storage_dir, $filename);
                    //store the filename into the db


                    if($uploaded){
                        // 'id','course_id','title','description','media','created_at','updated_at']
                        //file was uploaded->insert to db
                        $assignment = Assignments::find($id);
                        $assignment->course_id=$data['course_id'];
                        $assignment->title=$data['title'];
                        $assignment->description=$description;
                        $assignment->media=$filename;
                        $assignment->save();


                        //create event based on that assignment
                        $date_now = date("Y-m-d H:m:s");
                        // $date_valid = date("Y-m-d H:m:s", strtotime("+7 days"));
                        $date_valid = date("Y-m-d H:m:s");

                        $this->myEventCreator(
                            $data['title'],//title of event
                            'assignment',//type of event
                            $data['course_id'],//course_id
                            $date_now, //event start time
                            $date_valid
                        );
        
                        return redirect('/admin/assignments')
                            ->with('flash_message_success','You have successfully updated your assignment.');
                    }else{
                        //file was not uploaded dont insert to db
                        return back()->with('flash_message_error','Sorry, there was an error updating your assigment');
                    }
                }else{
                    //teacher not submitting file for upload
                    //update the rest of details
                    $assignment = Assignments::find($id);
                    $assignment->course_id=$data['course_id'];
                    $assignment->title=$data['title'];
                    $assignment->description=$description;
                    // $assignment->media=$filename;
                    $assignment->save();


                    //create event based on that assignment
                    $date_now = date("Y-m-d H:m:s");
                    // $date_valid = date("Y-m-d H:m:s", strtotime("+7 days"));
                    $date_valid = date("Y-m-d H:m:s");

                    $this->myEventCreator(
                        $data['title'],//title of event
                        'assignment',//type of event
                        $data['course_id'],//course_id
                        $date_now, //event start time
                        $date_valid
                    );
    
                    return redirect('/admin/assignments')
                        ->with('flash_message_success','You have successfully updated your assignment.');
                }
            }
            // dd($assignment);
            return view('admin.assignments.edit')
            ->with(compact('assignment',
            'my_courses',
            'assignment_course_id',
            'submitted_assignments_array'));
        }
        
    }
    public function assignmentDetails($assignment_id=null){
        //get the details of the assignemnt->description and if anyone has submitted
        $course_ids = $this->fetchEnrolledCourseIDs();
        $my_assignments = Assignments::with(['course'])->whereIn('course_id',$course_ids)->get();

        // dd(Assignments::find($assignment_id));
        $submitted_assignments_array =[];
        $assignment_ids="";
        $submitted_assignments = SubmittedAssignments::with(['user'])
            ->where(['assignment_id'=>$assignment_id])->get();

        return $submitted_assignments;
    }

    public function getAssignments(){
        // $my_courses = CourseUser::where(['user_id'=>'3'])->get();
        $my_courses = CourseUser::where(['user_id'=> \Auth::id()])->get();

        $course_ids="";
        foreach ($my_courses as $key => $value) {
            $course_ids .= $value->course_id .",";
        }
        $course_ids = explode(",", $course_ids);
        
        $my_assignments = Assignments::with(['course'])->whereIn('course_id',$course_ids)->get();


        $submitted_assignments_array =[];
        $assignment_ids="";
        foreach ($my_assignments as $key => $value) {
            $assignment_ids .= $value->id .",";

            $submitted_assignments = SubmittedAssignments::with(['user'])
            ->where(['assignment_id'=>$value->id])->get();

            $submitted_assignments_array += [
                $value->id => $submitted_assignments,
            ];
        }

        // dd($submitted_assignments_array[1]);//all assignments submitted to assignment with id of 1


        // $assignment_ids = explode(",", $assignment_ids);
        // $submitted_assignments = SubmittedAssignments::with(['user'])->whereIn('assignment_id',$assignment_ids)->get();
        
        // dd($submitted_assignments);
        // dd($my_events);
        //dd($my_courses[0]->course->title);//"Biology 101"

        return view('admin.assignments.index')->with(compact('my_assignments','submitted_assignments_array'));
    }

    public function createAssignments(Request $request){
        // $my_courses = CourseUser::where(['user_id'=>'3'])->get();
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        //dd($my_courses[0]->course->title);//"Biology 101"
        // dd($my_courses);

        if($request->isMethod('post')){
            $data=$request->all();
            // dd($data);
            if($data['course_id'] == "0"){
                return back()->with('flash_message_error','Please choose a course from the dropdown');
            }
            $slug =  DB::table('courses')->where('id', $data['course_id'])->value('slug');
            // dd($slug);
            if($request->hasFile('assignment')){
                $image_tmp = $request->file('assignment');

                $extension = $image_tmp->getClientOriginalExtension();//txt,pdf,csv
                $filename = time().'.'.$extension;//1592819807.txt

                $storage_dir = 'uploads/assignments/'.$slug.'/';
                // dd($storage_dir);

                $uploaded = $image_tmp->move($storage_dir, $filename);
                //store the filename into the db

                // $flight = new Flight;
                // $flight->name = $request->name;
                // $flight->save();

                if($uploaded){
                    // 'id','course_id','title','description','media','created_at','updated_at']
                    //file was uploaded->insert to db
                    $my_assignment = new Assignments;
                    $my_assignment->course_id=$data['course_id'];
                    $my_assignment->title=$data['title'];
                    $my_assignment->description=$data['description'];
                    $my_assignment->media=$filename;
                    $my_assignment->save();


                    //create event based on that assignment
                    $date_now = date("Y-m-d H:m:s");
                    // $date_valid = date("Y-m-d H:m:s", strtotime("+7 days"));
                    $date_valid = date("Y-m-d H:m:s");

                    $this->myEventCreator(
                        $data['title'],//title of event
                        'assignment',//type of event
                        $data['course_id'],//course_id
                        $date_now, //event start time
                        $date_valid
                    );
       
                    return redirect('/admin/assignments')
                        ->with('flash_message_success','You have successfully created your assignment.');
                }else{
                    //file was not uploaded dont insert to db
                    return back()->with('flash_message_error','Sorry, there was an error uploading your assigment');
                }
            }
        }
         //get
        return view('admin.assignments.create')->with(compact('my_courses'));

    }
    public function myEventCreator(String $title,String $type,String $course_id,String $start_date,String $end_date){
        //create events here
        $my_event = new Events;
        $my_event->title            = $title;
        $my_event->course_id        = $course_id;
        $my_event->type             = $type;
        $my_event->event_start_time = $start_date;
        $my_event->event_end_time   = $end_date;
        $my_event->color            ="#00FFFF";
        $my_event->save();
    }

    public function getEvents(){
        // $my_courses = CourseUser::where(['user_id'=>'3'])->get();
        $my_courses = CourseUser::where(['user_id'=> \Auth::id()])->get();
        $course_ids="";
        foreach ($my_courses as $key => $value) {
            $course_ids .= $value->course_id .",";
        }
        $course_ids = explode(",", $course_ids);
        $my_events = Events::with(['course'])->whereIn('course_id',$course_ids)->get();
        // dd($my_events);
        //dd($my_courses[0]->course->title);//"Biology 101"

        $yearly =$this->fetchAllEvents();
        
        $monthly = $this->fetchFutureEvents();
        
        $event_array = (array) null; 
        $class_event_array = (array) null; 
        $exam_event_array = (array) null; 
        $assignment_event_array = (array) null; 
        $now = time();
        foreach ($yearly as $key => $event) {
            $created_at=explode(" ", $event->created_at);
            $your_date = strtotime($created_at[0]);
            $datediff = $now - $your_date;
            $days = round($datediff / (60 * 60 * 24));
            $event_array [] = array(
                    "title" => $event->title,
                    "start" => $event->event_start_time,
                    "end" => $event->event_end_time,
                    "backgroundColor" => $event->color,
                    "borderColor" => $event->color,
                    "created_at" => $created_at[0],
                    "days" => $days,
                    );
        }
        

        foreach($monthly as $event){
            $created_at=explode(" ", $event->created_at);
            $your_date = strtotime($created_at[0]);
            $datediff = $now - $your_date;
            $days = round($datediff / (60 * 60 * 24));
            switch($event->type){
                case 'class':
                    #live classes
                    $class_event_array [] = array(
                    "id"=> $event->id,
                    "title" => $event->title,
                    "start" => $event->event_start_time,
                    "end" => $event->event_end_time,
                    "backgroundColor" => $event->color,
                    "borderColor" => $event->color,
                    "created_at" => $created_at[0],
                    "days" => $days,
                    );
                    break;
                case 'exam':
                    #exam events
                    $exam_event_array [] = array(
                        "id"=> $event->id,
                    "title" => $event->title,
                    "start" => $event->event_start_time,
                    "end" => $event->event_end_time,
                    "backgroundColor" => $event->color,
                    "borderColor" => $event->color,
                    "created_at" => $created_at[0],
                    "days" => $days,
                    );
                    break;
                case 'assignment':
                    #assignment events
                    $assignment_event_array [] = array(
                        "id"=> $event->id,
                        "title" => $event->title,
                        "start" => $event->event_start_time,
                        "end" => $event->event_end_time,
                        "backgroundColor" => $event->color,
                        "borderColor" => $event->color,
                        "created_at" => $created_at[0],
                        "days" => $days,
                        );
                    break;
            }
        }
        // dd($monthly);
        
         //get
        return view('admin.events.index')
        ->with(compact('my_events',
        'event_array',
        'class_event_array'
        ,'exam_event_array',
        'assignment_event_array'));
    }
    public function updateEvent(Request $request,$id){
        //check if i own the course
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        
        $course_ids = $this->fetchEnrolledCourseIDs();
        $event_details = Events::where('id',$id)->first();
        $course_id = $event_details['course_id'];
        if(!is_null($course_id) && in_array($course_id,$course_ids)){
            //user is owner of the course->can edit
            // dd($my_event);
            if($request->isMethod('post')){
                //user attempting to update
                $data=$request->all();
                $event_start_end = $data['event_start_end'];
                // dd($event_start_end);
                
                $event_start_end = explode(" - ", $event_start_end);
                 // 0 => "2020-06-23 00:00:00"
                 // 1 => "2020-06-23 23:59:59"
                // dd($event_start_end[0]);
                // dd(date("H:i", strtotime("04:25 PM"));)
    
                $event = Events::find($id);
                $event->title = $data['event_title'];
                $event->course_id = $data['course_id'];
                $event->event_start_time=$event_start_end[0];
                $event->event_end_time=$event_start_end[1];
                $event->color=$data['favcolor'];
                $event->save();
                return redirect('/admin/events')->with('flash_message_success','Event updated successfully ');
            }
            return view('admin.events.edit')->with(compact('event_details','my_courses'));
        }
    }
    public function createEvents(Request $request){
        // $my_courses = CourseUser::where(['user_id'=>'3'])->get();
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        //dd($my_courses[0]->course->title);//"Biology 101"

        if($request->isMethod('post')){
            $data=$request->all();
             // dd($data);

            $event_start_end = $data['event_start_end'];
            // dd($event_start_end);
            
            $event_start_end = explode(" - ", $event_start_end);
             // 0 => "2020-06-23 00:00:00"
             // 1 => "2020-06-23 23:59:59"
            // dd($event_start_end[0]);
            // dd(date("H:i", strtotime("04:25 PM"));)

            

            $my_event = new Events;
            $my_event->title=$data['event_title'];
            $my_event->course_id=$data['course_id'];
            $my_event->type=$data["type"] == null ? "class":$data["type"];
            $my_event->event_start_time=$event_start_end[0];
            $my_event->event_end_time=$event_start_end[1];
            $my_event->color=$data['favcolor'];

            // dd($my_event);
            $my_event->save();
            return redirect('/admin/events')->with('flash_message_success','Event created successfully ');
        }
         //get
        return view('admin.events.create')->with(compact('my_courses'));
    }
    public function createEvents2(){

        // PHP array
        $books = array(
            array(
                "title" => "All Day Event",
                "start" => "2020-07-01",
                "backgroundColor" => "#f56954",
                "borderColor" => "#f56954",
            ),
            array(
                "title" => "Long Event",
                "start" => "2020-07-22",
                "backgroundColor" => "#f39c12",
                "borderColor" => "#f39c12",
            ),
            array(
                "title" => "Birthday party from 12pm to 3pm",
                "start" => "2020-07-23 08:00:00",
                "backgroundColor" => "#00c0ef",
                "borderColor" => "#00c0ef",
            ),
            array(
                "title" => "Initiation",
                "start" => "2020-07-24 09:00:00",
                "backgroundColor" => "#0073b7",
                "borderColor" => "#0073b7",
            ),
            array(
                "title" => "Live classroom",
                "description" => "lecture",
                "start" => "2020-07-21 10:00:00",
                "backgroundColor" => "#00a65a",
                "borderColor" => "#00a65a",
            )
        );

        // //1.get all course_ids belonging to this user
        // $my_courses = CourseUser::where(['user_id'=> \Auth::id()])->get();

        // $course_ids="";
        // foreach ($my_courses as $key => $value) {
        //     $course_ids .= $value->course_id .",";
        // }
        // $course_ids = explode(",", $course_ids);
        // $my_tests = Test::with(['course'])->whereIn('course_id',$course_ids)->get();
        // dd($my_tests);
        //dd($my_courses[0]->course->title);//"Biology 101"
        return view('admin.events.create2')->with(compact('books'));
    }
    public function deleteEvents(Request $request,string $id){
        // $my_courses = CourseUser::where(['user_id'=>'3'])->get();
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        $event_details = Events::where(['id' => $id])->first();

        $event = Events::find($id);
        $event->delete();

        return back()->with('flash_message_success','Your event was deleted!');
        // dd($event);
        //dd($my_courses[0]->course->title);//"Biology 101"

        // if($request->isMethod('post')){
        //     $data=$request->all();
        //      // dd($data);

        //     $event_start_end = $data['event_start_end'];
        //     // dd($event_start_end);
            
        //     $event_start_end = explode(" - ", $event_start_end);
        //      // 0 => "2020-06-23 00:00:00"
        //      // 1 => "2020-06-23 23:59:59"
        //     // dd($event_start_end[0]);
        //     // dd(date("H:i", strtotime("04:25 PM"));)

            

        //     $my_event = new Events;
        //     $my_event->title=$data['event_title'];
        //     $my_event->course_id=$data['course_id'];
        //     $my_event->event_start_time=$event_start_end[0];
        //     $my_event->event_end_time=$event_start_end[1];
        //     $my_event->color=$data['favcolor'];

        //     // dd($my_event);
        //     $my_event->save();
        //     return redirect('/admin/events')->with('flash_message_success','Event created successfully ');
        // }
         //get
        // return view('admin.events.edit')->with(compact('my_courses','event_details'));
    }
    

    public function getExams(Request $request,String $exam_id=null){
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

        

        //get the questions in that exam

        $course_ids="";
        foreach ($my_courses as $key => $value) {
            $course_ids .= $value->course_id .",";
        }
        $course_ids = explode(",", $course_ids);
        $my_tests = Test::with(['course'])->whereIn('course_id',$course_ids)->get();
        
        // dd($my_courses);
        //dd($my_courses[0]->course->title);//"Biology 101"
        return view('admin.exams.index')->with(compact('exam_id','my_tests','my_courses','titles_array','my_questions','questions_array'));
    }
    public function getExamsDetails(Request $request,String $exam_id=null){
        $titles_array= [];
        if($exam_id != null){
            $exam_details = Test::find($exam_id);
            // dd($exam_details->course_id);
            $titles_array = $this->fetchExamTitles($exam_details->course_id);
            // dd($titles_array);
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
        return view('admin.exams.index2')->with(compact('my_courses','exam_id','titles_array','questions_array','my_questions'));
    }

    public function fetchExamTitles(String $course_id){
        $my_tests = Test::with('course')->where('course_id',$course_id)->where('lesson_id',NULL)->get();
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
        $question->delete();
        return redirect('/admin/exams')->with('flash_message_success','Question deleted');
    }
    public function createExams2(){
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        return view('admin.exams.create')->with(compact('my_courses'));
    }

    public function createExams(){
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        return view('admin.exams.create')->with(compact('my_courses'));
    }

    public function storeExams(){
        //get the exams data and store to text file then db

        header("Content-Type: application/json");

        // build a PHP variable from JSON sent using POST method
        $v = json_decode(stripslashes(file_get_contents("php://input")),TRUE);


        // $myfile = fopen("testfile.txt", "w") or die("Unable to open file!");
        // #1.create the test/exam in the tests table
        // fwrite($myfile, "\ndescription->".$v[0]["description"]);//description
        // fwrite($myfile, "\ncourse_id->".$v[0]["course_id"]);//course_id
        // fwrite($myfile, "\nexam_title->".$v[0]["exam_title"]);//exam_title

        //create an event to alert students of the exam
        $date_now = date("Y-m-d H:m:s");
        $date_valid = date("Y-m-d H:m:s", strtotime("+7 days"));

        $my_event = new Events;
        $my_event->title     = $v[0]["exam_title"];
        $my_event->course_id = $v[0]["course_id"];
        $my_event->type='exam';
        $my_event->event_start_time=$date_now;
        $my_event->event_end_time=$date_valid;
        $my_event->color="#00FFFF";
        $my_event->save();

        $my_test = new Test;
        $my_test->course_id   = $v[0]["course_id"];
        $my_test->title       = $v[0]["exam_title"];
        $my_test->description = $v[0]["description"];
        $my_test->published   = "1";
        $my_test->save();


        $my_test_id = $my_test->id;
        // fwrite($myfile, "\nnewest test id->".$my_test_id);//test id
        $question_ids="";

        foreach ($v as $key => $question) {
            //for questions -> $question["question"]["value"]
            //for options -> $question["options"][0]["value"]
            

            // fwrite($myfile,"\n\n".$question["question"]["value"]);//question
            #2.insert the questions to questions table
            $my_question = new Question;
            $my_question->question = $question["question"]["value"];
            $my_question->score    = "1";
            $my_question->save();
            
            $question_ids .= $my_question->id .",";
            // fwrite($myfile, "\nnewest question id->".$my_question->id);//question id


            foreach ($question["options"]  as $key => $question_details) {
                #3. store the question options(answers) to the question options table
                $question_options = $question_details["value"];
                if(!empty($question_options)){
                    //we have options->store them
                    // fwrite($myfile,"\n".$question_options);
                    $my_question_options = new QuestionsOption;
                    $my_question_options->question_id = $my_question->id;//its parent question id
                    $my_question_options->option_text = $question_options;
                    $my_question_options->correct     = "0";
                    $my_question_options->save();

                }
            }//end of answers loop
        }//end of questions loop
        // fwrite($myfile, "\nquestions array->".$question_ids);//question ids

        #4. store the question ids in question tests table
        $dataSet = [];
        $question_ids_array = explode(",", $question_ids);
        // fwrite($myfile, "\nquestions array itself->".$question_ids );//question ids
        foreach ($question_ids_array as $question_test) {
            if(!empty($question_test)){
                //question available->store
                $dataSet[] = [
                'question_id'  => $question_test,
                'test_id'    => $my_test_id
            ];
            // fwrite($myfile, "\n\nquestion->".$question_test." test_id->".$my_test_id);
            }
            
        }

        DB::table('question_test')->insert($dataSet);

        // fclose($myfile);
            
        $arr = array('success' => true, 
            'status' => "Successful", 
            'sent' => json_encode($v),
            'date_now' => $date_now, 
            'date_valid' => $date_valid
        );

        echo json_encode($arr);
    }

    public function attemptedExams(String $id=null){
        //get list of students who attempt test
        $test = Test::where('id',$id)->get()->first();
        $student_ids =ExamSubmits::with('exam')->where('test_id',$id)->value('user_id');
        $students_array = explode(",", $student_ids);
        $students = User::whereIn('id',$students_array)->get();
        // dd($students);

        return view('admin.exams.attempts')->with(compact('students','id','test'));
    }

    public function attemptedExamsByStudent(String $test_id=null,String $student_id=null){
        //get student details
        $student_details = User::find($student_id);

        //get list of questions & ids
        $questions = QuestionTest::with(['test'])->where('test_id',$test_id)->get();
        // dd($questions);
        $question_ids="";
        foreach($questions as $question){
            $question_ids .= $question->question_id. ',';
        }
        
        //and answers in the test
        $question_array =explode(",", $question_ids);

        //get the questions with their options for the test id supplied
        $question_options = Question::with(['options'])->whereIn('id',$question_array)->get();


        //get the answers that the student submitted for a particular test
        $question_answers = ExamAnswers::where(['test_id'=>$test_id,'user_id'=>$student_id])->get();

        //get test result if any
        $test_result = TestsResult::where(['test_id'=>$test_id,'user_id'=>$student_id])->get();
        if(count($test_result) < 1){
            $test_result="N/A";
        }else{
            $test_result=$test_result[0]->test_result;
        }
       
        return view('admin.exams.attempts_student')->with(compact('question_options','question_answers','questions','student_details','test_result'));
    }

    public function postStudentGrade(Request $request){
        $data=$request->all();
        
        //insert new or update
        $match_these = ['test_id'=>$data['t_id'],'user_id'=>$data['u_id']];
        TestsResult::updateOrCreate($match_these,['test_result'=>$data['marks']]);


        return back()->with('flash_message_success','Exam grade updated successfully!');
    }

    public function deleteExams(Request $request,string $id){
        $test = Test::find($id);
        $test->delete();

        return redirect('/admin/exams')->with('flash_message_success','Your exam was deleted!');
    }
    public function liveClasses(){
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        $my_classes = LiveClasses::with(['course'])->where(['owner'=> \Auth::id()])
        ->where(function($q) {
            $q->where('classTime', '>=', date("Y-m-d"))
              ->orWhereNull('classTime');
        })
        ->orderBy('id','DESC')
        ->get();
        // dd($my_classes);       
        return view('admin.classes.index')->with(compact('my_courses','my_classes'));
    }
    public function deleteLiveClass($id){
        LiveClasses::find($id)->delete();
        return redirect()->back()->with('flash_message_success','Your class has been deleted');
    }

    public function scheduleLiveClass(Request $request){
        // $my_courses = CourseUser::where(['user_id'=>'3'])->get();
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        // dd($my_courses[0]->course->title);//"Biology 101"

        if($request->isMethod('post')){
            $data=$request->all();            
            // dd($data);
            $user=\Auth::user();
            // dd($user);
            $title="";
            //in order to schedule a class happens
            //1.get the details of the future class

            $title_array=explode(" ", $data['title']);
            //check if name is has more than one
            $count=count($title_array);
            if($count > 1){
                //this is an array -> loop and get the elements and underscore them
                $title=$title_array[0];
                for($i=1;$i<$count;$i++){
                    $title=$title."-".$title_array[$i];
                }
            }else{
                //the title is one word e.g "testing"
                $title=$title_array[0];
            }

            
            // $meetingID=str_random(6);
            $meetingID =substr(md5(mt_rand()), 0, 6);
            // dump($meetingID);

            $event_start_end = $data['event_start_end'];
            // dd($event_start_end);
            
            $event_start_end = explode(" - ", $event_start_end);
             // 0 => "2020-06-23 00:00:00"
             // 1 => "2020-06-23 23:59:59"
            // dd($event_start_end[0]);
            // dd(date("H:i", strtotime("04:25 PM"));)

            


            $classTime=$event_start_end[0];//"2020-06-23 00:00:00"
            $attendeePW=str_random(6);//"ap";//$request->attendeePW;
            
            $moderatorPW=str_random(6);//"mp";//$request->moderatorPW;
            $duration='30';//$request->duration;

            //format datetime
            // $classTime=date("Y-m-d H:i:s",strtotime($classTime));//"2020-04-20 07:30:00"
            // dd($classTime);

            //insert record to table
            $newLiveClass= [
                'title'=>$title,//class title
                'meetingID'=>$meetingID,//meeting ID
                'course_id'=>$data['course_id'],
                'classTime'=>$classTime,//classTime
                'attendeePW'=>$attendeePW,//attendee password 
                'moderatorPW'=>$moderatorPW,//moderator password
                // 'duration'=>$duration,//role=0for normal user accounts
                'owner'=>$user['id']
                ];
            // dd($newLiveClass);
            $newLiveClass = LiveClasses::create($newLiveClass);
            if($newLiveClass){
                $my_event = new Events;
                $my_event->title=$data['title'];
                $my_event->type='class';
                $my_event->course_id=$data['course_id'];
                $my_event->event_start_time=$event_start_end[0];
                $my_event->event_end_time=$event_start_end[1];
                $my_event->color=$data['favcolor'];

                // dd($my_event);
                $my_event->save();
                    //return back to dashboard with class scheduled notification.
                    $class_string = "Meeting scheduled successfully!. Meeting ID is: ".$meetingID.".";
                    return redirect()->back()->with('flash_message_success',$class_string);
            }
        }
         //get
        return view('admin.classes.schedule')->with(compact('my_courses'));
    }
    public function createJoinLive($id){
        $meeting = LiveClasses::where('meetingID',$id)->first();
        // dd($meeting);

        $title=$meeting['title'];
        $meetingID=$meeting['meetingID'];
        $classTime=$meeting['classTime'];
        $attendeePW=$meeting['attendeePW'];
        $moderatorPW=$meeting['moderatorPW'];
        $owner=$meeting['owner'];

        // dd($meeting);
        $user = \Auth::user();

        //get the secure salt
        $salt = env("BBB_SALT", "0");
        //get BBB server
        $bbb_server = env("BBB_SERVER", "0");

        //2.get the checksum(to be computer) and store it in column
        
            //name=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW
            //(a)==> prepend the action to the entire query
        $create_string="name=$title&meetingID=$meetingID&record=true&attendeePW=$attendeePW&moderatorPW=$moderatorPW";

        $newCreateString="create".$create_string;
                // createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444
        //createname=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW

            //(b)==> append the secret salt to end of the new query string with the action
                //secret salt: 639259d4-9dd8-4b25-bf01-95f9567eaf4b
                // $newString = createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444639259d4-9dd8-4b25-bf01-95f9567eaf4b
        //$newString = "createname=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW".$salt;
            //(c)==> get the sha1 of the new string and save it as checksum
        $checksumCreate=sha1($newCreateString.$salt);
        // echo $newCreateString;
        // echo "<br/>".$checksumCreate;


        $createURL = $create_string."&checksum=".$checksumCreate;
        $getCreateURL= $bbb_server.'create?'.$createURL;

        ///UNCOMMENT ALL FROM HERE TO SCHEDULE LIVE CLASS ON BBB SERVER

        //3.create a meeting
        //make get request to create live class
        // $client = new Client();
        // $response = $client->request('GET', $getCreateURL);
        // // $response = $client->request('GET', 'http://bbb.teledogs.com/bigbluebutton/api/create?name=Flirting&meetingID=quest&attendeePW=ap&checksum=bcfb49cc9dac7b0834c90f1604c7005b9079da7b');

        // $body = $response->getBody(); 
        // $xml = simplexml_load_string($body);


        //  Initiate curl
            $ch = curl_init();
            // Disable SSL verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the url
            curl_setopt($ch, CURLOPT_URL,$getCreateURL);
            // Execute
            $result=curl_exec($ch);
            // Closing
            curl_close($ch);
            // dd($result);
            // Print the return data
            // print_r(json_decode($result, true));
            // dd($url);
            // die();


            // $client = new Client();
            // $response = $client->request('GET', $getCreateURL);
            // $response = $client->request('GET', 'http://bbb.teledogs.com/bigbluebutton/api/create?name=Flirting&meetingID=quest&attendeePW=ap&checksum=bcfb49cc9dac7b0834c90f1604c7005b9079da7b');

            // $body = $response->getBody(); 
            $xml = simplexml_load_string($result);

        //.4 join the meeting(not now)
        if($xml->returncode == "SUCCESS"){
            //successful on bbb server

            $classRecord = [
            'meetingID'=>$meetingID,
            'users'=>$user['id']
            ];


            // $newLiveClass = LiveClasses::create($newLiveClass);
            $newRecord = LiveClassRecordings::create($classRecord);

            if($newRecord){
                // created successfully->proceeed to join
                //1.get the details of the logged in user
                $currentUserArray= explode(" ", $user['name']);
                // dd($user);

                if(count($currentUserArray) > 1){
                    //has firstname lastname
                    $currentUser=$currentUserArray[0]."_".$currentUserArray[1];//"test_user"
                }else{
                    $currentUser=$currentUserArray[0];//"test"
                }

                //check if user is presenter by default or not 
                //if not owner of class assign role of attendee
                $userPass= $user['id'] == $owner ? 
                $meeting->moderatorPW: $meeting->attendeePW ;   

                // dd($meetingID);     

                //2.get the checksum(to be computer) and store it in column
                $join_string="fullName=$currentUser&meetingID=$meetingID&password=$userPass";

                $newJoinString="join".$join_string;

                //(b)==> append the secret salt to end of the new query string with the action
                    //secret salt: 639259d4-9dd8-4b25-bf01-95f9567eaf4b
                    // $newString = createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444639259d4-9dd8-4b25-bf01-95f9567eaf4b
                //$newString = "createname=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW".$salt;
                    

                //(c)==> get the sha1 of the new string and save it as checksum
                $checksumJoin=sha1($newJoinString.$salt);

                $joinURL = $join_string."&checksum=".$checksumJoin;
                $getJoinURL= $bbb_server.'join?'.$joinURL;


                // $names=array();
                // //save details into the liveclassrecordings table
                // $names = DB::table('tbl_scheduled_classes_recordings')->where('meetingID', $meetingID)->value('users');
                // $namesArray = explode(",", $names);
                // array_push($namesArray,$user['id']);
                // $newlist=implode(",", $namesArray);
                // // dd($newlist);


                // $liveRecord=LiveClassRecordings::where('meetingID',$meetingID)->update(['users'=>$newlist]);

                // dd($getJoinURL);
                return redirect()->away($getJoinURL);


                // $url = url('user/live/'.$meetingID);
                // //successful
                // //UNCOMMENT THIS
                // // $update = User::where('email',$user['email'])->update(['token'=>$meetingID]);


                // // sendMail(['template'=>get_option('user_create_meeting_email'),'recipent'=>[$user['email']]]);

                // // return redirect()->back()->with('msg',trans('main.thanks_class'));
                // $class_string = 'Meeting created successfully!. Share -> '.$meetingID.' for others to join. Meeting details sent to your E-mail Address';
                // $class_string = "Meeting created successfully!. Share -> ".$meetingID." for others to join.\r\n<a href='$url'>$url</a>";
                // return redirect()->back()->with('flash_message_success',$class_string);

            }else{
                //not successful
                return redirect()->back()->with('msg',trans('main.error_class'));
            }
        }else{
           //not successful
           return redirect()->back()->with('msg',trans('main.error_class')); 
        }
    }
    public function createLiveClass(Request $request){
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();

        if($request->isMethod('post')){
            $data=$request->all();
            // dd($data);
            //post method
            $user = \Auth::user();
            $title="";
            //in order to schedule a class happens
            //1.get the details of the future class

            $title_array=explode(" ", $request->title);
            //check if name is has more than one
            $count=count($title_array);
            if($count > 1){
                //this is an array -> loop and get the elements and underscore them
                $title=$title_array[0];
                for($i=1;$i<$count;$i++){
                    $title=$title."-".$title_array[$i];
                }
            }else{
                //the title is one word e.g "testing"
                $title=$title_array[0];
            }

            //1.5 create a live class as an event
            $course_id = $data['course_id'];

            $t=time();
            $event_start_end = date("Y/m/d H:m:s",$t);
            // dd($event_start_end);


            $my_event = new Events;
            $my_event->title=$request['title'];
            $my_event->course_id=$course_id;
            $my_event->type='class';
            $my_event->event_start_time=$event_start_end;
            $my_event->event_end_time=$event_start_end;
            $my_event->color="#00c0ef";
            $my_event->save();
            // dd($event_start_end);

            $meetingID=str_random(6);
            $attendeePW=str_random(6);//"ap";//$request->attendeePW;
            $moderatorPW=str_random(6);//"mp";//$request->moderatorPW;


            //get the secure salt
            $salt = env("BBB_SALT", "0");
            //get BBB server
            $bbb_server = env("BBB_SERVER", "0");

            //2.get the checksum(to be computer) and store it in column
            
                //name=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW
                //(a)==> prepend the action to the entire query
            $create_string="name=$title&meetingID=$meetingID&record=true&attendeePW=$attendeePW&moderatorPW=$moderatorPW";

            $newCreateString="create".$create_string;
                    // createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444
            //createname=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW

                //(b)==> append the secret salt to end of the new query string with the action
                    //secret salt: 639259d4-9dd8-4b25-bf01-95f9567eaf4b
                    // $newString = createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444639259d4-9dd8-4b25-bf01-95f9567eaf4b
            //$newString = "createname=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW".$salt;
                //(c)==> get the sha1 of the new string and save it as checksum
            $checksumCreate=sha1($newCreateString.$salt);
            // echo $newCreateString;
            // echo "<br/>".$checksumCreate;


            $createURL = $create_string."&checksum=".$checksumCreate;
            $getCreateURL= $bbb_server.'create?'.$createURL;

            //3.create a meeting
            //make get request to create live class
            $url = $getCreateURL;


            //  Initiate curl
            $ch = curl_init();
            // Disable SSL verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the url
            curl_setopt($ch, CURLOPT_URL,$url);
            // Execute
            $result=curl_exec($ch);
            // Closing
            curl_close($ch);
            // dd($result);
            // Print the return data
            // print_r(json_decode($result, true));
            // dd($url);
            // die();


            // $client = new Client();
            // $response = $client->request('GET', $getCreateURL);
            // $response = $client->request('GET', 'http://bbb.teledogs.com/bigbluebutton/api/create?name=Flirting&meetingID=quest&attendeePW=ap&checksum=bcfb49cc9dac7b0834c90f1604c7005b9079da7b');

            // $body = $response->getBody(); 
            $xml = simplexml_load_string($result);

            //.4 join the meeting(not now)
            if($xml->returncode == "SUCCESS"){
                //successful on bbb server
                $newLiveClass= [
                'title'=>$title,//class title
                'meetingID'=>$meetingID,
                'course_id'=>$data['course_id'],
                'classTime'=>$event_start_end,//meeting ID
                'attendeePW'=>$attendeePW,//attendee password 
                'moderatorPW'=>$moderatorPW,//moderator password
                'owner'=>$user->id
                ];


                $classRecord = [
                'meetingID'=>$meetingID,
                'users'=>$user->id
                ];


                $newLiveClass = LiveClasses::create($newLiveClass);
                LiveClassRecordings::create($classRecord);

                if($newLiveClass){
                    $url = url('admin/live-classes/live/'.$meetingID);
                    //successful
                    //UNCOMMENT THIS
                    // $update = User::where('email',$user['email'])->update(['token'=>$meetingID]);


                    // sendMail(['template'=>get_option('user_create_meeting_email'),'recipent'=>[$user['email']]]);

                    // return redirect()->back()->with('msg',trans('main.thanks_class'));
                    // $class_string = 'Meeting created successfully!. Share -> '.$meetingID.' for others to join. Meeting details sent to your E-mail Address';
                    $class_string = "Meeting created successfully! Share -> ".$meetingID." for others to join or click the link <a href='$url'>$url</a>";
                    return redirect()->back()->with('flash_message_success',$class_string);

                }else{
                    //not successful
                    return redirect()->back()->with('flash_message_error',"An error occurred, please try again");
                }
            }else{
               //not successful
               return redirect()->back()->with('flash_message_error',"An error occurred, please try again"); 
            }
        }
        return view('admin.classes.create')->with(compact('my_courses'));
    }
    public function joinLiveClass($meetingID){
        $user = \Auth::user();
        $currentUser="";

        //get the secure salt
        $salt = env("BBB_SALT", "0");
        //get BBB server
        $bbb_server = env("BBB_SERVER", "0");

        //1.get the details of the logged in user
        $currentUserArray= explode(" ", $user->name);
        // dd($user);

        if(count($currentUserArray) > 1){
            //has firstname lastname
            $currentUser=$currentUserArray[0]."_".$currentUserArray[1];//"test_user"
        }else{
            $currentUser=$currentUserArray[0];//"test"
        }
        

        //get the details of the live class
        $live_class = LiveClasses::where('meetingID',$meetingID)->first();
        // dd($live_class->title); = "First Class"
        if($live_class == null){
            return redirect()->back()->with('flash_message_error','An error occurred when trying to join the class');
        }

        //check if user is presenter by default or not 
        //if not owner of class assign role of attendee
        $userPass=$user->id == $live_class['owner'] ? 
        $live_class->moderatorPW: $live_class->attendeePW ;   

        // dd($meetingID);     

        //2.get the checksum(to be computer) and store it in column
        $join_string="fullName=$currentUser&meetingID=$meetingID&password=$userPass";

        $newJoinString="join".$join_string;

        //(b)==> append the secret salt to end of the new query string with the action
            //secret salt: 639259d4-9dd8-4b25-bf01-95f9567eaf4b
            // $newString = createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444639259d4-9dd8-4b25-bf01-95f9567eaf4b
        //$newString = "createname=$title&meetingID=$meetingID&attendeePW=$attendeePW&moderatorPW=$moderatorPW".$salt;
            

        //(c)==> get the sha1 of the new string and save it as checksum
        $checksumJoin=sha1($newJoinString.$salt);

        $joinURL = $join_string."&checksum=".$checksumJoin;
        $getJoinURL= $bbb_server.'join?'.$joinURL;

        // dd($getJoinURL);
        $names=array();
        //save details into the liveclassrecordings table
        $names = DB::table('live_class_recordings')->where('meetingID', $meetingID)->value('users');
        $namesArray = explode(",", $names);
        array_push($namesArray,$user['id']);
        $newlist=implode(",", $namesArray);
        // dd($newlist);


        $liveRecord=LiveClassRecordings::where('meetingID',$meetingID)->update(['users'=>$newlist]);

        // dd($getJoinURL);
        return redirect()->away($getJoinURL);
    }
    public function joinClassByID(Request $request){
        return $this->joinLiveClass($request->meetingID);
    }
    public function getResourcesList($course_ids){
        //find lesson_ids belonging to those course_ids
        $lesson = Lesson::whereIn('course_id',$course_ids)->pluck('id')->toArray();
        if(!is_null($lesson)){
            //fetch
            // $media = Media::with('courses')->whereIn('model_id', $lesson)->get();
            // $media = DB::table('media')->whereIn('model_id', $lesson)->get();
            // ['id','model_id','model_type','collection_name','name','file_name','disk','size','manipulations','custom_properties','order_column','updated_at','created_at']; 


            $media = DB::table('media')
                    ->select('media.id as id','media.name as name','media.file_name as file_name','media.size as size','lessons.course_id as course_id','lessons.title as lesson_title','courses.title as course_title')
                    ->join('lessons', 'lessons.id', '=', 'media.model_id')
                    ->join('courses', 'courses.id', '=', 'lessons.course_id')
                    ->whereIn('model_id',$lesson)
                    ->orderBy('id','DESC')
                    ->get();
            // dd($media);
            return $media;
        }else{
            return "";
        }
    }
    public function fetchEnrolledCourseIDs(){
        //step1. get the courses where the teacher has created  and store ids in string
        $my_courses = CourseUser::where(['user_id'=> \Auth::id()])->get();
        $course_ids="";
        foreach ($my_courses as $key => $value) {
            $course_ids .= $value->course_id .",";
        }
        $courseIdsArray = explode(",", $course_ids);;
        return $courseIdsArray;
    }
    public function fetchFutureEvents(){
        $course_ids =$this->fetchEnrolledCourseIDs();
        $month = date('m');
        $monthly = DB::table('events')
                    ->select('events.id as id','events.title as title','events.type as type','events.event_start_time as event_start_time','events.event_end_time as event_end_time','events.color as color','courses.title as course_title','events.created_at as created_at')
                    ->join('courses', 'courses.id', '=', 'events.course_id')
                    ->whereIn('course_id',$course_ids)
                    ->where(function($q) {
                        $q->where('event_start_time', '>=', date("Y-m-d"));
                    })
                    ->orderBy('event_start_time','DESC')
                    ->get();//has events data for the current month
        return $monthly;           
    }
    public function fetchAllEvents(){
        $course_ids =$this->fetchEnrolledCourseIDs();


        $monthly = DB::table('events')
                    ->select('events.id as id','events.title as title','events.type as type','events.event_start_time as event_start_time','events.event_end_time as event_end_time','events.color as color','courses.title as course_title','events.created_at as created_at')
                    ->join('courses', 'courses.id', '=', 'events.course_id')
                    ->whereIn('course_id',$course_ids)
                    ->orderBy('event_start_time','DESC')
                    ->get();//has events data for the current month
        return $monthly;  
    }
    public function fetchMonthlyEvents(){
        $course_ids =$this->fetchEnrolledCourseIDs();

        $month = date('m');

        $monthly = DB::table('events')
                    ->select('events.id as id','events.title as title','events.type as type','events.event_start_time as event_start_time','events.event_end_time as event_end_time','events.color as color','courses.title as course_title','events.created_at as created_at')
                    ->join('courses', 'courses.id', '=', 'events.course_id')
                    ->whereIn('course_id',$course_ids)
                    ->whereMonth('event_start_time', $month)
                    ->orderBy('event_start_time','DESC')
                    ->get();//has events data for the current month
        return $monthly;            
    }
}
