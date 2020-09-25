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
        ->limit(4)
        ->orderBy('course_id','DESC')
        ->get();

        $course_ids = CourseUser::where('user_id',\Auth::id())->pluck('course_id');
        $count_courses = count($courses);
        // dd($courses);

        //fetch my events
        $events = Events::with('course')
        ->whereIn('course_id',$course_ids)
        ->limit(4)
        ->orderBy('id','DESC')
        ->get();
        $count_events = count($events);
      
        //fetch my assignments
        $assignments = Assignments::with('course')
        ->whereIn('course_id',$course_ids)
        ->limit(4)
        ->orderBy('id','DESC')
        ->get();
        $count_assignments = count($assignments);

        //fetch my exams
        $exams = Test::with('course')
        ->whereIn('course_id',$course_ids)
        ->limit(4)
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
            'exams',
            'count_exams'
        ));
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

                    // dd($my_assignment);
                    $my_assignment->save();
       
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

        
         //get
        return view('admin.events.index')->with(compact('my_events'));
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

    public function getExams(){

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
                "start" => "2020-07-21 10:00:00",
                "backgroundColor" => "#00a65a",
                "borderColor" => "#00a65a",
            )
        );

        //1.get all course_ids belonging to this user
        $my_courses = CourseUser::where(['user_id'=> \Auth::id()])->get();

        $course_ids="";
        foreach ($my_courses as $key => $value) {
            $course_ids .= $value->course_id .",";
        }
        $course_ids = explode(",", $course_ids);
        $my_tests = Test::with(['course'])->whereIn('course_id',$course_ids)->get();
        // dd($my_tests);
        //dd($my_courses[0]->course->title);//"Biology 101"
        return view('admin.exams.index')->with(compact('my_tests'));
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
            'd' => 4, 
            'e' => 5);

         echo json_encode($arr);
    }

    public function attemptedExams(String $id=null){
        //get list of students who attempt test
        $test = Test::where('id',$id)->get()->first();
        // dd($test);
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

        return back()->with('flash_message_success','Your exam was deleted!');
    }
    public function liveClasses(){
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();        
        return view('admin.classes.index')->with(compact('my_courses'));
    }
    public function scheduleLiveClass(Request $request){
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
        $course_id = $request['course_id'];
        $event_start_end = $request['event_start_end'];

        $event_start_end = explode(" - ", $event_start_end);
         // 0 => "2020-06-23 00:00:00"
         // 1 => "2020-06-23 23:59:59"
        // dd($event_start_end[0]);
        // dd(date("H:i", strtotime("04:25 PM"));)


        $my_event = new Events;
        $my_event->title=$request['title'];
        $my_event->course_id=$course_id;
        $my_event->event_start_time=$event_start_end[0];
        $my_event->event_end_time=$event_start_end[1];
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
            'meetingID'=>$meetingID,//meeting ID
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
}
