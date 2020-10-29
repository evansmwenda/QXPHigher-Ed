<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>


<body class="hold-transition skin-blue sidebar-mini">

<div id="wrapper">

@include('partials.topbar')


<!-- //beginning of sidebar -->

@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

            <li class="{{ $request->segment(1) == '' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-home fa-1x"></i>
                    <span class="title">@lang('global.app_dashboard')</span>
                </a>
            </li>

            <!-- ensure user logged in to view this -->
            @if(\Auth::check())
                <li class="{{ $request->segment(1) == 'courses' ? 'active' : '' }}">
                    <a href="/courses">
                        <i class="fa fa-gears"></i>
                        <span class="title">@lang('global.courses.title')</span>
                    </a>
                </li>
                
                <li class="{{ $request->segment(1) == 'calender' ? 'active' : '' }}">
                    <a href="/calender">
                        <i class="fa fa-calendar-alt"></i>
                        <span class="title">Calender</span>
                    </a>
                </li>
                <li class="{{ $request->segment(1) == 'browse-lessons' ? 'active' : '' }}">
                    <a href="/browse-lessons">
                        <i class="fa fa-calendar-alt"></i>
                        <span class="title">Browse Lessons</span>
                    </a>
                </li>

                <li class="{{ $request->segment(1) == 'assignments' ? 'active' : '' }}">
                    <a href="/assignments">
                        <i class="fa fa-edit"></i>
                        <span class="title">Assignments</span>
                    </a>
                </li>
                <li class="{{ $request->segment(1) == 'exams' ? 'active' : '' }}">
                    <a href="/exams">
                        <i class="fa fa-briefcase"></i>
                        <span class="title">Exams & Certification</span>
                    </a>
                </li>
                <li class="{{ $request->segment(1) == 'live-classes' ? 'active' : '' }}">
                    <a href="/live-classes">
                        <i class="fa fa-headset"></i>
                        <span class="title">Live Classes</span>
                    </a>
                </li>
                <li class="{{ $request->segment(1) == 'account' ? 'active' : '' }}">
                    <a href="/account">
                        <i class="fa fa-headset"></i>
                        <span class="title">Edit Acccount</span>
                    </a>
                </li>

                <li class="{{ $request->segment(1) == 'faq' ? 'active' : '' }}">
                    <a href="/faq">
                        <i class="fa fa-question"></i>
                        <span class="title">FAQs</span>
                    </a>
                </li>
                <li class="{{ $request->segment(1) == 'subscribe' ? 'active' : '' }}">
                    <a href="/subscribe">
                        <i class="fa fa-wallet"></i>
                        <span class="title">Subscribe</span>
                    </a>
                </li>
                

                <!-- <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                    <a href="{{ route('auth.change_password') }}">
                        <i class="fa fa-key"></i>
                        <span class="title">Change password</span>
                    </a>
                </li> -->
            @endif

            

            @if(\Auth::check())
                <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
            @else
            <li>
                <a href="/login">
                    <i class="fa fa-arrow-right"></i>
                    <span class="title">Login</span>
                </a>
            </li>

            @endif



        </ul>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('global.logout')</button>
{!! Form::close() !!}

<!-- //end of sidebar -->


<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            @if(isset($siteTitle))
                <h3 class="page-title">
                    {{ $siteTitle }}
                </h3>
            @endif

            <div class="row">
                <div class="col-md-12">

                    @if (Session::has('message'))
                        <div class="note note-info">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    @if ($errors->count() > 0)
                        <div class="note note-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('main')

                </div>
            </div>
        </section>
    </div>
</div>

{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">Logout</button>
{!! Form::close() !!}

@include('partials.javascripts')
</body>
</html>