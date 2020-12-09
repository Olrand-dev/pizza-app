<div class="container-fluid">

    <div class="navbar-header">

        <button type="button" class="navbar-toggle" data-toggle="collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        @can('show-sidebar')
            <span class="page-name">{{ $pageName }}</span>
        @else
            {{--<x-site-logo></x-site-logo>--}}
        @endcan

        <div class="top-user-mobile">
            <x-top-user :user="$user"/>
        </div>

    </div>

    <div class="collapse navbar-collapse">

        {{-- <ul class="nav navbar-nav navbar-left">
            <li>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-dashboard"></i>
                </a>
            </li>
        </ul> --}}

        <x-top-user :user="$user"/>

    </div>

</div>
