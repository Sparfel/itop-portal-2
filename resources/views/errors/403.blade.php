{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1><i class="fas fa-ban"></i> {{ __('Error') }}</h1>
@endsection

@section('content')

    <div class="error-page">
        <h2 class="headline text-warning"> 403</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Access is denied.</h3>

            <p>
                You're not allowed to see this page.
                Meanwhile, you may <a href="{{url('/')}}">return to Home</a>.
            </p>


        </div>
    <!-- /.error-content -->
    </div>
    <!-- /.error-page -->

@endsection
