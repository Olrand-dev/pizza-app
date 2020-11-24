@extends('layouts.master')

@section('page_title')
    @yield('title')
@endsection

@section('layout_content')
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

            <x-footer></x-footer>

        </footer>

    </div>

</div>
@endsection
