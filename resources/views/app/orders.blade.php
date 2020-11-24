@extends('layouts.app')

@section('title')
    Orders
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">
            <div class="card">

                <div class="content scroll-x-box">

                    <orders-list></orders-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection
