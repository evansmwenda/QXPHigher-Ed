<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events;
use App\CourseUser;
use App\Course;
use App\Assignments;
use App\SubmittedAssignments;
use DB;

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
        return view('home');
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


        return view('admin.exams.index')->with(compact('books','my_events'));
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


        $jsonData= '[
{"name":"Bill Smith", "city":"Fort Lauderdale", "state":"Florida"}, 
{"name":"Vanessa Halls", "city":"New York City", "state":"New York"},
{"name":"Ryan Mitchells", "city":"Miami", "state":"Florida"}
]';

$people= json_decode($jsonData, true);





        // $v1 = stripslashes(file_get_contents("php://input"));
        // $v = stripslashes(file_get_contents("php://input"));
        // build a PHP variable from JSON sent using GET method
        // $v = json_decode(stripslashes($_GET["data"]));
        // encode the PHP variable to JSON and send it back on client-side

        $myfile = fopen("testfile.txt", "w") or die("Unable to open file!");
        //     //2.create reply and return back

        foreach ($v as $key => $question) {
            # code...
            // $evans =json_encode($question);
            //for questions -> $question["question"]["value"]
            //for options -> $question["options"][0]["value"]
            //for questions -> $question["options"][0]["name"]
            fwrite($myfile,"\n".$question["question"]["value"]."\n");
          

            foreach ($question["options"]  as $key => $question_details) {
                # code...
                $question_options = $question_details["value"];
                fwrite($myfile,"\n".$question_options."\n\n\n");
            }


            
        }
        // for($i=0; $i<count($v); $i++) {//$obj['reviews']
        //     // echo "Rating is " . $obj['reviews'][$i]["rating"] . " and the excerpt is " . $obj['reviews'][$i]["excerpt"] . "<BR>";

        //     // $current_question = "question-".$i+1;
        //     $evans =json_encode($v[$i]);
        //     // $evans = json_decode( $evans,TRUE);
        //     // $evans_q = $evans->options;
        //     // $evans= json_encode($evans);
        //     fwrite($myfile,"\n".$evans."\n\n");
        // }

        // [{"question-1":{"name":"question-1","value":"which of the following is not a mammal?"},"options":[{"name":"1","value":"study of plansts"},{"name":"2","value":"Duck billed dophus"}],"total":3,"course_id":"6","exam_title":"my name"},{"question-2":{"name":"question-2","value":"Who was the second president of Kenya"},"options":[{"name":"1","value":"Spiny ant eater"},{"name":"2","value":"Uhuru Kenyatta"}],"total":3,"course_id":"6","exam_title":"my name"},{"question-3":{"name":"question-3","value":"Are you yourself lately?"},"options":[],"total":3,"course_id":"6","exam_title":"my name"}]
// [
//    {
//       "question":{
//          "name":"question-1",
//          "value":"which of the following is not a mammal?"
//       },
//       "options":[
//          {
//             "name":"1",
//             "value":"study of plansts"
//          },
//          {
//             "name":"2",
//             "value":"Duck billed dophus"
//          }
//       ],
//       "total":3,
//       "course_id":"6",
//       "exam_title":"my name"
//    },
//    {
//       "question-3":{
//          "name":"question-3",
//          "value":"Are you yourself lately?"
//       },
//       "options":[

//       ],
//       "total":3,
//       "course_id":"6",
//       "exam_title":"my name"
//    }
// ]







        
        $txt = "John mwenda count->> ".count($v)."\n";
        fwrite($myfile, $txt);
        $evans= json_encode($v);
        fwrite($myfile, $evans);
        $txt = "\n".date("Y-m-d H:i:s")."\n========================================================\n";
        fwrite($myfile, $txt);
        fclose($myfile);
            



            $arr = array('success' => true, 
                'status' => "Successfulle", 
                'sent' => $evans,
                'd' => 4, 
                'e' => 5);

         echo json_encode($arr);
    }
}
