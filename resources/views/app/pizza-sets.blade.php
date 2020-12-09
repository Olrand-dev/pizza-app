@extends('layouts.app')

@section('title')
    Pizza Sets
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="{{ Auth::user()->can('show-sidebar') ? 'col-md-12' : 'col-md-10 col-md-offset-1' }}">
            <div class="card">

                <div class="content scroll-x-box">

                    <pizzasets-list></pizzasets-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection
