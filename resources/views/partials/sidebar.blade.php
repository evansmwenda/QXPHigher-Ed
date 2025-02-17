 @inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/admin/home') }}">
                    <i class="fa fa-wrench"></i>
                    <span class="title">@lang('global.app_dashboard')</span>
                </a>
            </li>

            
            @can('user_management_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">@lang('global.user-management.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                
                @can('permission_access')
                <li class="{{ $request->segment(2) == 'permissions' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.permissions.title')
                            </span>
                        </a>
                    </li>
                @endcan
                @can('role_access')
                <li class="{{ $request->segment(2) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.roles.title')
                            </span>
                        </a>
                    </li>
                @endcan
                @can('user_access')
                <li class="{{ $request->segment(2) == 'users' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">
                                @lang('global.users.title')
                            </span>
                        </a>
                    </li>
                @endcan
                </ul>
            </li>
            @endcan
            @can('question_access')
            <li class="{{ $request->segment(2) == 'students' ? 'active' : '' }}">
                <a href="{{ url('/admin/students') }}">
                    <i class="fa fa-user"></i>
                    <span class="title">Enroll Students</span>
                </a>
            </li>
            @endcan
            @can('course_access')
            <li class="{{ $request->segment(2) == 'courses' ? 'active' : '' }}">
                <a href="{{ route('admin.courses.index') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">@lang('global.courses.title')</span>
                </a>
            </li>
            @endcan
            
            @can('lesson_access')
            <li class="{{ $request->segment(2) == 'lessons' ? 'active' : '' }}">
                <a href="{{ route('admin.lessons.index') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">@lang('global.lessons.title')</span>
                </a>
            </li>
            @endcan
            
            {{-- @can('question_access')
            <li class="{{ $request->segment(2) == 'questions' ? 'active' : '' }}">
                <a href="{{ route('admin.questions.index') }}">
                    <i class="fa fa-question"></i>
                    <span class="title">@lang('global.questions.title')</span>
                </a>
            </li>
            @endcan
            
            @can('questions_option_access')
            <li class="{{ $request->segment(2) == 'questions_options' ? 'active' : '' }}">
                <a href="{{ route('admin.questions_options.index') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">@lang('global.questions-options.title')</span>
                </a>
            </li>
            @endcan --}}
            
            @can('test_access')
            <li class="{{ $request->segment(2) == 'tests' ? 'active' : '' }}">
                <a href="{{ url('admin/tests') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">Quizzes</span>
                </a>
            </li>
            @endcan

            <li class="{{ $request->segment(2) == 'exams' ? 'active' : '' }}">
                <a href="/admin/exams">
                    <i class="fa fa-briefcase"></i>
                    <span class="title">Exams</span>
                </a>
            </li>

            <li class="{{ $request->segment(2) == 'events' ? 'active' : '' }}">
                <a href="/admin/events">
                    <i class="fa fa-calendar-alt"></i>
                    <span class="title">Calendar</span>
                </a>
            </li>
            <li class="{{ $request->segment(2) == 'assignments' ? 'active' : '' }}">
                <a href="/admin/assignments">
                    <i class="fa fa-edit"></i>
                    <span class="title">Assignments</span>
                </a>
            </li>
            <li class="{{ $request->segment(2) == 'live-classes' ? 'active' : '' }}">
                <a href="/admin/live-classes">
                    <i class="fa fa-headset"></i>
                    <span class="title">Live Classes</span>
                </a>
            </li>
            <li class="{{ $request->segment(2) == 'subscribe' ? 'active' : '' }}">
                <a href="/admin/subscribe">
                    <i class="fa fa-wallet"></i>
                    <span class="title">Billing</span>
                </a>
            </li>

            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">Change password</span>
                </a>
            </li>

            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('global.logout')</button>
{!! Form::close() !!}
