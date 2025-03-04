@extends('adminlte::page')

@section('css')
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('vendor/dropzone/dist/min/dropzone.min.css')}}">--}}

@endsection

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-11">
            <h1><i class="fas fa-edit"></i> {{ __('New Request') }}</h1>
        </div>

        <div class="col-sm-1 float-right">
            <div class="btn-group">
                <button type="button" name="valid" id="valid-global" class="btn btn-outline-primary" title=" {{__('Creation')}}"><i class="fas fa-check"></i></button>
            </div>
            @if ($userMode == 'expert')
                <button type="button"
                            id="creationMode"
                            class="btn btn-outline-primary"
                            title=" {{__('Standard mode')}}"
                            value="standard">
                    <i class="fas fa-user-tie"></i>
               </button>
            @else
                <button type="button"
                        id="creationMode"
                        class="btn btn-outline-primary"
                        title=" {{__('Expert mode')}}"
                        value="expert">
                    <i class="fas fa-user-graduate"></i>
                </button>
            @endif
        </div>
    </div>

@endsection

@section('content')

    <div class="container-fluid ">
        <form action="/newrequest" method="post" id="newRequest" enctype="multipart/form-data">
        <div class="row">
            @if ($userMode == 'expert')
                @include('frontend.request.newrequest.create-expert')
            @else
                @include('frontend.request.newrequest.create-basic')
            @endif
        </div>
        </form>

        <form style="display: none" action="/requestcreated" method="POST" id="redirection">
            {{ csrf_field() }}
            <input type="hidden" id="ref" name="ref" value=""/>
        </form>
    </div>
@endsection

@section('footer')
    &nbsp;
@endsection

@push('js')
    {{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>--}}
    <!-- JS -->

    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>

        Dropzone.autoDiscover = false;
        $(document).ready(function() {

            var TabServices;

            var  dzClosure;
            //Editeur
            $('#summernote').summernote(
                {
                    height: 300,
                    placeholder: '{{__('Please include as much detail as possible in your description')}}',
                    required: true
                }
            );

            //Validation du formulaire par le bouton Valider
            $("#valid-global").click(function(e) {
                console.log('validation global');
                validate();
            });

            $("#valid-expert").click(function(e) {
                console.log('validation expert');
                validate();
            });

            $("#valid-basic").click(function(e) {
                console.log('validation basic');
                validate();
            });

            function validate(){
                //document.getElementById("changeStatus").value = "update";
                //$("#newRequest").submit();
                console.log(dzClosure.files);
                if (dzClosure.files.length > 0 )
                { console.log(dzClosure.files);}
                else {
                    console.log('on valide ici le formulaire car pas de fichier joint');
                    //un appel ajax ici pour poster le formulaire sans fichier puis valider
                    //le second formulaire avec le résultat du premier et afficher les féliciations
                    //il devrait y a voir une façon plus simple ...
                    /*$("#newRequest").submit();
                    $("#redirection").submit();*/
                }
            }

            $('form').submit(function(event) {
                if (this.checkValidity() == false)
                {
                    //Si pas de description, on met un Warning
                    if ($('#summernote').summernote('isEmpty')) {
                        toastr['error']('{{__('Please provide as much information as possible in the ticket so that we can best answer you.')}}','{{__('Description missing')}}');
                        $('#summernote').summernote('focus');
                    }
                    console.log(this.id);
                    event.preventDefault()
                    event.stopPropagation()
                }
                $(this).addClass("was-validated")
            })

            $('#myDropzone').dropzone({
                url: '{{ route('newrequest') }}',
                autoProcessQueue: false,
                method: 'post',
                uploadMultiple: true,
                parallelUploads: 5,
                maxFiles: 5,
                maxFilesize: 2,
                /*acceptedFiles: 'image/*',*/
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                init: function() {
                    dzClosure = this; // Makes sure that 'this' is understood inside the functions below.
                    // for Dropzone to process the queue (instead of default form behavior):
                    validBtns = document.getElementsByName('valid');
                    for (const validBtn of validBtns) {
                        validBtn.addEventListener("click", function (e) {
                            // Make sure that the form isn't actually being sent.
                            e.preventDefault();
                            e.stopPropagation();
                            // console.log('on remonte les fichiers ?');
                            //dzClosure.processQueue();

                            console.log(dzClosure.getQueuedFiles().length);
                            if (dzClosure.getQueuedFiles().length > 0) {
                                dzClosure.processQueue();
                            } else {
                                console.log('que si pas de fichier !');
                                $("#newRequest").submit();
                            }
                        });
                    }
                    //send all the form data along with the files:
                    console.log('on envoie les données formulaire !');
                    dzClosure.on("sendingmultiple", function(data, xhr, formData) {
                        formData.append("mode", jQuery("#mode").val());
                        formData.append("title", jQuery("#title").val());
                        formData.append("type", jQuery("#type").val());
                        formData.append("location", jQuery("#location").val());
                        formData.append("description", jQuery("#summernote").val());
                        if (jQuery("#mode").val() == 'expert'){// mode expert, on a des champs en plus
                            formData.append("service", jQuery("#service").val());
                            formData.append("module", jQuery("#module").val());
                            formData.append("impact", jQuery("#impact").val());
                            formData.append("issuetype", jQuery("#issuetype").val());
                            formData.append("failurecomponentissue", jQuery("#failurecomponentissue").val());
                            formData.append("failuremode", jQuery("#failuremode").val());
                        }
                    });
                    dzClosure.on('complete',function(data){
                        console.log(data);
                        //window.location.href = '{{ route('home')}}';
                    })
                },
                 success: function (file, response) {
                     console.log(response);
                     $("#ref").val(response);
                     console.log('Redirection ...');
                     $("#redirection").submit();
                     {{--window.location.href = '{{ route('home')}}';--}}
                }
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
                        {{--'request_id': {{$Ticket->id}}--}}
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


            $("#creationMode").click(function() {
               //alert('on change de mode '+$("#creationMode").val());
                jQuery.ajax({
                    url: window.location.origin + '/changeparam',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'userMode' : $("#creationMode").val()
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


            {{--$("#location").on('change',function(){--}}
            {{--    //alert('on change de site pour '+$("#location").val());--}}
            {{--    console.log('Changement de site');--}}
            {{--    var selectService = document.getElementById("selectService");--}}
            {{--    selectService.innerHTML = "";--}}
            {{--    var selectInfoTitle = document.getElementById("selectInfoTitle");--}}
            {{--    selectInfoTitle.innerHTML = "";--}}
            {{--    var serviceLoader = document.getElementById("serviceLoader");--}}
            {{--    serviceLoader.innerHTML = '<div class="input-group-text" id="loader"><img style="height:24px;" src="{{URL::asset('/img/sm_loader.gif')}}"></div>';--}}

            {{--    jQuery.ajax({--}}
            {{--        url: window.location.origin + '/ajaxlistservices',--}}
            {{--        datatype: 'json',--}}
            {{--        method : "POST",--}}
            {{--        data      :{--}}
            {{--            "_token": "{{ csrf_token() }}",--}}
            {{--            'location_id' : $("#location").val()--}}
            {{--        },--}}
            {{--        success: function(data){--}}
            {{--            //toastr['success']('{{__('Mode has been changed')}}','{{__('User Mode')}}');--}}
            {{--        },--}}
            {{--        error :function(xhr, ajaxOptions, thrownError){--}}
            {{--            alert(xhr.status);--}}
            {{--            alert(thrownError);--}}
            {{--        },--}}
            {{--        complete : function(data){--}}
            {{--            //window.location.reload();--}}
            {{--            //const obj = JSON.parse(data);--}}

            {{--            //console.log(data);--}}
            {{--            console.log(data.responseJSON);--}}
            {{--            TabServices = data.responseJSON;--}}
            {{--            jlist_1 = data.responseJSON;--}}
            {{--            //var select = document.getElementById ("service");--}}
            {{--            for(var i=0;i<jlist_1.length;i++){--}}
            {{--                var newOption = new Option (jlist_1[i].name, jlist_1[i].id);--}}
            {{--                selectService.options.add (newOption);--}}
            {{--                //TabServices[ jlist_1[i].id] = jlist_1[i].description--}}
            {{--                // https://stackoverflow.com/questions/1144705/best-way-to-store-a-key-value-array-in-javascript--}}
            {{--            }--}}
            {{--            serviceLoader.innerHTML ='';--}}
            {{--            console.log(jlist_1[0].id);--}}
            {{--            console.log(jlist_1.length);--}}

            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            {{--$("#selectService").on('change',function(){--}}
            {{--    //alert($("#selectService").innerHTML);--}}
            {{--    var liste = document.getElementById("selectService");--}}
            {{--    var index = liste.options.selectedIndex; // index de l'option sélectionnée--}}

            {{--    var valeur = liste.options[index].value // la valeur de l'option--}}
            {{--    var texte = liste.options[index].innerHTML; //--}}
            {{--    //alert(texte);--}}
            {{--    var selectInfoTitle = document.getElementById("selectInfoTitle");--}}
            {{--    selectInfoTitle.innerHTML = texte;--}}
            {{--    console.log(TabServices);--}}
            {{--});--}}



        });





    </script>

@endpush
