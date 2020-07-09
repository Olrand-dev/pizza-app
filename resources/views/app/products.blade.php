@extends('layouts.app')

@section('title')
Products
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">

                <div class="content">

                    <products-list></products-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection