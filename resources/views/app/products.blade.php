@extends('layouts.app')

@section('title')
    Products
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">
            <div class="card">

                <div class="content scroll-x-box">

                    <products-list></products-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection
