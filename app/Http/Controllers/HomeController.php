<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\EnrolledCourses;
use App\RequestEnrollment;
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
use App\Transaction as MyTransactions;
use App\Package;
use App\Subscription;
use App\library\OAuth;
use App\Task;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;
use Auth;
use Session;
use App\Events\PaymentSuccessfulEvent;
use AfricasTalking\SDK\AfricasTalking;

// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
// use Request;
use App\Http\Requests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Hash;
use App\Events\NewUserRegisteredEvent;

class HomeController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(){
        $data=array(
            'id' =>19,
            'link'=>"https://www.google.com",
            'name' => 'Evans Mwenda',
            'email'=>"evansmwenda.em@gmail.com"
        );

        event(new NewUserRegisteredEvent($data));
    }
    public function checkMySubscriptionStatus(){
        //get package status
        $subscription = Subscription::with('package')->where('user_id',\Auth::id())->get();
        // dd($subscription);
        // Date('Y-m-d h:i:s', strtotime('+14 days')),       
        $date_now = date("Y-m-d  h:i:s"); // this format is string comparable
        $expiry_on =$subscription[0]->expiry_on;
        if($expiry_on > $date_now){
            //subscription valid for either trial period or a specific plan
            if($subscription[0]->package_id == '0'){
                //user is on free trial
                $active = false;//user is on free trial
            }else{
                //user has a valid paid plan
                $active = true;//subscription is active
            }
           
        }else{
            //even if its free version(0) , it has expired
            $active = false;//expired or is on free trial
        }
        return $active;
    }
    public function sendPaymentNotification(){
        //test function to send sms
        if(!is_null(\Auth::id())){
            $data = \Auth::user();
            // dd($data);
            $sms_recipients="";//empty string

            $username = getenv("AFRICASTALKING_USERNAME");
            $apiKey   = getenv("AFRICASTALKING_API_KEY");

            $AT       = new AfricasTalking($username, $apiKey);
            // Get one of the services
            $sms      = $AT->sms();

            // Set the numbers you want to send to in international format
            $recipients='+254718145956';//$data['phone'];//'+254712345678'

            // Set your message
            $message    = "Dear Customer,your payment was successful.";

            // Set your shortCode or senderId
            $from       = "QXP";
            // Get one of the services
            try {
                // Thats it, hit send and we'll take care of the rest
                $result = $sms->send([
                    'to'      => $recipients,
                    'message' => $message,
                    'from'    => $from
                    
                ]);

                // print_r($result);
                    
            } catch (Exception $e) {
                //echo "Error: ".$e->getMessage();
                $result=$e->getMessage();
                // print_r($result);
            }
        }
        // dd("logged out");
        
    }

    public function allquizzes(){
        //fetch the quizes in the courses the student is enrolled to
        $course_ids_array = $this->fetchEnrolledCourseIDs();
        $my_quizzes = Test::with(['course','lesson'])->where('course_id',$course_ids_array)->where('lesson_id','!=',NULL)->get();
        // dd($my_quizzes);
        return view('students.allquizes')->with(compact('my_quizzes'));
    }
    public function account(Request $request){
        $user = \Auth::user();
        
        if($request->isMethod('post')){
            //save user details
            // dd($user);
            // $update = User::find($user->id);
            // $user = new User;
            $user->name=$request->username;
            $user->phone=$request->phone;
            $user->save();
            return redirect()->back()->with("flash_message_success","User Details Updated Successfully");

        }
        return view('students.account')->with(compact('user'));
    }
    public function createTask(Request $request, Task $task){
        $task->task_date=$request->date;
        $task->user_id=\Auth::id();
        $task->title=$request->title;
        $task->save();
        return redirect('/calender');
    }

    public function qxplanding(){
        return view('landing');
    }

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
        //always check if any payments made that dont havent been used
        $this->checkPaymentStatusDashboard();

        $purchased_courses = NULL;
        if (\Auth::check()) {
            $enrollments = DB::table('enrolled_courses')
                ->select(
                'enrolled_courses.course_id as id',
                'courses.title as title',
                'courses.slug as slug',
                'courses.description as description',
                'courses.price as price',
                'courses.course_image as course_image',
                'courses.start_date as start_date',
                'courses.published as published',
                'courses.created_at as updated_at',
                'courses.updated_at as course_updated_at',
                'courses.deleted_at as deleted_at'
                )
                ->join('courses', 'courses.id', '=', 'enrolled_courses.course_id')
                ->where('user_id',\Auth::id())
                ->orderBy('enrolled_courses.id','DESC')
                ->get();
            // dd($enrollments);
            $purchased_courses = Course::whereHas('students', function($query) {
                $query->where('id', \Auth::id());
            })
            ->with('lessons')
            ->orderBy('id', 'desc')
            ->get();
            // dd($purchased_courses);
            //purchased courses are the onees that the student has paid for or rated,
            //enrolled courses are the ones they have been enrolled into
        }
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

        $active=$this->checkMySubscriptionStatus();

        $assignments = $this->fetchAssignments();

        $courses = Course::where('published', 1)->orderBy('id', 'desc')->get(); 
        return view('index', 
        compact('courses', 
        'purchased_courses',
        'test_details',
        'assignments',
        'active',
        'enrollments',
        'result_array'));
    }
    public function verify(){
        $email= \Auth::user()->email;
        return view('auth.verify_email')->with(compact('email'));
    }
    public function sendActivate(Request $request){
        //this function sends activation email to those  who havent activated their accounts
        $user = User::where('email',$request->email)->get()->first();
        // dump($user);
        //generate new token
        $token = str_random(15);
        // dump($token);
        $update = User::where('email',$request->email)->update(['token'=>$token]);
        // dump($update);
        if($update) {
            //write code to send activation email
            //http://localhost.com/register/activate/9TT0e3YmDUV20f8
            $url = url('register/activate/'.$token);
            // dump($url);
            $data=array(
                'link'=>$url,
                'name' => $user->name,
                'email'=>$request->email
            );
            Mail::to($request->email)->send(new RegisterMail($data));
            return back()->with('flash_message_success', 'Account Activation Email Sent');
        }
    }
    public function accountActivate($token=null){
        //this function checks if token exists and updates the activation status and redirects to home page
        $user = User::with('role')->where('token',$token)->first();
        // dd($user->role[0]->id);
        if(!is_null($user)){
            //token valid
            $user->verified = 1;
            $user->token = NULL;
            $user->save();
            //check if user is student or teacher
            switch($user->role[0]->id){
                case 2:
                    #teacher
                    return redirect('/admin/home');
                    break;
                case 3:
                    #student
                    return redirect()->route('home-user');
                    break;
            }
        }else{
            // dump("its null");
            $url = url('/login');
            // dd($url);
            return redirect()->away($url);
        }
        
    }
    public function landing(Request $request){
        if(!\Auth::check()){
             $logged_in = false;
            return redirect('/welcome');
        }else{
            $logged_in=true;
                $status = User::where('email',\Auth::user()->email)->value('verified');
                if($status == '0'){
                    return redirect()->route('verify-user');
                
            }
            //user verified->check if have active subscription
            $active=$this->checkMySubscriptionStatus();
          
        }
        $this->checkPaymentStatusDashboard();


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
            
            if($course->total_lessons > 0){
                $percentage = ($count/$course->total_lessons)*100 ;
            }else{
                $percentage = 0;
            }
            // $percentage = empty(($count/$course->total_lessons)*100) ? 0: ($count/$course->total_lessons)*100;
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
        // dd($enrolled_course);
        $messages = RequestEnrollment::where('student_id',\Auth::user()->id)->orderBy('read','ASC')->get();
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
            'count_events',
            'messages'
        ));
    }
    public function getCalender(){
        //get user tasks
        $user_tasks=Task::where('user_id',\Auth::id())->get();
        $yearly =$this->fetchAllEvents();
        $monthly = $this->fetchFutureEvents();
        // $monthly = $this->fetchAllEvents();
         // dd($monthly);
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
        // dd($assignment_event_array);          

        //match the dates to days
        return view('students.calender')->with(compact('event_array','user_tasks','class_event_array'
            ,'exam_event_array','assignment_event_array'));
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
        $assignments = $this->fetchAssignments();
        // dd($assignments);
        $tests_ids="";
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
        
        $method="GET";
        return view('students.assignments')
        ->with(compact(
        'method',
        'assignments',
        'test_details',
        'result_array'));
    }
    public function displayAssignment(Request $request,$id=null){
        
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
            $assignment =  Assignments::find($id);

            //check if student submitted assignment previously
            $submitted = SubmittedAssignments::where('assignment_id',$id)->where('user_id',\Auth::id())->get();
            // dd($submitted);
            
            $method="GET";
            //$assignments = Assignments::
        }
        $all_assignments = $this->fetchAssignments();
        // dd($assignments);
        return view('students.assignment_display')
        ->with(compact(
        'method',
        'assignment',
        'all_assignments',
        'submitted'));
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
        $exams = Test::with(['course'])->whereIn('course_id', $ids_array)->paginate(4);
        // dd($exams);

        //get the test_ids in the courses enrolled by student
        $tests = Test::whereIn('course_id', $ids_array)->get();
        $my_tests_ids="";
        foreach($tests as $test){
            $my_tests_ids .= $test->id .","; 
        }
        $my_tests_ids_array = explode(",", $my_tests_ids);

        //get all test results for logged in user
        $test_results = TestsResult::where('user_id',\Auth::id())->whereIn('test_id',$my_tests_ids_array)->get();
        
        //get the examsubmits for those tests
        $submitted_exams = DB::table('exam_submits')
                    ->select('exam_submits.id as id','exam_submits.test_id as test_id',
                    'exam_submits.user_id as user_id','tests.title as test_title',
                    'courses.title as course_title','course_user.user_id as owner_id',
                    'users.name as owner_name')
                    ->join('tests', 'tests.id', '=', 'exam_submits.test_id')
                    ->join('courses', 'courses.id', '=', 'tests.course_id')
                    ->join('course_user', 'course_user.course_id', '=', 'courses.id')
                    ->join('users', 'users.id', '=', 'course_user.user_id')
                    ->whereIn('test_id',$my_tests_ids_array)
                    ->get();
                    // dd($submitted_exams);

        $my_submitted_exams =[];
        foreach($submitted_exams as $submitted){
            //check if the user id is among those who have submitted
            $userids_array = explode(",", $submitted->user_id);
            if(in_array(\Auth::id(),$userids_array)){
                //student had submitted this exam->add to array
                array_push($my_submitted_exams, $submitted);
            }            
        }
        // dd($my_submitted_exams);
        
        return view('students.exams')->with(compact('exams','my_submitted_exams','test_results'));
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
    public function certificates(){
        return view('students.certificates');
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
        $course_ids="";
        $enrolled_courses =  EnrolledCourses::where(['user_id'=>\Auth::id()])->get(); 
        foreach ($enrolled_courses as $key => $course) {
            $course_ids .= $course->course_id .",";
               # code...
          }  
        $course_ids = explode(",", $course_ids);

        $my_classes = LiveClasses::with(['course'])
        ->whereIn('course_id',$course_ids)
        ->where(function($q) {
            $q->where('classTime', '>=', date("Y-m-d"));
              // ->orWhereNull('classTime');
        })
        ->orderBy('id','DESC')
        ->get();

        //user verified->check if have active subscription
        $active=$this->checkMySubscriptionStatus();

        
         return view('students.liveclasses')->with(compact('my_classes','active'));
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
                    ->select('events.id as id','events.title as title','events.type as type','events.event_start_time as event_start_time','events.event_end_time as event_end_time','events.color as color','courses.title as course_title','events.created_at as created_at')
                    ->join('courses', 'courses.id', '=', 'events.course_id')
                    ->whereIn('course_id',$course_ids)
                    ->where(function($q) {
                        $q->where('event_start_time', '>=', date("Y-m-d"));
                          // ->orWhereNull('event_end_time');
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
    public function awardFreeTrialAll(){
        //gives every user account free trial
        $users  = User::get();
        // dd($users);
        $data  = array ();
        foreach($users as $user){
            $data = array(
                    "user_id" => $user->id,
                    "expiry_on" => Date('Y-m-d h:i:s', strtotime('+10 days')),
                    "package_id" => '0'
            );
            Subscription::insert($data); 
        }
    }
    public function getSubscription(){
        $subscription = Subscription::with('package')->where('user_id',\Auth::id())->first();
        // dd($subscription);

        // Date('Y-m-d h:i:s', strtotime('+14 days')),       
        $date_now = date("Y-m-d  h:i:s"); // this format is string comparable
        $expiry_on =$subscription->expiry_on;
        if($expiry_on > $date_now){
            $active = true;//subscription is active
        }else{
            $active = false;//expired or is on free trial
        }
        
        return view('students.subscribe')->with(compact('subscription', 'active','expiry_on'));
    }
    public function startSubscription($id=null){
        $user = \Auth::user();

        $packages = Package::where('id',$id)->get();
        
        if($packages->isEmpty()){
            return back()->with('flash_message_error','An error occurred, please try again');
        }
        
        //check to see if user is logged in
        if(\Auth::id() == ""){
            return back()->with('flash_message_error','Please login to renew subscription');
        }
        // dd($packages);
        $subscription = Subscription::with('package')->where('user_id',\Auth::id())->first();
        // Date('Y-m-d h:i:s', strtotime('+14 days')),       
        $date_now = date("Y-m-d  h:i:s"); // this format is string comparable
        $expiry_on =$subscription->expiry_on;
        if($expiry_on > $date_now){
            $active = true;//subscription is active
        }else{
            $active = false;//expired or is on free trial
        }

        
        $isDemo = env('PESAPAL_IS_DEMO',true);//check if we are in sandbox mode
        if($isDemo)
            $api = 'https://demo.pesapal.com';
        else
            $api = 'https://www.pesapal.com';
        
        $token = $params    = NULL;
        $iframelink         = $api.'/api/PostPesapalDirectOrderV4';

        //Kenyan keys
        $consumer_key       = env('PESAPAL_CONSUMER_KEY','');
        $consumer_secret    = env('PESAPAL_CONSUMER_SECRET','');
         
        $signature_method   = new \OAuthSignatureMethod_HMAC_SHA1();
        $consumer           = new \OAuthConsumer($consumer_key, $consumer_secret);
        
        // dd($packages);
        $package_name = $packages[0]->name;
        $amount = str_replace(',','',$packages[0]->amount);// $_POST['amount'];
        // $amount = number_format($amount, 2);//format amount to 2 decimal places

        $desc ="description";// ;
        $type ="MERCHANT";// ; //default value = MERCHANT
        $reference =uniqid();// //unique order id of the transactionby merchant
        $name = explode(" ", $user['name']);
        $first_name =$name[0];
        if(count($name) >1 ){
            $last_name =$name[1];
        }else{
            $last_name ='';
        }
        
        $email =$user['email'];
        $phonenumber =$user['phone'];

        $is_used="0";
        $status = 'PLACED';
        $currency ='KES';
        $tracking_id = '';
        $payment_method = '';//CHANGE LATER
        $callback_url   = url("payments/redirect");//URL user to be redirected to after payment
        // dd($callback_url);
        // https://skytoptechnologies.com
        // /?pesapal_transaction_tracking_id=058e9adb-d351-4092-9df7-0bd776900859
        // &pesapal_merchant_reference=5f2ad92d9dc87


        $transactions = new MyTransactions;
        $transactions->user_id       = $user['id'];
        $transactions->phone         = $user['email'];
        $transactions->amount        = $amount;
        $transactions->currency      = $currency;
        $transactions->status        = $status;
        $transactions->reference     = $reference;
        $transactions->is_used       = $is_used;
        $transactions->description   = $desc;
        $transactions->tracking_id   = $tracking_id;
        $transactions->payment_method= $payment_method;
        $transactions->save();

        //update the package_id in user's subscription
        $subscription = Subscription::where('user_id',$user['id'])->first();
        $subscription->package_id = $id;
        $subscription->save();
        
        $post_xml   = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                       <PesapalDirectOrderInfo 
                            xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
                            xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" 
                            Currency=\"".$currency."\" 
                            Amount=\"".$amount."\" 
                            Description=\"".$desc."\" 
                            Type=\"".$type."\" 
                            Reference=\"".$reference."\" 
                            FirstName=\"".$first_name."\" 
                            LastName=\"".$last_name."\" 
                            Email=\"".$email."\" 
                            PhoneNumber=\"".$phonenumber."\" 
                            xmlns=\"http://www.pesapal.com\" />";
        $post_xml = htmlentities($post_xml);
        
        //post transaction to pesapal
        $iframe_src = \OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);

        // return view('user.payments.iframe')->with(compact('iframe_src','amount','package_name'));
         return view('students.subscribe')->with(compact(
             'iframe_src',
             'amount',
             'package_name',
             'subscription', 
             'active',
             'expiry_on'
            ));
    }
    public function getCallback(Request $request){
        //get package status
        $subscription = Subscription::with('package')->where('user_id',\Auth::id())->get();
        // Date('Y-m-d h:i:s', strtotime('+14 days')),       
        $date_now = date("Y-m-d  h:i:s"); // this format is string comparable
        $expiry_on =$subscription[0]->expiry_on;
        if($expiry_on > $date_now){
            $active = true;//subscription is active
        }else{
            $active = false;//expired or is on free trial
        }

        $user= \Auth::user();
        // $status='UNKNOWN';
        // dd($request->all());
        $tracking_id = $request['pesapal_transaction_tracking_id'];
        $reference = $request['pesapal_merchant_reference'];
        // dump($request->all());

        /** check status of the transaction made
          *There are 3 available API
          *checkStatusUsingTrackingIdandMerchantRef() - returns Status only. 
          *checkStatusByMerchantRef() - returns status only.
          *getMoreDetails() - returns status, payment method, merchant reference and pesapal tracking id
        **/
        
        //$status           = $this->checkStatusByMerchantRef($reference);
        $responseArray    = $this->getTransactionDetails($reference,$tracking_id);
        // $status             = $this->checkStatusUsingTrackingIdandMerchantRef($reference,$tracking_id);
        // dd($responseArray);
        $transactions = MyTransactions::where('reference',$responseArray['pesapal_merchant_reference'])->first();
        if(!is_null($transactions)){
            //found transaction->updated details
            $transactions->status=$responseArray['status'];
            $transactions->payment_method=$responseArray['payment_method'];
            $transactions->tracking_id=$responseArray['pesapal_transaction_tracking_id'];
            $transactions->save();

            //write code to update data in the subscription table
            // getSubscriptions($user_id,$transaction_status);
            $this->buySubscription($user['id'],$transactions->status,$reference);   
        }
        $status = $responseArray['status'];
        // dd($transactions);

        
        //At this point, you can update your database.
        //In my case i will let the IPN do this for me since it will run
        //IPN runs when there is a status change  and since this is a new transaction, 
        //the status has changed for UNKNOWN to PENDING/COMPLETED/FAILED

        //make query to check status here
        return view('students.redirect')->with(compact('status',
        'reference',
        'tracking_id',
        'subscription',
        'active',
        'expiry_on'));
    }
    public function checkStatusUsingTrackingIdandMerchantRef($pesapalMerchantReference,$pesapalTrackingId){
        //checkStatusUsingTrackingIdandMerchantRef($pesapalMerchantReference,$pesapalTrackingId)
        $token = $params    = NULL;
        //Kenyan Merchant
        $consumer_key       = env('PESAPAL_CONSUMER_KEY','');
        $consumer_secret    = env('PESAPAL_CONSUMER_SECRET','');

        $signature_method   = new \OAuthSignatureMethod_HMAC_SHA1();
        $consumer           = new \OAuthConsumer($consumer_key, $consumer_secret);
        
        $isDemo =env('PESAPAL_IS_DEMO',true);//check if we are in sandbox mode
        if($isDemo)
            $api = 'https://demo.pesapal.com';
        else
            $api = 'https://www.pesapal.com'; 
            
        $QueryPaymentStatus               =   $api.'/API/QueryPaymentStatus';
        // $QueryPaymentStatusByMerchantRef  =   $api.'/API/QueryPaymentStatusByMerchantRef';
        // $querypaymentdetails              =   $api.'/API/querypaymentdetails';

        //get transaction status
        $request_status = \OAuthRequest::from_consumer_and_token(
                                $consumer, 
                                $token, 
                                "GET", 
                                $QueryPaymentStatus, 
                                $params
                            );
        $request_status->set_parameter("pesapal_merchant_reference", $pesapalMerchantReference);
        $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
        $request_status->sign_request($signature_method, $consumer, $token);
        
        $status = $this->curlRequest($request_status);
    
        return $status;
    }
    public function checkStatusByMerchantRef($pesapalMerchantReference){
        //checkStatusByMerchantRef($pesapalMerchantReference)
        $token = $params    = NULL;
        //Kenyan Merchant
        $consumer_key       = env('PESAPAL_CONSUMER_KEY','');
        $consumer_secret    = env('PESAPAL_CONSUMER_SECRET','');

        $signature_method   = new \OAuthSignatureMethod_HMAC_SHA1();
        $consumer           = new \OAuthConsumer($consumer_key, $consumer_secret);
        
        $isDemo =env('PESAPAL_IS_DEMO',true);//check if we are in sandbox mode
        if($isDemo)
            $api = 'https://demo.pesapal.com';
        else
            $api = 'https://www.pesapal.com'; 
            
        // $QueryPaymentStatus               =   $api.'/API/QueryPaymentStatus';
        $QueryPaymentStatusByMerchantRef  =   $api.'/API/QueryPaymentStatusByMerchantRef';
        // $querypaymentdetails              =   $api.'/API/querypaymentdetails';

        $request_status = \OAuthRequest::from_consumer_and_token(
                                $consumer, 
                                $token, 
                                "GET", 
                                $QueryPaymentStatusByMerchantRef, 
                                $params
                            );
        $request_status->set_parameter("pesapal_merchant_reference", $pesapalMerchantReference);
        $request_status->sign_request($signature_method, $consumer, $token);
    
        $status = $this->curlRequest($request_status);
    
        return $status;
    }
    public function getTransactionDetails($pesapalMerchantReference,$pesapalTrackingId){
        //getTransactionDetails($pesapalMerchantReference,$pesapalTrackingId);
        $token = $params    = NULL;
        //Kenyan Merchant
        $consumer_key       = env('PESAPAL_CONSUMER_KEY','');
        $consumer_secret    = env('PESAPAL_CONSUMER_SECRET','');

        $signature_method   = new \OAuthSignatureMethod_HMAC_SHA1();
        $consumer           = new \OAuthConsumer($consumer_key, $consumer_secret);
        
        $isDemo =env('PESAPAL_IS_DEMO',true);//check if we are in sandbox mode
        if($isDemo)
            $api = 'https://demo.pesapal.com';
        else
            $api = 'https://www.pesapal.com'; 
            
        // $QueryPaymentStatus               =   $api.'/API/QueryPaymentStatus';
        // $QueryPaymentStatusByMerchantRef  =   $api.'/API/QueryPaymentStatusByMerchantRef';
        $querypaymentdetails              =   $api.'/API/QueryPaymentDetails';
        // dump($querypaymentdetails);

        $request_status = \OAuthRequest::from_consumer_and_token(
                                $consumer, 
                                $token, 
                                "GET", 
                                $querypaymentdetails, 
                                $params
                            );
        $request_status->set_parameter("pesapal_merchant_reference", $pesapalMerchantReference);
        $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
        $request_status->sign_request($signature_method, $consumer, $token);
    
        $responseData = $this->curlRequest($request_status);
        
        $pesapalResponse = explode(",", $responseData);
        $pesapalResponseArray=array('pesapal_transaction_tracking_id'=>$pesapalResponse[0],
                   'payment_method'=>$pesapalResponse[1],
                   'status'=>$pesapalResponse[2],
                   'pesapal_merchant_reference'=>$pesapalResponse[3]);

        // array:[
        //   "pesapal_transaction_tracking_id" => "9f225cb3-9473-4c83-a30e-2bd58ec87dac"
        //   "payment_method" => "MPESA"
        //   "status" => "PENDING"
        //   "pesapal_merchant_reference" => "5f2b268415a76"
        // ]
                   
        return $pesapalResponseArray;
    }

    public function curlRequest($request_status){        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_status);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True'){
            $proxy_tunnel_flag = (
                    defined('CURL_PROXY_TUNNEL_FLAG') 
                    && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE'
                ) ? false : true;
            curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
            curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
        }
        
        $response                   = curl_exec($ch);
        $header_size                = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $raw_header                 = substr($response, 0, $header_size - 4);
        $headerArray                = explode("\r\n\r\n", $raw_header);
        $header                     = $headerArray[count($headerArray) - 1];
        
        //transaction status
        $elements = preg_split("/=/",substr($response, $header_size));
        // $pesapal_response_data = $elements[1];
        $pesapal_response_data = $elements[1];//when offline
        
        return $pesapal_response_data;
    }

    public function buySubscription($user_id,$transaction_status,$reference){
        //check if status was successful
        if($transaction_status == 'COMPLETED'){
            //payment successful->1.award subscription
            $subscription = Subscription::where('user_id',$user_id)->first();
            $subscription->expiry_on = Date('Y-m-d h:i:s', strtotime('+31 days')) ;
            $subscription->save();

            //2.set is_used to true so that payment is not used to buy another subscription
            $transactions = MyTransactions::where('reference',$reference)->first();
            if(!is_null($transactions)){
                //found transaction->updated details
                $transactions->is_used="1";
                $transactions->save();  
            }
            //TODO ADD PAYMENT SUCCESSFUL EVENT
            event(new PaymentSuccessfulEvent(\Auth::user()));

            //check if expiry_on is less than now or greater than
            // if($subscription->expiry_on < date('Y-m-d h:i:s')){
            //     //if less than now->award 30 days subscriptions from now
            //     $subscription->expiry_on = Date('Y-m-d h:i:s', strtotime('+31 days')) ;
            //     $subscription->save();
            // }else{
            //     //if greater than, award 30 days from expiry_on 
            //     $timestamp = date_create($subscription->expiry_on);
            //     date_add($timestamp,date_interval_create_from_date_string("31 days"));
            //     $timestamp = date_format($timestamp,"Y-m-d h:i:s");
            //     $subscription->expiry_on = $timestamp ;
            //     $subscription->save();
            // }
        }else{
            //payment not successful
            //do not give user subscription
        }
    }

    public function checkPaymentStatusDashboard(){
        //check on dashboard if user's payment was successful
        $user = \Auth::user();
        // dd($user);

        $my_transaction = MyTransactions::where([
            ['user_id','=',$user['id']],
            ['is_used','=','0']
        ])->latest()->first();
        // dd($my_transaction);
        if(!is_null($my_transaction)){
            //logic here
            $status = $this->checkStatusByMerchantRef($my_transaction->reference);
            if($status == 'COMPLETED'){
                //payment is successful
                //1.update the status column 
                $my_transaction->status = $status;
                $my_transaction->save();
                //2.award subscription
                $this->buySubscription($user['id'],$status,$my_transaction->reference); 
            }
            // dd($status);
        }
        
        // http://localhost:8000
        // /user/payments/redirect?pesapal_transaction_tracking_id=23f64864-f610-4c39-b8cc-4a0417349a10&pesapal_merchant_reference=5f4e9cde85297

        // https://skytoptechnologies.com
        // /?pesapal_transaction_tracking_id=058e9adb-d351-4092-9df7-0bd776900859
        // &pesapal_merchant_reference=5f2ad92d9dc87
    }

    public function getIPN(Request $request){
        /*gets the instant payment notification from pesapal
        and updates the transactions table*/
        $pesapalTrackingId          = "";
        $pesapalNotification        = "";
        $pesapalMerchantReference   = "";
        if(isset($request['pesapal_merchant_reference']))
        $pesapalMerchantReference = $request['pesapal_merchant_reference'];
        
        if(isset($request['pesapal_transaction_tracking_id']))
            $pesapalTrackingId = $request['pesapal_transaction_tracking_id'];
            
        if(isset($request['pesapal_notification_type']))
            $pesapalNotification=$request['pesapal_notification_type'];


        $transactionDetails = $this->getTransactionDetails($pesapalMerchantReference,$pesapalTrackingId);

        //End of IPN test
                
        //Update database
        $value  = array("COMPLETED"=>"Paid","PENDING"=>"Pending","INVALID"=>"Cancelled","FAILED"=>"Cancelled");
        $status = $value[$transactionDetails['status']];
        
        $dbUpdateSuccessful = $this->updateTransactionByIPN($transactionDetails);
        $resp	= "pesapal_notification_type=$pesapalNotification".		
				  "&pesapal_transaction_tracking_id=$pesapalTrackingId".
				  "&pesapal_merchant_reference=$pesapalMerchantReference";

        ob_start();
        echo $resp;
        ob_flush();
        exit;

    }

    public function updateTransactionByIPN($transaction){

        $status                     = $transaction['status'];
        $payment_method             = $transaction['payment_method'];
        $pesapalMerchantReference   = $transaction['pesapal_merchant_reference'];
        $pesapalTrackingId          = $transaction['pesapal_transaction_tracking_id'];
        

        $transact = MyTransactions::where('reference',$pesapalMerchantReference)->first();
        $user_id = $transact['user_id'];
        $transact->status=$status;
        $transact->payment_method=$payment_method;
        $transact->tracking_id=$pesapalTrackingId;
        $transact->reference=$pesapalMerchantReference;
        $transact->is_used="1";
        $transact->save();

         

        //payment successful->1.award subscription
        $subscription = Subscription::where('user_id',\Auth::id())->first();
        $subscription->expiry_on = Date('Y-m-d h:i:s', strtotime('+31 days')) ;
        $subscription->save();

        // //2.set is_used to true so that payment is not used to buy another subscription
        // $transactions = MyTransactions::where('reference',$reference)->first();
        // if(!is_null($transactions)){
        //     //found transaction->updated details
        //     $transactions->is_used="1";
        //     $transactions->save();  
        // }
        //TODO ADD PAYMENT SUCCESSFUL EVENT
        event(new PaymentSuccessfulEvent(\Auth::user()));
        
        return true;

    }
    public function getBrowseLessons(){
        $course_ids="";
        $enrolled_courses =  EnrolledCourses::where(['user_id'=>\Auth::id()])->get(); 
        foreach ($enrolled_courses as $key => $course) {
            $course_ids .= $course->course_id .",";
               # code...
          }  
        $course_ids = explode(",", $course_ids);

        $my_classes = LiveClasses::with(['course'])
        ->whereIn('course_id',$course_ids)
        ->where(function($q) {
            $q->where('classTime', '>=', date("Y-m-d"));
              // ->orWhereNull('classTime');
        })
        ->orderBy('id','DESC')
        ->get();

        return view('students.browselessons')->with(compact('my_classes'));
    }
    public function register2(Request $request){
        if($request->isMethod('post')){
            //user submitting register request
            // dd($request->all());
            
            //check if any of the required fields is empty
            if(empty($request->name) || 
                empty($request->phone) || 
                empty($request->email) ||
                empty($request->role_id) || 
                empty($request->password) ||
                empty($request->password_confirmation)){
                //missing details on form submit by 'enter key'
                return redirect()->back()->with('flash_message_error','Please fill all details');
            }

            $duplicateUser= User::where('phone',$request->number)->first();
            $duplicateEmail = User::where('email',$request->email)->first();

            if($duplicateUser)
            {
                // $request->session()->flash('Error','duplicate_user');
                return redirect()->back()->with('flash_message_error','Phone already exists');
            }
            if($duplicateEmail)
            {
                // $request->session()->flash('Error','duplicate_email');
                return redirect()->back()->with('flash_message_error','Email already exists');
            }
            if($request->password != $request->password_confirmation)
            {
                $request->session()->flash('Error','password_not_same');
                return redirect()->back()->with('msg',trans('main.pass_confirmation_same'));
            }
            $token = str_random(15);
            //http://localhost.com/register/activate/9TT0e3YmDUV20f8
            $url = url('register/activate/'.$token);


            //id,name,email,phone,verified,password,remember_token,created_at,updated)at
            $newUser = [
                'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'verified'=>0,
                'password'=>Hash::make($request->password),//encrypt($request->password),
                'token'=>$token
            ];

            // print_r($newUser);die();
            $newUser = User::create($newUser);
            // dd($newUser);

            //assign user to selected role
            DB::table('role_user')->insert(
                [
                    "user_id" => $newUser['id'],
                    "role_id" => $request->role_id
                ]
            );

            if($request->role_id == '3'){
                //student
                $package_id = 3;
            }else{
                //teacher
                $package_id = 4;
            }

            $data=array(
                'id' =>$newUser['id'],
                'link'=>$url,
                'name' => $request->name,
                'email'=>$request->email,
                'package_id'=>$package_id
            );
            event(new NewUserRegisteredEvent($data));

            

            return redirect('/login');
        }
        return view('auth.register');
    }
    public function searchCourse()
    {
        //get notifications from request enrollment table  
        $messages = RequestEnrollment::where('student_id',\Auth::user()->id)->orderBy('read','ASC')->get();
        return view('students.search')->with('messages',$messages);
    }
    public function findCourse(Request $request)
    {
        $query = $request->course;
        $all = Course::with('teachers')->where('title', 'LIKE', '%'.$query.'%')->get();
        // dd($all);
        return view('students.results')->with('results',$all);    
    }
    public function sendRequest(Request $request, RequestEnrollment $datatable)
    {

        
        $messages = RequestEnrollment::where('student_id',\Auth::user()->id)->orderBy('read','ASC')->get();
        
        $courseID=$request->course;
       //check if the student is already enrolled to the course
       $status = EnrolledCourses::where('user_id',\Auth::user()->id)
                    ->where('course_id',$courseID)->get();
                    $exists=count($status);
                    // dd($exists);
        //check if the student has already send a request for the course enrollment
        $already_send = RequestEnrollment::where('student_id',\Auth::user()->id)
        ->where('course_id',$courseID)->get();
        $if_already_send=count($already_send);

        if($exists==1){      
            Session::flash('flash_message_error','You are already enrolled to this course');
            return view('students.search')->with('messages',$messages);
        }else if($if_already_send==1){
            //return back
            Session::flash('flash_message_error','You have already send the request for enrollment to '.$request->title.'');
            return view('students.search')->with('messages',$messages);
        }else
        {
            //send request to teacher
            $datatable->student_id=\Auth::user()->id;
            $datatable->course_id=$courseID;
            $datatable->teacher_id=$request->teacher_id;
            $datatable->status='Pending';
            $datatable->read='0';
            $datatable->save();

            Session::flash('flash_message_success','Your request for enrollment to '.$request->title.' has been send successfully');
            return view('students.search')->with('messages',$messages);
        }
    }
           
        // studentNofications
        public function updateMessage(Request $request)
        {
            //check if its already updated
            $already_read= RequestEnrollment::where('id',$request->id)->value('read');
            
            if($already_read=='0'){
                RequestEnrollment::where ('id',$request->id)
                ->update(array('read' => '1'));
                $messages = RequestEnrollment::where('student_id',\Auth::user()->id)->get();
                $details = RequestEnrollment::where('id',$request->id)->get();
                //  dd($details);
                return view('students.notifications')->with('messages',$messages)->with('details',$details);
                //  return redirect()->route('notifications', ['id' => $request->id])->with('messages',$messages);
                
            }else{

                $messages = RequestEnrollment::where('student_id',\Auth::user()->id)->orderBy('read','ASC')->get();
                $details = RequestEnrollment::where('id',$request->id)->get();
                //  dd($details);
                return view('students.notifications')->with('messages',$messages)->with('details',$details);
                //  return redirect()->route('notifications', ['id' => $request->id])->with('messages',$messages);
                
            }
        }
              
}
