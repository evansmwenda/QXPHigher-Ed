<?php

// Route::get('/index', 'HomeController@index');
// Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('/welcome', 'HomeController@qxplanding');
Route::get('/ipn', 'HomeController@getIPN');
// QXP redirects
Route::get('/','HomeController@landing')->name('home-user');
// Route::get('/sms','HomeController@sms');

//we have been redirected from QXP->initiate receipt
// Route::group(['middleware' => ['guest']], function () {
//     Route::post('/redirect', 'HomeController@getRedirect');
// });

Route::group(['middleware' => 'auth'], function () {
    // Route::get('/', 'HomeController@landing');

    //edit account
    Route::match(['get', 'post'],'/account','HomeController@account');

    //browse-lessons
    Route::get('/browse-lessons', 'HomeController@getBrowseLessons');

    //courses
    Route::get('/courses', 'HomeController@index');

    //award free-trial
    Route::get('/freetrial','HomeController@awardFreeTrialAll');

    //subscriptions and payments
    Route::get('/subscribe', 'HomeController@getSubscription');
    Route::get('/subscribe/{id}', 'HomeController@startSubscription');
    Route::get('/payments/redirect', 'HomeController@getCallback');

    

    //faq
    Route::get('/faq', 'HomeController@getFAQ');

    //live classes
    Route::get('/live-classes', 'HomeController@getLiveClass');
    Route::post('live-classes/join','HomeController@joinClassByID');
    Route::get('live-classes/live/{meetingID}','HomeController@joinLiveClass');

    //assignments
    
    Route::get('/assignments', 'HomeController@getAssignments');
    Route::get('/quizzes', 'HomeController@allquizzes');
    Route::match(['get', 'post'],'/assignments/attempt/{id}', 'HomeController@displayAssignment');

    //exams
    Route::get('/exams', 'HomeController@getExams');
    Route::match(['get', 'post'],'/exams/save/{id}', 'HomeController@postExams');
    Route::get('/certification','HomeController@certificates');

    //billing
    Route::get('/billing', 'HomeController@cale');

    //calendar(s)
    Route::get('/calender', 'HomeController@getCalender');
    Route::get('/calender2', 'HomeController@getCalenderOld');

    //enroll to a free course
    Route::get('enroll/{id}', 'HomeController@enrollCourse');

    //tregister a teacher automatically from QXP
    // Route::post('tregister', 'Auth\RegisterController@register')->name('auth.tregister');;
    Route::get('/search','HomeController@searchCourse');
    Route::post('/student_search_course','HomeController@findCourse');
    Route::post('/student_sendrequest','HomeController@sendRequest');

    //Student Notofication
    Route::post('/notifications/{id}','HomeController@notifications');
    Route::post('/update_message/{id}','HomeController@updateMessage');

    });
    

Route::get('course/{slug}', ['uses' => 'CoursesController@show', 'as' => 'courses.show']);
Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('course/{course_id}/rating', ['uses' => 'CoursesController@rating', 'as' => 'courses.rating']);

Route::get('lesson/{course_id}/{slug}', ['uses' => 'LessonsController@show', 'as' => 'lessons.show']);
Route::get('attempt/{course_id}/{slug}', ['uses' => 'LessonsController@attempt', 'as' => 'lessons.attempt']);
Route::post('lesson/{slug}/test', ['uses' => 'LessonsController@test', 'as' => 'lessons.test']);

// Create Task route
Route::post('task','HomeController@createTask')->name('task');
// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');

$this->post('mylogin', 'Auth\LoginController@login')->name('auth.mylogin');
$this->post('login', 'Auth\LoginController@login')->name('auth.login');
$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('auth.register');
$this->post('myregister', 'Auth\RegisterController@register')->name('auth.myregister');
$this->post('tregister', 'Auth\RegisterController@tRegister')->name('auth.tregister');;
$this->post('register', 'Auth\RegisterController@register')->name('auth.register');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'Admin\DashboardController@index')->name('homeadmin');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::post('permissions_mass_destroy', ['uses' => 'Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    
    //courses
    Route::resource('courses', 'Admin\CoursesController');
    Route::post('courses_mass_destroy', ['uses' => 'Admin\CoursesController@massDestroy', 'as' => 'courses.mass_destroy']);
    Route::post('courses_restore/{id}', ['uses' => 'Admin\CoursesController@restore', 'as' => 'courses.restore']);
    Route::delete('courses_perma_del/{id}', ['uses' => 'Admin\CoursesController@perma_del', 'as' => 'courses.perma_del']);
    
    //lessons
    Route::resource('lessons', 'Admin\LessonsController');
    Route::post('lessons_mass_destroy', ['uses' => 'Admin\LessonsController@massDestroy', 'as' => 'lessons.mass_destroy']);
    Route::post('lessons_restore/{id}', ['uses' => 'Admin\LessonsController@restore', 'as' => 'lessons.restore']);
    Route::delete('lessons_perma_del/{id}', ['uses' => 'Admin\LessonsController@perma_del', 'as' => 'lessons.perma_del']);
    
    Route::resource('questions', 'Admin\QuestionsController');
    Route::post('questions_mass_destroy', ['uses' => 'Admin\QuestionsController@massDestroy', 'as' => 'questions.mass_destroy']);
    Route::post('questions_restore/{id}', ['uses' => 'Admin\QuestionsController@restore', 'as' => 'questions.restore']);
    Route::delete('questions_perma_del/{id}', ['uses' => 'Admin\QuestionsController@perma_del', 'as' => 'questions.perma_del']);
    Route::resource('questions_options', 'Admin\QuestionsOptionsController');
    Route::post('questions_options_mass_destroy', ['uses' => 'Admin\QuestionsOptionsController@massDestroy', 'as' => 'questions_options.mass_destroy']);
    Route::post('questions_options_restore/{id}', ['uses' => 'Admin\QuestionsOptionsController@restore', 'as' => 'questions_options.restore']);
    Route::delete('questions_options_perma_del/{id}', ['uses' => 'Admin\QuestionsOptionsController@perma_del', 'as' => 'questions_options.perma_del']);
    
    //for  events
    Route::get('events','Admin\DashboardController@getEvents');
    Route::match(['get', 'post'],'events/create','Admin\DashboardController@createEvents');
    Route::match(['get', 'post'],'events/update/{id}','Admin\DashboardController@updateEvent');
    Route::match(['get', 'post'],'events/create2','Admin\DashboardController@createEvents2');
    Route::match(['get', 'post'],'events/delete/{id}','Admin\DashboardController@deleteEvents');

    //for assignments
    Route::get('assignments','Admin\DashboardController@getAssignments');
    Route::match(['get', 'post'],'assignments/create','Admin\DashboardController@createAssignments');
    Route::match(['get', 'post'],'assignments/update/{id}','Admin\DashboardController@updateAssignment');

    //tests/quizzes
    // Route::resource('tests', 'Admin\TestsController');
    Route::match(['get', 'post'],'tests','Admin\TestsController@index')->name('tests.index');
    Route::get('tests/create/new','Admin\TestsController@create');
    Route::match(['get', 'post'],'tests/store','Admin\TestsController@store')->name('tests.store');
    Route::get('tests/attempts/{id}','Admin\TestsController@attemptedQuizzes');
    Route::match(['get', 'post'],'tests/attempts/{test_id}/{student_id}','Admin\TestsController@attemptedQuizzesByStudent');
    Route::get('tests/{id}','Admin\TestsController@getTestDetails');
    Route::get('tests/delete-question/{id}','Admin\TestsController@deleteExamQuestion');
    Route::post('tests_mass_destroy', ['uses' => 'Admin\TestsController@massDestroy', 'as' => 'tests.mass_destroy']);
    Route::post('tests_restore/{id}', ['uses' => 'Admin\TestsController@restore', 'as' => 'tests.restore']);
    Route::delete('tests_perma_del/{id}', ['uses' => 'Admin\TestsController@perma_del', 'as' => 'tests.perma_del']);
    Route::post('/spatie/media/upload', 'Admin\SpatieMediaController@create')->name('media.upload');
    Route::post('/spatie/media/remove', 'Admin\SpatieMediaController@destroy')->name('media.remove');

    //for exams 
    Route::match(['get', 'post'],'exams','Admin\DashboardController@getExams');
    Route::get('exams/{id}','Admin\DashboardController@getExamsDetails');
    Route::match(['get', 'post'],'exams/delete-question/{id}','Admin\DashboardController@deleteExamQuestion');
    Route::get('exams/create','Admin\DashboardController@createExams');
    Route::get('exams/create/new','Admin\DashboardController@createExams2');
    Route::match(['get', 'post'],'exams/save','Admin\DashboardController@storeExams');
    Route::get('exams/attempts/{id}','Admin\DashboardController@attemptedExams');
    Route::match(['get', 'post'],'exams/attempts/{test_id}/{student_id}','Admin\DashboardController@attemptedExamsByStudent');
    Route::match(['get', 'post'],'exams/delete/{id}','Admin\DashboardController@deleteExams');
    Route::post('exams/grade/save','Admin\DashboardController@postStudentGrade');

    //for live classes
    Route::get('live-classes','Admin\DashboardController@liveClasses');
    Route::match(['get', 'post'],'live-classes/schedule','Admin\DashboardController@scheduleLiveClass');
    Route::match(['get', 'post'],'live-classes/create','Admin\DashboardController@createLiveClass');
    Route::get('live-classes/delete/{id}','Admin\DashboardController@deleteLiveClass');
    Route::get('live-classes/start/{id}','Admin\DashboardController@createJoinLive');
    // Route::match(['get', 'post'],'events/create2','Admin\DashboardController@createEvents2');
    // Route::post('live-classes/create','Admin\DashboardController@scheduleLiveClass2');
    Route::post('live-classes/join','Admin\DashboardController@joinClassByID');
    Route::get('live-classes/live/{meetingID}','Admin\DashboardController@joinLiveClass');

    //for students
    Route::get('students','Admin\DashboardController@students');
    Route::match(['get','post'],'students/enroll','Admin\DashboardController@enroll');
    Route::get('autocomplete','Admin\DashboardController@autocomplete')->name('autocomplete');
    Route::get('students/list/{id}','Admin\DashboardController@studentlist');
    Route::get('students/list/{course_id}/remove/{id}','Admin\DashboardController@studentlistRemove');
    Route::get('studentrequests','Admin\DashboardController@requests');
    Route::get('request_details','Admin\DashboardController@requestDetails');
    

});
Route::match(['get','post'],'/register2', 'HomeController@register2')->name('register2');
Route::match(['get','post'],'/verify2', 'HomeController@verify')->name('verify2');
Route::post('/register/activate', 'HomeController@sendActivate')->name('send_activate');
Route::get('/register/activate/{id}', 'HomeController@accountActivate')->name('account_activate');