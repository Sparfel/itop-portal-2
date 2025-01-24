@extends('layouts.app')
{{--@extends('adminlte::page')--}}

@section('content_header')
    <h1><i class="fas fa-ban"></i> {{ __('Error') }}</h1>
@endsection

@section('content')

    <div class="error-page">
        <h2 class="headline text-warning"> 499</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Problem with your account.</h3>

            <p>
                Please contact Fives Administrator.
                Meanwhile, you may <a href="{{url('/logout')}}">go out</a>.
            </p>
            <p> Return to <a href="{{url('/')}}">Home</a>. </p>


        </div>
    <!-- /.error-content -->
    </div>
    <!-- /.error-page -->

@endsection
