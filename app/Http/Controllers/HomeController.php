<?php

namespace App\Http\Controllers;

use App\Course;
use App\EnrolledCourses;
use App\Events;
use App\Test;
use App\TestsResult;
use DB;
use App\Lesson;
use DateTime;
use DateInterval;
use DatePeriod;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
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

    public function evans(){
        $_POST['email'] = 'evansmwenda.em@gmail.com';
        $_POST['password'] = 'password';

        return redirect()->route('/', [$_POST]);

       // dd($_POST);
    }

    public function landing(){
        //landing page for the user's dashboard

        //get the user's quizzes

        // $test_results = TestsResult::where(['user_id'=> \Auth::id() ])->get();
        $test_results = DB::table('tests_results')->where(['user_id'=> \Auth::id() ])->distinct('test_id')->get();
        $tests_ids="";
        $my_results="";
        $result_array =[];
        foreach ($test_results as $test ) {
            $tests_ids .= $test->test_id.",";

            // $GET = [];
            // $GET += ['one' => 1];
            $result_array += [
                $test->test_id => $test->test_result,
            ];

            // if (strpos($my_results, $test->test_result) !== false) {
            //     //
            // }else{
            //     $my_results .= $test->test_result.",";//test_result
            // }

        }
        // dd($result_array[51]);

        // $my_array=array();
        $my_test_ids = explode(",",$tests_ids);//convert to array
        $test_details = DB::table('tests')
              ->whereIn('id', $my_test_ids)
              ->get();
        // dd($data);
        // dd($my_test_ids);
        // foreach($my_test_ids as $my_test){
        //     $test_details  .= Test::where(['id'=> $test->test_id ])->get();
        //     // dd($test_details);
        // }
        // dd($test_details);
        // die();
        // $categories = DB::table('tests_results')->where(['user_id'=> \Auth::id() ])->distinct('test_id')->get();
        //dd($test_details);
        return view('students.home_user')->with(compact('test_details','result_array'));
    }
    public function getCalender(){
        $month = date('m');
        $monthly = DB::table('events')
                    ->whereMonth('event_start_time', $month)->get();//has events data for the current month
        

        $eventDates_array = "";
        foreach($monthly as $event){
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
        $daterange = new \DatePeriod($begin, $interval ,$end);

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
        // dd($eventDates_array);
        foreach($daterange as $date){
            if(in_array($date->format("d"), $eventDates_array)){
                //get the key of the date value
                $key = array_search($date->format("d"), $eventDates_array);
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
        return view('students.calender')->with(compact('month_year','month_dates'));
    }
    public function getExams(){
        return view('students.exams');
    }
    public function enrollCourse($course_id){
        //enroll to this course
        //get user id
        // $course_details = Course::where(['published'=> 1,'id'=>$course_id])->get();
        // dd($course_details[0]);
        if (\Auth::check()) {
            $course_details = Course::where(['published'=> 1,'id'=>$course_id])->get();
            $total_lessons = Lesson::where(['course_id'=> $course_details[0]->id])->get();
            $total_lessons = $total_lessons->count();

            $newEnrolledCourse = [
                'course_id' => $course_details[0]->id,
                'lesson_id' => "1",
                'user_id' => \Auth::id(),
                'total_lessons' => $total_lessons
            ];

            $newEnrolledCourse = EnrolledCourses::updateOrCreate($newEnrolledCourse);
            if($newEnrolledCourse){
                //course enrolled
                return redirect()->back()->with('flash_message_success','You have enrolled to course successfully');
            }
        }
        

    }
}
