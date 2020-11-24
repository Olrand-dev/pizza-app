@extends('layouts.master')

@section('page_title')
    @yield('title')
@endsection

@section('layout_content')
<div id="app" class="wrapper">

    <div class="content">

        @yield('content')

    </div>

</div>
@endsection
