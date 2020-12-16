<!doctype html>
<html lang="ru">

<head>

	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="{{ asset('dev-lib/img/favicon.ico') }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Pizza App | @yield('page_title')</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('dev-lib/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{ asset('dev-lib/css/animate.min.css') }}" rel="stylesheet"/>

    <!--  Light Bootstrap Dashboard CSS    -->
    <link href="{{ asset('dev-lib/css/light-bootstrap-dashboard.css?v=1.4.0') }}" rel="stylesheet"/>

    <!--  Vue image Lightbox CSS  -->
    <link href="{{ asset('dev-lib/css/vue-image-lightbox.min.css') }}" rel="stylesheet"/>

    <link href="{{ asset('dev-lib/css/style.css') }}" rel="stylesheet"/>


    <!--     Fonts and icons     -->
    {{--<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">--}}
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{ asset('dev-lib/css/pe-icon-7-stroke.css') }}" rel="stylesheet" />

</head>


<body>

    @yield('layout_content')

</body>


<!--   Core JS Files   -->
<script src="{{ asset('dev-lib/js/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('dev-lib/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!--   FontAwesome   -->
<script src="https://kit.fontawesome.com/35133f2e0e.js" crossorigin="anonymous"></script>

<!--  Charts Plugin -->
{{-- <script src="{{ asset('dev-lib/js/chartist.min.js') }}"></script> --}}

<!--  Notifications Plugin    -->
<script src="{{ asset('dev-lib/js/bootstrap-notify.js') }}"></script>

<!--  Google Maps Plugin    -->
{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}

<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="{{ asset('dev-lib/js/light-bootstrap-dashboard.js?v=1.4.0') }}"></script>

<!--  Vue JS pagination  -->
<script src="{{ asset('dev-lib/js/vue-paginate.min.js') }}"></script>

<script src="{{ asset('dev-lib/js/app.js') }}"></script>

</html>
