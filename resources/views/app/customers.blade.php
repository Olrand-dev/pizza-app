@extends('layouts.app')

@section('title')
    Customers
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="{{ Auth::user()->can('show-sidebar') ? 'col-md-12' : 'col-md-10 col-md-offset-1' }}">

            <customers-list></customers-list>

        </div>
    </div>

</div>
@endsection
