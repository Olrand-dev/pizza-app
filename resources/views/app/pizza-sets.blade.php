@extends('layouts.app')

@section('title')
Pizza Sets
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">

                <div class="content">

                    <pizzasets-list></pizzasets-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection