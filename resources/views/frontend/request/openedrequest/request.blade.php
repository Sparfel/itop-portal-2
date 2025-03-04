@extends('adminlte::page')

@section('css')
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
{{--    <link rel="stylesheet" type="text/css" href="{{asset('vendor/dropzone/dist/min/dropzone.min.css')}}">--}}

@endsection

@section('content_header')
    <h1><i class="fas fa-ticket-alt"></i> {{$Ticket->ref}}, {{ __('Request\'s Details') }}</h1>

@endsection


@section('content')
    @php
        $showInfo = '';
        $showLog = '';
        $showContact = '';
        $showAttach = '';
        switch ($tab) {
            case 'log' :
                $showLog = ' active show';
                break;
            case 'contact' :
                $showContact = ' active show';
                break;
            case 'attach' :
                $showAttach = ' active show';
                break;
            default :
                $showInfo = ' active show';
                break;
        }
   @endphp

    <div class="row">
        <div class="container-fluid ">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{$showInfo}}" id="information-tab" data-toggle="pill" href="#ticket" role="tab" aria-controls="information" aria-selected="true">{{ __('Request') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$showLog}}" id="log-tab" data-toggle="pill" href="#log" role="tab" aria-controls="log" aria-selected="true">{{ __('Logs') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$showContact}}" id="contact-tab" data-toggle="pill" href="#contact" role="tab" aria-controls="contact" aria-selected="false">{{ __('Contacts') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$showAttach}}" id="attachments-tab" data-toggle="pill" href="#attachments" role="tab" aria-controls="attachments" aria-selected="false">{{ __('Attachments') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">


                    <div class="row mb-2">
                        <div class="col-sm-10">
                            <h4> {{ $Ticket->title}}</h4>
                        </div>
                        <div class="col-sm-2 float-right">
                            <div class="row">

{{--                                <div class="btn-group">--}}
{{--                                    @if ($Abuttons->update)  <button type="button" id="validtop" class="btn btn-outline-primary" title=" {{__('Update')}}"><i class="fas fa-check"></i></button> @endif--}}
{{--                                    @if ($Abuttons->resolve) <button type="button" id="solvetop" class="btn btn-outline-primary" title=" {{__('Resolve')}}"> <i class="far fa-thumbs-up"></i></button> @endif--}}
{{--                                    @if ($Abuttons->close) <button type="button" id="closetop" class="btn btn-outline-primary" title=" {{__('Close')}}"><i class="fas fa-lock"></i></button> @endif--}}
{{--                                    @if ($Abuttons->reopen) <button type="button" id="reopentop" class="btn btn-outline-primary" title=" {{__('Re-open')}}"><i class="fas fa-unlock-alt"></i></button> @endif--}}
{{--                                </div>--}}
                                <buttons-request display="small" locale = "{{session('locale')}}" buttons={{ json_encode($Abuttons)}}></buttons-request>&nbsp;

                                <div class="btn-group">
                                    {{--                                    <button type="button" id="valid" class="btn btn-primary" title=" {{__('Normal mode')}}"> <i class="fas fa-user-tie"></i></button>--}}
{{--                                    <button type="button" id="expert" class="btn btn-outline-primary" title=" {{__('Expert mode')}}"><i class="fas fa-user-graduate"></i></button>--}}
                                    @if ($userMode == 'expert')
                                        <button type="button"
                                                id="updateMode"
                                                class="btn btn-outline-primary"
                                                title=" {{__('Standard mode')}}"
                                                value="standard">
                                            <i class="fas fa-user-tie"></i>
                                        </button>
                                    @else
                                        <button type="button"
                                                id="updateMode"
                                                class="btn btn-outline-primary"
                                                title=" {{__('Expert mode')}}"
                                                value="expert">
                                            <i class="fas fa-user-graduate"></i>
                                        </button>
                                    @endif

                                    @if (isset($Aattach) && count($Aattach)>0)
                                        <button type="button"
                                                id="downloadAllSmall"
                                                class="btn btn-outline-primary"
                                                title=" {{__('Download all attachments')}}"
                                                value="download">
                                            <i class="far fa-file-archive"></i>
                                        </button>

                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="tab-content" id="tabContent">
                        <div class="tab-pane fade {{$showInfo}}" id="ticket" role="tabpanel" aria-labelledby="information-tab">
{{--                            @include('frontend.request.openedrequest.information-basic')--}}
                            <form action="/openedrequest/{{$Ticket->id}}" method="post" id="newLog">
                                @if ($userMode == 'expert')
                                    @include('frontend.request.openedrequest.information-expert')
                                @else
                                    @include('frontend.request.openedrequest.information-basic')
                                @endif
                            </form>
                        </div>
                        <div class="tab-pane fade {{$showLog}}" id="log" role="tabpanel" aria-labelledby="log-tab">
                            @include('frontend.request.openedrequest.log')
                        </div>
                        <div class="tab-pane fade {{$showContact}}" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            @include('frontend.request.openedrequest.contact')
                        </div>
                        <div class="tab-pane fade {{$showAttach}}" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            @include('frontend.request.openedrequest.attachment')
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
{{--    <button type="button" class="btn btn-primary m-1" id="btnOpenSaltB">Open Sweetalert2 (Basic)</button>--}}
{{--    <button type="button" class="btn btn-success m-1" id="btnOpenSaltC">Open Sweetalert2 (Custom)</button>--}}


@endsection

@section('footer')
    &nbsp;
@endsection

@section('js')
{{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>--}}


    <!-- JS -->
    <!-- si js intégré dans app.js, cela ne fonctionne pas : mystere !!-->
{{--    <script src="{{asset('vendor/dropzone/dist/min/dropzone.min.js')}}" type="text/javascript"></script>--}}

    <script>

        // Dropzone.autoDiscover = false;
        $(document).ready(function() {

            $("#updateMode").click(function() {
                //alert('on change de mode '+$("#creationMode").val());
                jQuery.ajax({
                    url: window.location.origin + '/changeparam',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'userMode' : $("#updateMode").val()
                    },
                    success: function(data){
                        toastr['success']('{{__('Mode has been changed')}}','{{__('User Mode')}}');
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    },
                    complete : function(data){
                        //window.location.reload();

                        console.log(data);
                        setTimeout(function(){location.reload();
                        }, 500);


                    }
                });
            });

            // Read flag from the controller.

            @if(Session::has('msg'))
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
            var flashMsgJson = {!!Session::get('msg')!!};
            toastr[flashMsgJson.type](flashMsgJson.text,flashMsgJson.title);
            @endisset
        });
    </script>

@endsection
