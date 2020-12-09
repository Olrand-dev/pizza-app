@extends('layouts.app')

@section('title')
    Employees
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="{{ Auth::user()->can('show-sidebar') ? 'col-md-12' : 'col-md-10 col-md-offset-1' }}">

            <employees-list></employees-list>

        </div>
    </div>

</div>
@endsection
