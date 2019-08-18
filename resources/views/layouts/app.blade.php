<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($meta_title) ? $meta_title : config('app.name')}}</title>
    <meta name="description" content="{{ isset($meta_description) ? $meta_description : '' }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'CRM') }}
            </a>
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
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @if (Auth::user()->status==1)

                           @if (Auth::user()->role==1)
                               <li class="nav-item">
                                   <a class="nav-link" href="{{ route('import')}}">
                                       Import
                                    </a>
                                </li>
                            @endif
                           
                            @if (Auth::user()->role==1||Auth::user()->role==2)
                           <li class="nav-item">
                                <a class="nav-link" href="{{ route('taskslist')}}">
                                   Tasks List (Admin,Moderator)
                                </a>
                            </li>
                            @endif
                           
                            @if (Auth::user()->role==3)
                           <li class="nav-item">
                                <a class="nav-link" href="{{ route('taskslistuser')}}">
                                   Tasks List 
                                </a>
                            </li>
                            @endif

                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('posts')}}">
                                    Posts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact')}}">
                                    {{ __('Contact') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('help')}}">
                                    {{ __('Help') }}
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if (Auth::user()->status==1&&Auth::user()->role==3)
                                    <a class="dropdown-item" >Limit: {{Auth::user()->weight}}</a>
                                    <a class="dropdown-item" href="{{ route('mytask')}}">My task</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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
    <header class="masthead"
            style="background-image: url(@if (isset($image)&&$image)'@php echo  $image;@endphp'@else'/img/analiz-it-800x445-1240x631.jpg' @endif)">
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
    <div class="container">
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
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    @if ($socials)
                        <ul class="list-inline text-center">
                            <li class="list-inline-item">
                                <a href="{{$socials[0]['link']}}" target="_blank">
                                        <span class="fa-stack fa-lg">
                                          <i class="fas fa-circle fa-stack-2x"></i>
                                          <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                     </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{$socials[1]['link']}}" target="_blank">
                                        <span class="fa-stack fa-lg">
                                          <i class="fas fa-circle fa-stack-2x"></i>
                                          <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                        </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{$socials[2]['link']}}" target="_blank">
                                        <span class="fa-stack fa-lg">
                                          <i class="fas fa-circle fa-stack-2x"></i>
                                          <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                                         </span>
                                </a>
                            </li>
                        </ul>
                    @endif
                    <p class="copyright text-muted">Copyright
                        &copy; {{ config('app.name', 'Laravel') }} @php echo date('Y'); @endphp</p>
                </div>
            </div>
        </div>
    </footer>

    <back-to-top visibleoffset="200" bottom="50px" right="50px">
        <button type="button" class="btn btn-info btn-to-top"><i class="fa fa-chevron-up"></i></button>
    </back-to-top>
    <saved-component></saved-component>
    

</div>
</body>
</html>
