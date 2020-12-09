@extends('layouts.master')

@section('page_title')
    @yield('title')
@endsection

@section('layout_content')
<div id="app" class="wrapper">

    @can('show-sidebar')
        <div class="sidebar" data-color="orange" data-image="">

            <div class="sidebar-wrapper">

                <x-site-logo></x-site-logo>

                <x-sidebar-menu></x-sidebar-menu>

            </div>
        </div>
    @endcan


    <div class="{{ Auth::user()->can('show-sidebar') ? 'main-panel' : 'main-panel-full' }}">

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
