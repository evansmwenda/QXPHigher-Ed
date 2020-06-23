<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events;

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

    public function getEvents(Request $request){

        if($request->isMethod('post')){
            $data=$request->all();

            $event_start_end = $data['event_start_end'];
            
            $event_start_end = explode(" - ", $event_start_end);
             // 0 => "2020-06-23 00:00:00"
             // 1 => "2020-06-23 23:59:59"
            // dd($event_start_end);

            

            $my_event = new Events;
            $my_event->title=$data['event_title'];
            $my_event->event_start_time=$event_start_end[0];
            $my_event->event_end_time=$event_start_end[1];

            // dd($my_event);
            $my_event->save();
            return back()->with('flash_message_success','Event created successfully ');
        }
         //get
        return view('admin.events.create');
    }
}
