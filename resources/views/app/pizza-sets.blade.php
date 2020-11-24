@extends('layouts.app')

@section('title')
    Pizza Sets
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">
            <div class="card">

                <div class="content scroll-x-box">

                    <pizzasets-list></pizzasets-list>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection
