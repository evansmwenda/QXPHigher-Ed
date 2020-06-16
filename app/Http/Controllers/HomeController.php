<?php

namespace App\Http\Controllers;

use App\Course;
use App\EnrolledCourses;
use DB;
use App\Lesson;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        return view('students.home_user');
    }
    public function getCalender(){
        //get the month and the year
        $month_year = date("F Y", time());

        //match the dates to days
        return view('students.calender')->with(compact('month_year'));
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
