@extends('layouts.app')

@section('title')
Orders
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">

                <div class="content">

                    <orders-list></orders-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection
