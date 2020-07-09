<!doctype html>
<html lang="ru">

<head>

	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="{{ asset('dev-lib/img/favicon.ico') }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Pizza App | @yield('title')</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('dev-lib/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{ asset('dev-lib/css/animate.min.css') }}" rel="stylesheet"/>

    <!--  Light Bootstrap Dashboard CSS    -->
    <link href="{{ asset('dev-lib/css/light-bootstrap-dashboard.css?v=1.4.0') }}" rel="stylesheet"/>

    <link href="{{ asset('dev-lib/css/style.css') }}" rel="stylesheet"/>


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{ asset('dev-lib/css/pe-icon-7-stroke.css') }}" rel="stylesheet" />

</head>


<body>

    <div id="app" class="wrapper">

        <div class="sidebar" data-color="orange" data-image="">
        
            <div class="sidebar-wrapper">

                <div class="logo">
                    <a href="{{ url('/') }}" class="simple-text">
                        Pizza App
                    </a>
                </div>
    
                <x-sidebar-menu></x-sidebar-menu>

            </div>
        </div>

    
        <div class="main-panel">

            <nav class="navbar navbar-default navbar-fixed">
                
                <x-top-menu></x-top-menu>

            </nav>
    
    
            <div class="content">

                @yield('content')
 
            </div>
    
    
            <footer class="footer">
                <div class="container-fluid">

                    {{-- <nav class="pull-left">
                        <ul>
                            <li>
                                <a href="#">
                                    Home
                                </a>
                            </li>
    
                        </ul>
                    </nav> --}}

                    <p class="copyright pull-right">
                        &copy; @{{ year }} 
                        <a href="https://www.webworkshop.dev">Web Workshop</a> | 
                        template by <a href="http://www.creative-tim.com">Creative Tim</a>
                    </p>

                </div>
            </footer>
    
        </div>

    </div>
    
</body>


<!--   Core JS Files   -->
<script src="{{ asset('dev-lib/js/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('dev-lib/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!--  Charts Plugin -->
{{-- <script src="{{ asset('dev-lib/js/chartist.min.js') }}"></script> --}}

<!--  Notifications Plugin    -->
<script src="{{ asset('dev-lib/js/bootstrap-notify.js') }}"></script>

<!--  Google Maps Plugin    -->
{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}

<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="{{ asset('dev-lib/js/light-bootstrap-dashboard.js?v=1.4.0') }}"></script>

<script src="{{ asset('dev-lib/js/app.js') }}"></script>

</html>
