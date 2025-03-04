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
                    <h4> {{ $Ticket->title}}</h4>
                    <div class="tab-content" id="tabContent">
                        <div class="tab-pane fade {{$showInfo}}" id="ticket" role="tabpanel" aria-labelledby="information-tab">
                            @include('frontend.request.closedrequest.information')
                            {{--                    {{ dd($ticket) }}--}}
                        </div>
                        <div class="tab-pane fade {{$showLog}}" id="log" role="tabpanel" aria-labelledby="log-tab">
                            @include('frontend.request.closedrequest.log')
                            {{--                    {{ dd($ticket) }}--}}
                        </div>
                        <div class="tab-pane fade {{$showContact}}" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            @include('frontend.request.closedrequest.contact')
                        </div>
                        <div class="tab-pane fade {{$showAttach}}" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            @include('frontend.request.closedrequest.attachment')
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
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>

        Dropzone.autoDiscover = false;
        $(document).ready(function() {

            //Editeur
            $('#summernote').summernote(
                {
                    height: 300
                }
            );

            //Validation du formulaire par le bouton Valider
            $("#valid").click(function() {
                document.getElementById("changeStatus").value = "update";
                $("#newLog").submit();
            });
            //Marquer le ticket comme résolu
            $("#solve").click(function() {
                document.getElementById("changeStatus").value = "solve";
                $("#newLog").submit();
            });
            $("#close").click(function() {
                document.getElementById("changeStatus").value = "close";
                $("#newLog").submit();
            });
            $("#reopen").click(function() {
                document.getElementById("changeStatus").value = "reopen";
                $("#newLog").submit();
            });


            //Ajout des contacts au ticket
            $("#saveAddContact").click(function() {
                alert('on ajoute ces contacts et on raffaichit la page '+$("#addContactList").val());
                jQuery.ajax({
                    url: window.location.origin + '/addcontactlist',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'list' : $("#addContactList").val(),
                        'request_id': {{$Ticket->id}}
                    },
                    success: function(data){

                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    },
                    complete : function(data){
                        //window.location.reload();
                        console.log(data);
                    }
                });
            });

            //Utilisé pour la liste multiple des contacts à ajouter
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: "{{__('Select one or more contacts')}}",
                maximumSelectionLength: 5, //On limite l'ajout à 5 contacts d'un coup
                ajax: {
                    url: window.location.origin+'/getcontactlist/',
                    datatype: 'json',
                    delay: 250,
                    minimumInputLength: 3,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        }
                        //console.log(query);
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        //console.log(data);
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
            })

            //Dropzone : Ajout de pièces jointes
            //Dropzone pour les pièces jointes
            var fileList = new Object();
            $('#dropzone').dropzone({
                url: '{{ route('uploadattachment') }}',
                method: 'post',
                //acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                maxFilesize: 2,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                init: function() {
                    this.on("sending", function(file, xhr, formData) {
                        formData.append("request_id", "{{$Ticket->id}}");
                    });
                },
                removedfile: function(file)
                 {
                     var name = file.upload.filename;
                     if (file.upload.progress == 100)
                     {var fileid = fileList[file.upload.uuid]['fileid'];}
                     else {fileid = '-';}
                     $.ajax({
                         type: 'POST',
                         url: '{{ route('removeattachment') }}',
                         data: { "_token": "{{ csrf_token() }}",
                                name: name,
                                request_id: fileid },
                         success: function (data){
                             console.log("File "+ fileid +" has been successfully removed!!");
                             console.log(data);
                         },
                         error: function(e) {
                             console.log(e);
                         }});
                     var fileRef;
                     return (fileRef = file.previewElement) != null ?
                         fileRef.parentNode.removeChild(file.previewElement) : void 0;
                 },
                 success: function (file, response) {
                     fileList[file.upload.uuid] = { "fileName" : file.name, "fileid" : response.request_id };
                 },
            });


            // //TEST
            // $(document).ready(function() {
            //     $('#btnOpenSaltB').click(function() {
            //         Swal.fire(
            //             'Good job!',
            //             'You clicked the button!',
            //             'success'
            //         );
            //     });
            //
            //     var Toast = Swal.mixin({
            //         toast: true,
            //         position: 'top-end',
            //         showConfirmButton: false,
            //         timer: 30000
            //     });
            //
            //     $('#btnOpenSaltC').click(function() {
            //         Toast.fire({
            //             icon: 'success',
            //             title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            //         });
            //     });
            // })

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
