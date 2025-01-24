@extends('adminlte::page')

@section('css')
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('vendor/dropzone/dist/min/dropzone.min.css')}}">--}}

@endsection

@section('content_header')
    <h1><i class="fas fa-check"></i> {{ __('Congratulations') }}</h1>
@endsection

@section('content')

<div class="container-fluid ">
    <div class="row">
        <div class=" col-md-6 col-md-offset-3">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i>
                        {{ __('Request created') }}
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <div class="callout callout-success">
                        <h5>{{ __('Request was created as :ref.',['ref' => $ticketItop_ref]) }}</h5>
                        <h5>{{ __('You will soon recieve an email to call you back this reference.')}}</h5>

                        <p>{{ __('The Helpdesk Team Support') }}</p>

                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->
        </div>
    <!-- /.col -->
    </div>
</div>




@endsection

@section('js')

@endsection
