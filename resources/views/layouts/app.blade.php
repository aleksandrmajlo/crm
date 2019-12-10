<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($meta_title) ? $meta_title : config('app.name')}}</title>
    <meta name="description" content="{{ isset($meta_description) ? $meta_description : '' }}">

    <script>
        $side_serials_def = '{{$side_serials['side_serials_def']}}';
        $count_def = '{{$side_serials['count_def']}}';
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/DataTables/datatables.min.css" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container">
            <ul class="navbar-nav ml-auto">
                <li>
                    <a class="navbar-brand btn btn-primary" href="{{ url('/') }}">
                        {{ config('app.name', 'CRM') }}
                    </a>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                    aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary"
                                   href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @if (Auth::user()->status==1)
                            @if (Auth::user()->role==1)
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('import')}}">
                                        Import
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->role==1||Auth::user()->role==2)
                                <li class="nav-item dropdown">
                                    <a id="navbarSearch" class="nav-link dropdown-toggle btn btn-primary" href="#"
                                       role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Log,search<span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('orderLog')}}">Log</a>
                                        <a class="dropdown-item" href="{{ route('orderLogID')}}">Log by ID</a>
                                        <a class="dropdown-item" href="{{ route('search')}}">Search</a>
                                        <a class="dropdown-item" href="{{ route('searchIP')}}">Search by IP</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('taskslist')}}">
                                        Tasks List
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->role==3)
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('taskslistuser')}}">
                                        Tasks List
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->status==1&&Auth::user()->role==3)
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('mytask')}}">My order</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn btn-info">Limit: {{Auth::user()->weight}}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary" href="{{ route('posts')}}">
                                    Posts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary" href="{{ route('contact')}}">
                                    {{ __('Contact') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary" href="{{ route('help')}}">
                                    {{ __('Help') }}
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle btn btn-primary" href="#"
                               role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <header class=" @if (isset($image)&&$image) @php echo  "masthead";@endphp @else @php echo  "masthead notImage";@endphp @endif"
            style="background-image: url(@if (isset($image)&&$image)'@php echo  $image;@endphp' @endif)">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>@yield('title')</h1>
                        @if (trim($__env->yieldContent('subheading')))
                            <span class="subheading">
                                    @yield('subheading')
                               </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-md-12">
                {!! $adverts !!}
            </div>
        </div>
        <div class="row">
            @if (isset($with_sidebar))
                <div class="col-lg-4 col-md-12 mx-auto">
                    @yield('sidebar')
                </div>
            @endif
            @php
                $width=8;
                if(isset($with_content)){
                    $width=$with_content;
                }
            @endphp
            <div class="col-lg-{{$width}} col-md-12 mx-auto">
                @yield('content')
            </div>
        </div>
    </div>

    <back-to-top visibleoffset="200" bottom="50px" right="50px">
        <button type="button" class="btn btn-info btn-to-top"><i class="fa fa-chevron-up"></i></button>
    </back-to-top>
    <saved-component></saved-component>
    <div class="modal fade" id="SelectUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <admin-update></admin-update>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg " id="LogTask" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <admin-logtask></admin-logtask>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg " id="AddSchowComment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cooments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <addorder-commentadmin></
                    <addorder-commentadmin>
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()&&Auth::user()->status==1&&Auth::user()->role==3)
        <addwork-task></addwork-task>
    @endif

</div>
</body>
</html>
