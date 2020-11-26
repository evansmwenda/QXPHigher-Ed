<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
// use Request;
use App\Http\Controllers\Controller;
use App\Events;
use App\CourseUser;
use App\Course;
use App\Assignments;
use App\RequestEnrollment;
use App\Test;
use App\Lesson;
use App\Question;
use App\QuestionsOption;
use App\SubmittedAssignments;
use App\ExamSubmits;
use App\ExamAnswers;
use App\EnrolledCourses;
use App\User;
use App\QuestionTest;
use App\TestsResult;
use App\LiveClasses;
use App\LiveClassRecordings;
use App\Transaction as MyTransactions;
use App\Subscription;
use App\Package;
use App\Media;
use DB;
use Session;
// use GuzzleHttp\Client;
// use GuzzleHttp\Ring\Exception\ConnectException;

use App\Events\PaymentSuccessfulEvent;
use App\Mail\MeetingEmail;
use App\Mail\RegisterMail;
use App\library\OAuth;

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
        ->paginate(6);
        // dd($courses[0]);

        $highlights = $this->getSummaryCount();

        //check for unused payments
        $this->checkPaymentStatusDashboard();

        //get number of resources
        // $resources = $this->getResourcesList($course_ids);


        //fetch student enrollment requests
        $request_enrollments = $this->getRequestEnrollments();
        // dd($request_enrollments);

        return view('home')->with(compact(
            'courses',
            'highlights',
            'request_enrollments'
        ));
        
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
    public function getRequestEnrollments(){
        $request_enrollments =DB::table('request_enrollments')
        ->select('request_enrollments.id',
        'request_enrollments.status',
        'courses.title',
        'users.name',
        'users.email')
        ->where('teacher_id',\Auth::id())
        ->where('status','Pending')
        ->join('users','users.id','=','request_enrollments.student_id')
        ->join('courses','courses.id','=','request_enrollments.course_id')
        ->orderBy('id','DESC')->get();

        return $request_enrollments;
    }
    public function students(){
        //user verified->check if have active subscription
        $active=$this->checkMySubscriptionStatus();

        // enroll-details
        $request_enrollments = $this->getRequestEnrollments();

        //highlights
        $highlights = $this->getSummaryCount();

        $count_arr = array();
        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        foreach($my_courses as $course){
            //get count of students enrolled to that course
            $students = EnrolledCourses::where('course_id',$course->course->id)->get();
            $count = $students->count();
            array_push($count_arr,$count);
        }

        return view('admin.students.index')
        ->with(compact('my_courses',
        'count_arr',
        'request_enrollments',
        'highlights',
        'active'
        ));
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
    public function requestDetails(Request $request){
        $student_request =DB::table('request_enrollments')
        ->select('request_enrollments.id','request_enrollments.status','courses.title','users.name','users.email','users.phone','request_enrollments.student_id','request_enrollments.course_id')
        ->where('request_enrollments.id',$request->request_id)
        ->join('users','users.id','=','request_enrollments.student_id')
        ->join('courses','courses.id','=','request_enrollments.course_id')->get();
        // dump( $student_request);

        //highlights
        $highlights = $this->getSummaryCount();

        //request of enrollment of other students
        $request_enrollments = $this->getRequestEnrollments();

        return view('admin.students.request_details')
        ->with(compact('request_enrollments',
        'student_request',
        'highlights'));
    }
    public function acceptRequest(Request $request){
        // enroll-details
        $request_enrollments = $this->getRequestEnrollments();

        //highlights
        $highlights = $this->getSummaryCount();
        if($request->isMethod('post')){
            // dd($request->all());
                //we have found a user
                $user_id = $request->user_id;
                $total_lessons = Lesson::where(['course_id'=> $request->course_id])->get();
                // dd($total_lessons);
                $newEnrolledCourse = [
                    'course_id' => $request->course_id,
                    'lesson_id' => empty($total_lessons[0]->id) ? '0':$total_lessons[0]->id,
                    'user_id' => $user_id,
                    'total_lessons' => $total_lessons->count()
                ];
                // dd($newEnrolledCourse);

                $newEnrolledCourse = EnrolledCourses::updateOrCreate($newEnrolledCourse);
                
                if($newEnrolledCourse){
                    //update the enrollment table status
                    RequestEnrollment::where ('id',$request->enroll_id)
                   ->update(array('status' => 'Accepted','read'=>'0'));
                    Session::flash('flash_message_success','You have successfully accepted the user request and has been enrolled to the course successfully');
                    return view('admin.students.requests')->with(compact('request_enrollments','highlights'));
                  }else{
                    Session::flash('flash_message_error','An error occurred, please try again later');
                    return view('admin.students.requests')->with(compact('request_enrollments','highlights'));
                }
            
        }
        return view('admin.students.requests')->with(compact('request_enrollments','highlights'));
    }
    public function rejectRequest(Request $request)
    {
        // enroll-details
        $request_enrollments = $this->getRequestEnrollments();

        //highlights
        $highlights = $this->getSummaryCount();

        //update the enrollment table status
        RequestEnrollment::where ('id',$request->enroll_id)
        ->update(array('status' => 'Rejected','read'=>'0'));
        Session::flash('flash_message_success','You have successfully rejected the user request');
        return view('admin.students.requests')->with(compact('request_enrollments','highlights'));
    }
    public function requests(){
        // enroll-details
        $request_enrollments = $this->getRequestEnrollments();

        //highlights
        $highlights = $this->getSummaryCount();
        return view('admin.students.requests')->with(compact('request_enrollments','highlights'));
    }

    public function enroll(Request $request){
        //get my courses ids
        $course_ids = $this->fetchEnrolledCourseIDs();
        //fetch latest student enrollments in your course ids
        $enrollments = DB::table('enrolled_courses')
        ->select('enrolled_courses.id as id','enrolled_courses.course_id as course_id','courses.title as course_title',
        'users.name as user_name','users.email as user_email')
        ->join('courses', 'courses.id', '=', 'enrolled_courses.course_id')
        ->join('users', 'users.id', '=', 'enrolled_courses.user_id')
        ->whereIn('course_id',$course_ids)
        ->orderBy('enrolled_courses.id','DESC')
        ->get();

        // dd($enrollments);
        // enroll-details
        $request_enrollments = $this->getRequestEnrollments();

        //highlights
        $highlights = $this->getSummaryCount();
        
        //get my courses

        $my_courses = CourseUser::with(['course'])->where(['user_id'=> \Auth::id()])->get();
        // dd(Request::method() =='GET');
        if($request->isMethod('post')){
        // if(Request::method() == 'POST'){
            // dd($request->all());
            //get user details from name
            $user = User::where('name', $request->search)->first();
            // dd($user);
            if($user){
                //we have found a user
                $user_id = $user->id;
                $total_lessons = Lesson::where(['course_id'=> $request->course_id])->get();
                // dd($total_lessons);
                $newEnrolledCourse = [
                    'course_id' => $request->course_id,
                    'lesson_id' => empty($total_lessons[0]->id) ? '0':$total_lessons[0]->id,
                    'user_id' => $user_id,
                    'total_lessons' => $total_lessons->count()
                ];
                // dd($newEnrolledCourse);

                $newEnrolledCourse = EnrolledCourses::updateOrCreate($newEnrolledCourse);
                if($newEnrolledCourse){
                    //course enrolled
                    return redirect()->back()->with('flash_message_success','User enrolled to course successfully');
                }
            }else{
                return redirect()->back()->with('flash_message_error', "An error occurred, please try again");
            }
            
        }
        // dd($my_courses);
        return view('admin.students.enroll')
        ->with(compact('my_courses',
        'enrollments',
        'highlights',
        'request_enrollments'));
    }

    public function autocomplete(Request $request){
        //   $search = $request->get('term');
          $result = User::where('name', 'LIKE', '%'. $request->terms. '%')->get();
        //   $response = array();
        //   foreach($result as $user){
        //      $response[] = array("value"=>$user->id,"label"=>$user->name);
        //   }
          return response()->json($result);    
    }

    public function studentlist($id=null){
        // enroll-details
        $request_enrollments = $this->getRequestEnrollments();

        //highlights
        $highlights = $this->getSummaryCount();

        $course = Course::find($id);
        $course_ids =$this->fetchEnrolledCourseIDs();
        $enrollments =(object) array();
        if(in_array($id,$course_ids)){
            //user owns the course
            $enrollments = DB::table('enrolled_courses')
            ->select('enrolled_courses.id as id','enrolled_courses.course_id as course_id','courses.title as course_title',
            'users.name as user_name','users.email as user_email')
            ->join('courses', 'courses.id', '=', 'enrolled_courses.course_id')
            ->join('users', 'users.id', '=', 'enrolled_courses.user_id')
            ->where('course_id',$id)
            ->orderBy('enrolled_courses.id','DESC')
            ->get();
        }else{
            return redirect()->back()->with('flash_message_error', "An error occurred, please try again");
        }
        // dd($course);
        return view('admin.students.list')
        ->with(compact('enrollments',
        'course',
        'highlights',
        'request_enrollments'));
    }
    public function studentlistRemove($course_id=null,$id=null){
        $course_ids =$this->fetchEnrolledCourseIDs();
        $enrollments =(object) array();
        if(in_array($course_id,$course_ids)){
            $course = EnrolledCourses::where('id', $id)->delete();
            return redirect()->back()->with('flash_message_error', "User removed from list");
        }else{
            return redirect()->back()->with('flash_message_error', "An error occurred, please try again");
        }
        
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

        //user verified->check if have active subscription
        $active=$this->checkMySubscriptionStatus();

        // dd($meeting);
        $user = \Auth::user();

        //get the secure salt
        $salt = env("BBB_SALT", "0");
        //get BBB server
        $bbb_server = env("BBB_SERVER", "0");
        $logout_url = env("BBB_LOGOUT_URL", "http://higher-ed.qxp-global.com/");

        //check active subscription and set time for meeting
        if($active){
            //no timeout set
            $create_string="name=$title&meetingID=$meetingID&record=true&attendeePW=$attendeePW&moderatorPW=$moderatorPW&logoutURL=$logout_url";
        }else{
            //timer set to 45 mins
            $timer = 45;
            $create_string="name=$title&meetingID=$meetingID&record=true&attendeePW=$attendeePW&moderatorPW=$moderatorPW&duration=$timer&logoutURL=$logout_url";
        }

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
            //user verified->check if have active subscription
            $active=$this->checkMySubscriptionStatus();

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
            $classTime=$request->classTime;
            $attendeePW=str_random(6);//"ap";//$request->attendeePW;
            $moderatorPW=str_random(6);//"mp";//$request->moderatorPW;
            $duration='30';

            //get the secure salt
            $salt = env("BBB_SALT", "0");
            //get BBB server
            $bbb_server = env("BBB_SERVER", "0");
            $logout_url = env("BBB_LOGOUT_URL", "http://higher-ed.qxp-global.com/");

            //check active subscription and set time for meeting
            if($active){
                //no timeout set
                $create_string="name=$title&meetingID=$meetingID&record=true&attendeePW=$attendeePW&moderatorPW=$moderatorPW&logoutURL=$logout_url";
            }else{
                //timer set to 45 mins
                $timer = 45;
                $create_string="name=$title&meetingID=$meetingID&record=true&attendeePW=$attendeePW&moderatorPW=$moderatorPW&duration=$timer&logoutURL=$logout_url";
            }
            
            $newCreateString="create".$create_string;

            $checksumCreate=sha1($newCreateString.$salt);


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
                // dd($newLiveClass);


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

    public function getSubscription(){
        $subscription = Subscription::with('package')->where('user_id',\Auth::id())->firstOrFail();
        // dd($subscription);
        // Date('Y-m-d h:i:s', strtotime('+14 days')),       
        $date_now = date("Y-m-d  h:i:s"); // this format is string comparable
        $expiry_on =$subscription->expiry_on;
        if($expiry_on > $date_now){
            $active = true;//subscription is active
        }else{
            $active = false;//expired or is on free trial
        }
        
        return view('admin.payments.subscribe')->with(compact('subscription', 'active','expiry_on'));
    }
    public function startSubscription($id=null){
        $user = \Auth::user();

        $packages = Package::where('id',$id)->get();
        // dump($packages);
        
        if($packages->isEmpty()){
            return back()->with('flash_message_error','no packages');
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
        $callback_url   = url("admin/redirect");//URL user to be redirected to after payment
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


        return view('admin.payments.subscribe')->with(compact(
             'iframe_src',
             'amount',
             'package_name',
             'subscription', 
             'active',
             'expiry_on'
            ));
    }
    public function getCallback(Request $request){
        $user= \Auth::user();
        // $status='UNKNOWN';
        // dd($request->all());
        $tracking_id = $request['pesapal_transaction_tracking_id'];
        $reference = $request['pesapal_merchant_reference'];

        /** check status of the transaction made
          *There are 3 available API
          *checkStatusUsingTrackingIdandMerchantRef() - returns Status only. 
          *checkStatusByMerchantRef() - returns status only.
          *getMoreDetails() - returns status, payment method, merchant reference and pesapal tracking id
        **/
        
        // $statusArray           = $this->checkStatusByMerchantRef($reference);
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
        return view('admin.payments.redirect')->with(compact('status','reference','tracking_id'));
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
        // dd($responseData);

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
        $pesapal_response_data = $elements[1];
        
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
}
