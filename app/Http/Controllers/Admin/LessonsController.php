<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonsRequest;
use App\Http\Requests\Admin\UpdateLessonsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;

class LessonsController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Gate::allows('lesson_access')) {
            return abort(401);
        }

        $lessons = Lesson::with('course')->whereIn('course_id', Course::ofTeacher()->pluck('id'));
        if ($request->input('course_id')) {
            $lessons = $lessons->where('course_id', $request->input('course_id'));
        }
        
        if (request('show_deleted') == 1) {
            if (! Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $lessons = $lessons->onlyTrashed()->ORDERBY('id','DESC')->get();
        } else {
            $lessons = $lessons->ORDERBY('id','DESC')->get();
        }
        // dd($lessons);
        $my_summary_count= app('App\Http\Controllers\Admin\DashboardController')->getSummaryCount();
// dd($lessons);
        return view('admin.lessons.index', compact('lessons','my_summary_count'));
    }

    /**
     * Show the form for creating new Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('lesson_create')) {
            return abort(401);
        }
        $courses = \App\Course::ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');

        return view('admin.lessons.create', compact('courses'));
    }

    /**
     * Store a newly created Lesson in storage.
     *
     * @param  \App\Http\Requests\StoreLessonsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonsRequest $request)
    {
        if (! Gate::allows('lesson_create')) {
            return abort(401);
        }
        
        $request = $this->saveFiles($request);
        // dump($request->course_id);
        // dump($request->all());

        $newLesson = [
            'course_id' => $request['course_id'] ,
            'title' => $request['title'],
            'slug' => $request['slug'],
            'lesson_image' => NULL,
            'short_text' => $request['short_text'],
            'full_text' => $request['full_text'],
            'position' => Lesson::where('course_id', $request->course_id)->max('position') + 1,
            'free_lesson' => $request['free_lesson'],
            'published' =>$request['published'] 
        ];
        // dump($newLesson);
        // dump($request['downloadable_files']);

        
        // dump("end");
        $lesson = Lesson::create($newLesson);

        if(!empty($request['downloadable_files'])){
            //teacher has uploaded media for the lesson
            dump("not empty");
            $name  = explode(".",$request['downloadable_files']);

            $media = new \App\Media();
            $media->model_id = $lesson->id;
            $media->model_type= 'App\Lesson';
            $media->collection_name= 'downloadable_files';
            $media->name=$name[0];
            $media->file_name=$request['downloadable_files'];
            $media->disk='media';
            $media->size=1;
            $media->manipulations='';
            $media->custom_properties='';
            $media->order_column=0;
            $media->save();
            // dump($media);
        }
        


        
        // $lesson = Lesson::create($request->all()
        //     + ['position' => Lesson::where('course_id', $request->course_id)->max('position') + 1]);

        //     dump($lesson);
        // foreach ($request->input('downloadable_files_id', []) as $index => $id) {
        //     $model          = config('laravel-medialibrary.media_model');
        //     $file           = $model::find($id);
        //     $file->model_id = $lesson->id;
        //     $file->save();
        // }

        return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id]);
    }


    /**
     * Show the form for editing Lesson.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('lesson_edit')) {
            return abort(401);
        }
        $courses = \App\Course::ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');

        $lesson = Lesson::findOrFail($id);

        return view('admin.lessons.edit', compact('lesson', 'courses'));
    }

    /**
     * Update Lesson in storage.
     *
     * @param  \App\Http\Requests\UpdateLessonsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLessonsRequest $request, $id)
    {
        if (! Gate::allows('lesson_edit')) {
            return abort(401);
        }
        $request = $this->saveFiles($request);
        $lesson = Lesson::findOrFail($id);
        $lesson->update($request->all());


        $media = [];
        foreach ($request->input('downloadable_files_id', []) as $index => $id) {
            $model          = config('laravel-medialibrary.media_model');
            $file           = $model::find($id);
            $file->model_id = $lesson->id;
            $file->save();
            $media[] = $file;
        }
        $lesson->updateMedia($media, 'downloadable_files');

        return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id]);
    }


    /**
     * Display Lesson.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = \App\Course::get()->pluck('title', 'id')->prepend('Please select', '');$tests = \App\Test::where('lesson_id', $id)->get();

        $lesson = Lesson::findOrFail($id);

        return view('admin.lessons.show', compact('lesson', 'tests'));
    }


    /**
     * Remove Lesson from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('admin.lessons.index');
    }

    /**
     * Delete all selected Lesson at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('lesson_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Lesson::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Lesson from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->restore();

        return redirect()->route('admin.lessons.index');
    }

    /**
     * Permanently delete Lesson from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->forceDelete();

        return redirect()->route('admin.lessons.index');
    }
}
