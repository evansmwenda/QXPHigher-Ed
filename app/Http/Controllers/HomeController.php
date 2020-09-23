<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\EnrolledCourses;
use App\Events;
use App\Test;
use App\TestsResult;
use App\Lesson;
use App\LessonStudent;
use App\Assignments;
use App\SubmittedAssignments;
use App\QuestionTest;
use App\Question;
use App\ExamSubmits;
use App\ExamAnswers;
use App\LiveClasses;
use App\LiveClassRecordings;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;
use Auth;
use Session;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
// use Request;
use App\Http\Requests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class HomeController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function getFAQ(){
        //display the faqs
        return view('students.faq');
    }
    public function tRegister(Request $request){
        //get details of the teacher and register them here
        // DB::table('users')->insert(
        //     ['email' => 'john@example.com', 'votes' => 0]
        // );

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        DB::table('role_user')->insert(
            ['role_id' => 2, 'user_id' => $user->id]
        );
    }

    public function index(){
        $purchased_courses = NULL;
        if (\Auth::check()) {
            $purchased_courses = Course::whereHas('students', function($query) {
                $query->where('id', \Auth::id());
            })
            ->with('lessons')
            ->orderBy('id', 'desc')
            ->get();
        }
        $courses = Course::where('published', 1)->orderBy('id', 'desc')->get();
        return view('index', compact('courses', 'purchased_courses'));
    }

    public function landing(){
        if(\Auth::check()){
            $logged_in=true;
        }else{
            $logged_in = false;
        }


        $enrolled_course = DB::table('enrolled_courses')
        ->join('courses', 'courses.id', '=', 'enrolled_courses.course_id')
        // ->join('lessons', 'lessons.course_id', '=', 'courses.id')
        ->where('enrolled_courses.user_id', '=', \Auth::id())
        ->get();
        // dd($enrolled_course);
        $course_progress=[];//with different course progresses
        $progress_array=[];
        $badge_array=[];
        $prog_parent=[];
        
        foreach($enrolled_course as $course){
            //get the lesson ids and calculate percentage done
            $ids = explode(",", $course->lesson_id);
            $count = count(array_unique($ids));
            

            $percentage = ($count/$course->total_lessons)*100;
            if($percentage > 100){
                $percentage = 100.0;
            }


            $percentage = round($percentage);
            switch ($percentage) {
                case $percentage == 100:
                    $progress_parent = "progress progress-xs";
                    $progress_class="progress-bar progress-bar-success";
                    $badge_class="badge progress-bar-success";
                    break;
                case $percentage > 90:
                    $progress_parent = "progress progress-xs progress-striped active";
                    $progress_class="progress-bar progress-bar-success";
                    $badge_class="badge progress-bar-success";
                    break;
                case $percentage < 90 && $percentage > 30:
                    $progress_parent = "progress progress-xs progress-striped active";
                    $progress_class="progress-bar progress-bar-primary";
                    $badge_class="badge progress-bar-primary";
                    break;
                case $percentage < 30:
                    $progress_parent = "progress progress-xs progress-striped active";
                    $progress_class="progress-bar progress-bar-warning";
                    $badge_class="badge progress-bar-warning";
                    break;  
                default:
                    $progress_parent = "progress progress-xs progress-striped active";
                    $progress_class="progress-bar progress-bar-primary";
                    $badge_class="badge progress-bar-primary";
                    break;
            }
            array_push($badge_array, $badge_class);
            array_push($progress_array, $progress_class);
            array_push($course_progress, $percentage);
            array_push($prog_parent, $progress_parent);
        }
        // dd(\Auth::id());

        //
        //get results of any attempted quizes
        $tests_ids="";
        $my_results="";
        $result_array =[];
        $my_test_ids =[];
        if(\Auth::id() != null){
            $test_results = DB::table('tests_results')->where(['user_id'=> \Auth::id() ])->distinct('test_id')->orderBy('id','DESC')->get();
        
            foreach ($test_results as $test ) {
                $tests_ids .= $test->test_id.",";

                $result_array += [
                    $test->test_id => $test->test_result,
                ];

            }

            $my_test_ids = explode(",",$tests_ids);//convert to array
        }
        
        // dd($my_test_ids);

        $test_details = DB::table('tests')
                    ->select('tests.id as test_id','tests.title as title','tests.course_id as course_id','courses.title as name','courses.id as course_id')
                    ->join('courses', 'courses.id', '=', 'tests.course_id')
                    ->whereIn('tests.id', $my_test_ids)
                    // ->where('tests.id', $my_test_ids)
                    ->orderBy('tests.id','DESC')
                    ->get();

                    // dd($test_details);


        $assignments = $this->fetchAssignments();
        
        // dd($assignments);

        $monthly = $this->fetchFutureEvents();

        // dd(count($assignments));
        $count_assignments = count($assignments);
        $count_courses = count($enrolled_course);
        $count_exams = count($test_details);
        $count_events = count($monthly);


        return view('students.home_user')->with(compact(
            'test_details',
            'result_array',
            'enrolled_course',
            'course_progress',
            'progress_array',
            'badge_array',
            'prog_parent',
            'assignments',
            'monthly',
            'count_assignments',
            'count_courses',
            'count_exams',
            'count_events'
        ));
    }
    public function getCalender(){

        $monthly = $this->fetchMonthlyEvents();
         // dd($monthly);
        $event_array = (array) null; 
        foreach($monthly as $event){
            $event_array [] = array(
                "title" => $event->title,
                "start" => $event->event_start_time,
                "end" => $event->event_end_time,
                "backgroundColor" => $event->color,
                "borderColor" => $event->color,
                );
        }  
        // dd($event_array);          

        //match the dates to days
        return view('students.calender')->with(compact('event_array'));
    }
    public function getCalenderOld(){
        //step2. fetch the assignments in the enrolled courses of student
            // $ids_array = explode(",", $my_course_ids);

            // // $assignments = Assignments::whereIn('course_id', $ids_array)->get();
            // $assignments = C::with(['course'])->whereIn('course_id', $ids_array)->get();
        $course_ids="";
        $enrolled_courses =  EnrolledCourses::where(['user_id'=>\Auth::id()])->get(); 
        foreach ($enrolled_courses as $key => $course) {
            $course_ids .= $course->course_id .",";
               # code...
          }  
        $course_ids = explode(",", $course_ids);

        $month = date('m');
        $monthly = DB::table('events')
                    ->whereIn('course_id',$course_ids)
                    ->whereMonth('event_start_time', $month)->get();//has events data for the current month
        // dd($monthly);
        

        $eventDates_array = "";
        foreach($monthly as $key=>$event){
            //store the event date in array
            $event_start_date = $event->event_start_time;//"2020-06-17 13:00:00"
            $date_value =date('d',strtotime($event_start_date)); 
            $eventDates_array .= $date_value .",";
            
        }
        

        //get the month and the year
        $month_year = date("F Y", time());

        $current_time = date("Y-m-01",time());//first day of current month
        $end_time = date("Y-m-t",time());//last day of the current month

        $begin = new \DateTime( $current_time );
        $end = new \DateTime( $end_time);
        $end = $end->modify( '+1 day' );

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);//has days in the current month(1..31)
        // dd($daterange);

        $month_dates ="";
        
        $day_html =  "<ul class='days'>
                                <li class='day'>
                                    <div class='date'>X</div>                       
                                </li>
                      </ul>";
                      // dd($daterange);
        $startDate = reset($daterange);   
        // dd($startDate) ;  
        if($startDate->format("N") == "1"){
            # monday
            $month_dates .= $day_html;
        }else if($startDate->format("N") == "2") {
            # tuesday
            $month_dates .= $day_html.$day_html;
        }else if($startDate->format("N") == "3") {
            # wednesday
            $month_dates .= $day_html.$day_html.$day_html;            
        }else if($startDate->format("N") == "4") {
            # thursday
            $month_dates .= $day_html.$day_html.$day_html.$day_html;            
        }else if($startDate->format("N") == "5") {
            # friday
            $month_dates .= $day_html.$day_html.$day_html.$day_html.$day_html;            
        }else if($startDate->format("N") == "6") {
            # saturday
            $month_dates .= $day_html.$day_html.$day_html.$day_html.$day_html.$day_html;            
        }  


        $eventDates_array =explode(",", $eventDates_array);
        
        foreach($daterange as $date){
            if(in_array($date->format("d"), $eventDates_array)){
                // this day has event(s) connected to it

                //get the key of the date value
                $key = array_search($date->format("d"), $eventDates_array);
                // dd($key);
                $title = $monthly[$key]->title;
                $start_time = date("H:i",strtotime($monthly[$key]->event_start_time));//"2020-06-17 13:00:00"
                $end_time =date("H:i",strtotime($monthly[$key]->event_end_time));//"2020-06-17 13:00:00"
                //display the associated event
                $month_dates .= "<ul class='days'>
                                <li class='day'>
                                    <div class='date'>".$date->format("d")."</div>
                                    <div class='event'>
                                        <div class='event-desc'>"
                                        .$title.
                                        "
                                        </div>
                                        <div class='event-time'>
                                        ".$start_time." to ".$end_time."
                                        </div>
                                    </div> 

                                </li>
                            </ul>";
            }else{
                //display it as a blank date
                $month_dates .= "<ul class='days'>
                                <li class='day'>
                                    <div class='date'>".$date->format("d")."</div>                       
                                </li>
                            </ul>";
            }

        }
        $month_dates = "
                        <div id='calendar'>
                            <ul class='weekdays'>
                                <li>Sunday</li>
                                <li>Monday</li>
                                <li>Tuesday</li>
                                <li>Wednesday</li>
                                <li>Thursday</li>
                                <li>Friday</li>
                                <li>Saturday</li>
                            </ul>
                            ".$month_dates."
                        </div>    ";
       

        //match the dates to days
        return view('students.calender2')->with(compact('month_year','month_dates'));
    }
    public function getAssignments(Request $request){
         
        if($request->isMethod('post')){
            $method = "POST";
            $data=$request->all();
             // dd($data);
            // $request->validate([
            // 'file' => 'required|mimes:pdf,xlx,csv|max:2048',
            // ]);
            if($request->hasFile('assignment')){
                $image_tmp = $request->file('assignment');

                $extension = $image_tmp->getClientOriginalExtension();//txt,pdf,csv
                $filename = time().'.'.$extension;//1592819807.txt

                $storage_dir = 'uploads/assignments/'.$data['slug'].'/';
                // dd($storage_dir);

                $uploaded = $image_tmp->move($storage_dir, $filename);
                //store the filename into the db

                // $flight = new Flight;
                // $flight->name = $request->name;
                // $flight->save();

                if($uploaded){
                    //file was uploaded->insert to db
                    $my_assignment = new SubmittedAssignments;
                    $my_assignment->assignment_id=$data['assignment_id'];
                    $my_assignment->user_id=\Auth::id();
                    $my_assignment->filename=$filename;
                    $my_assignment->save();
       
                    return back()
                        ->with('flash_message_success','You have successfully submitted your assignment.');
                }else{
                    //file was not uploaded dont insert to db
                    return back()->with('flash_message_error','Sorry, there was an error uploading your assigment');
                }
            } 
   
           }else{
            $assignments = $this->fetchAssignments();
            
            $method="GET";
            //$assignments = Assignments::
        }
        return view('students.assignments')->with(compact('method','assignments'));
    }
    public function postAssignments(){
        return view('students.assignments');
    }
    public function getExams(){
        #1. get the courses student is taking
        $my_courses = EnrolledCourses::where(['user_id'=>\Auth::id()])->get();
        $my_course_ids="";
        foreach($my_courses as $course){
            $my_course_ids .= $course->course_id .","; 
        }

       #2. get the exams in those courses and display them.
        $ids_array = explode(",", $my_course_ids);

        // $assignments = Assignments::whereIn('course_id', $ids_array)->get();
        $exams = Test::with(['course'])->whereIn('course_id', $ids_array)->get();
        // dd($exams);
        
        return view('students.exams')->with(compact('exams'));
    }
    public function postExams(Request $request,$id=null){
        if($request->isMethod('post')){
            $method = "POST";
            $data=$request->all();

            
            // array:9 [▼
            //   "_token" => "e7bm1BiFUIR&YToXox9k3ZhjRg4QMYMtUIIE3uDy"
            //   "_count" => "3"
            //   "test_id" => "63"
            //   "question0" => "81"
            //   "answer0" => array:1 [▼
            //     0 => "Uhuru Kenyatta"
            //   ]
            //   "question1" => "82"
            //   "answer1" => "Daniel"
            //   "question2" => "83"
            //   "answer2" => array:1 [▼
            //     0 => "!false"
            //   ]
            // ]
            $count = $data['_count'];
            $test_id= $data['test_id'];

            // dd($data['answer1'][0]);
            #1.insert into exam-submits table
            $my_submits_row = ExamSubmits::where('test_id',$test_id)->get()->first();
            // dd($my_submits_row['id']);
            $my_submits = ExamSubmits::where('test_id',$test_id)->value('user_id');
            if(is_null($my_submits)){
                //no student has attempted this test before->insert to row

                $exam_submits = new ExamSubmits;
                $exam_submits->test_id = $test_id;
                $exam_submits->user_id = \Auth::id() . ',';
                $exam_submits->save();

            }else{
                //student(s) have attempted the test 
                //append student user id
                $my_submits .= \Auth::id() . ',';
                //update the  column
                $exam_submits = ExamSubmits::find($my_submits_row['id']);
                $exam_submits->user_id = $my_submits;
                $exam_submits->save();
            }
            // dd($my_submits);

            $question_array="";
            $my_data = [];
            for($i=0;$i<$count;$i++){

                //get current question id
                $question = 'question'.$i;
                $question_id= $data[$question];

                //get current answer
                $answer = 'answer'.$i;
                $answer= $data[$answer];
                //check if array
                if(is_array($answer)){
                    //get the first object in array
                    $my_answer = $answer[0];
                }else{
                    $my_answer = $answer;
                }

                //store all the fields in array
                $my_answers = array(
                    "test_id"     => $test_id,
                    "question_id" => $question_id,
                    "answer"      => $my_answer,
                    "user_id"     => \Auth::id()
                );
                array_push($my_data, $my_answers);

                // $question_array .= $my_answer.',';

                // $my_answers = new ExamAnswers;
                // $my_answers->test_id=$test_id;
                // $my_answers->question_id=$question_id;
                // $my_answers->answer=$my_answer;
                // $my_answers->user_id=\Auth::id();
                // $my_answers->save();

                
                // $question_array .= $question_id.',';

                //loop through the answers
            }
            ExamAnswers::insert($my_data);
            // dd($my_data);


            return redirect('/exams')->with('flash_message_success','Exam submitted successfully');
        }else{
            //get the course details
            $test_details = Test::with(['course'])->where('id', $id)->first();

            #1. get the questions in that test id
            $my_questions_test = QuestionTest::where(['test_id'=>$id])->get();
            
            $my_questions_ids="";
            foreach($my_questions_test as $question){
                $my_questions_ids .= $question->question_id .","; 
            }
            
            $questions_array = explode(",", $my_questions_ids);
            #2. get the questions and check in question_options for their options
            $exams = Question::with(['options'])->whereIn('id', $questions_array)->get();
            // dd();
            $questions_count =count($exams); 
            
            return view('students.exams_attempt')->with(compact('exams','test_details','id','questions_count')); 
        }
        
        
    }
    public function enrollCourse($course_id){
        //enroll to this course
        //get user id
        // $course_details = Course::where(['published'=> 1,'id'=>$course_id])->get();
        // dd($course_id);
        if (\Auth::check()) {
            $course_details = Course::where(['published'=> 1,'id'=>$course_id])->get();
            // dd($course_details[0]->id);
            $course_id = strval($course_details[0]->id);
            // dd($course_id);
            $total_lessons = Lesson::where(['course_id'=> $course_id])->get();
            // $total_lessons = Lesson::where(['course_id'=> '14'])->get();
            
            // dd($total_lessons);
            //add course to my courses section
            $myCourse = DB::table('course_student')
            ->updateOrInsert(
            [
                'course_id' => $course_details[0]->id,
                'user_id' => \Auth::id(),
                'rating' => '5'
            ]
            );

            // $myCourse = [
            //     'course_id' => $course_details[0]->id,
            //     'user_id' => \Auth::id(),
            //     'rating' => '4'
            // ];

            

            // $total_lessons = $total_lessons->count();

            $newEnrolledCourse = [
                'course_id' => $course_details[0]->id,
                'lesson_id' => $total_lessons[0]->id,
                'user_id' => \Auth::id(),
                'total_lessons' => $total_lessons->count()
            ];
            // dd($newEnrolledCourse);


            $newEnrolledCourse = EnrolledCourses::updateOrCreate($newEnrolledCourse);
            if($newEnrolledCourse){
                //course enrolled
                return redirect()->back()->with('flash_message_success','You have enrolled to course successfully');
            }
        }
    }

    public function getLiveClass(){
        return view('students.liveclasses');
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

    public function fetchAssignments(){
        $ids_array = $this->fetchEnrolledCourseIDs();

        // $assignments = Assignments::whereIn('course_id', $ids_array)->get();
        $assignments = Assignments::with(['course'])->whereIn('course_id', $ids_array)->get();
        //dd($assignments);
        return $assignments;
    }
    public function fetchEnrolledCourseIDs(){
        //step1. get the courses where the student is enrolled in and store ids in string
        $course_ids="";
        $enrolled_courses =  EnrolledCourses::where(['user_id'=>\Auth::id()])->get(); 
        foreach ($enrolled_courses as $key => $course) {
            $course_ids .= $course->course_id .",";
               # code...
          }  
        $courseIdsArray = explode(",", $course_ids);
        return $courseIdsArray;
    }
    public function fetchFutureEvents(){
        $course_ids =$this->fetchEnrolledCourseIDs();

        $month = date('m');

        $monthly = DB::table('events')
                    ->select('events.id as id','events.title as title','events.event_start_time as event_start_time','events.event_end_time as event_end_time','events.color as color','courses.title as course_title')
                    ->join('courses', 'courses.id', '=', 'events.course_id')
                    ->whereIn('course_id',$course_ids)
                    ->where(function($q) {
                        $q->where('event_end_time', '>=', date("Y-m-d"))
                          ->orWhereNull('event_end_time');
                    })
                    ->orderBy('event_start_time','DESC')
                    ->get();//has events data for the current month
        return $monthly;            
    }
    public function fetchMonthlyEvents(){
        $course_ids =$this->fetchEnrolledCourseIDs();

        $month = date('m');

        $monthly = DB::table('events')
                    ->select('events.id as id','events.title as title','events.event_start_time as event_start_time','events.event_end_time as event_end_time','events.color as color','courses.title as course_title')
                    ->join('courses', 'courses.id', '=', 'events.course_id')
                    ->whereIn('course_id',$course_ids)
                    ->whereMonth('event_start_time', $month)
                    ->orderBy('event_start_time','DESC')
                    ->get();//has events data for the current month
        return $monthly;            
    }

    public function getSubscription(){
        return view('students.subscribe');
    }
    public function startSubscription($id=null){
         // dump("cl/**/icked renew page");
         return view('students.subscribe');
    }
}
