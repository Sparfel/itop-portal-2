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
        <div class="row">
            <div class="col-md-8">
            <form action="/newrequest" method="post" id="newRequest" enctype="multipart/form-data">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-pencil-alt"></i>
                        {{__('New Ticket')}}
                    </h3>
                    <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                        <dl class="row">
                            {{ csrf_field() }}
{{--                            <dt class="col-sm-2">{{__('Title')}}</dt>--}}
                            <dd class="col-sm-12">
                                <label>{{__('Title')}}</label>
                                <input type='text' id='title' name='title' class="form-control" placeholder="{{__('Few words briefly describing your request')}}">
                            </dd>
{{--                            <dt class="col-sm-2">{{__('Location')}}</dt>--}}
                            <dd class="col-sm-6">
                                <label>{{__('Location')}}</label>
                                <select name="location" id="location" class="select2bs4_1"  data-placeholder="{{__('Site')}}"
                                        style="width: 100%;">
                                    @foreach($Locations as $id=>$location)
                                    <option @if ($user_loc_id== $id) SELECTED @endif value="{{$id}}"> {{$location}}</option>
                                    @endforeach
                                </select>
                            </dd>
{{--                            <dt class="col-sm-2">{{__('Type')}}</dt>--}}
                            <dd class="col-sm-6">
                                <label>{{__('Request Type')}}</label>
                                <select name="type" id="type" class="select2bs4_2"  data-placeholder="{{__('Type')}}"
                                        style="width: 100%;">
                                    <option value="user_issue"> Incident</option>
                                    <option value="service_request"> Services Request</option>
                                    <option value="other"> Other</option>
                                </select>

                            </dd>



{{--                            <dt class="col-sm-2">{{__('Description') }}</dt>--}}
{{--                            <dd class="col-sm-6">--}}
{{--                                <label>{{__('Service')}}</label>--}}
{{--                                <select name="service" id="selectService" class="form-control"  data-placeholder="{{__('Service')}}"--}}
{{--                                        style="width: 100%;">--}}

{{--                                </select>--}}
{{--                            </dd>--}}

                            <div class="form-group col-sm-6">
                                <label>{{__('Service')}}</label>
                                <div class="input-group " id="" data-target-input="nearest">
                                    <div id="serviceLoader" class="input-group-prepend">
{{--                                        <div class="input-group-text" id="loader"><img style="height:24px;" src="{{URL::asset('/img/sm_loader.gif')}}"></div>--}}
                                    </div>
                                    <select name="service" id="selectService" class="form-control"  data-placeholder="{{__('Service')}}"  >
                                    </select>

                                </div>
                                <!-- /.input group -->
                            </div>

                            <div class="col-sm-6">
                                <div class="callout callout-info text-sm">
                                    <h6 id="selectInfoTitle"><i class="fas fa-info"></i> Note:</h6>
                                    <p id="selectInfoDescription"> This page has been enhanced for printing.</p>
                                </div>
                            </div>


                            <dd class="col-sm-12">
                                <label>{{__('Description') }}</label>
                                <textarea id="summernote" class="summernote" name="description"></textarea>
                            </dd>
                            <dd class="col-sm-12">
                                <div class="dropzone" id="myDropzone" style="background-color:lightgray;">
                                    <div class="dz-message" data-dz-message><span>{{__('Drop files here to upload') }}</span></div>
                                </div>

                            </dd>
                        </dl>

                        <input hidden type="submit" id="validNewLog" value="Valider">


                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-4">

            @if ($userMode == 'expert')
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tags"></i>
                            {{__('Complements')}}
                        </h3>
                        <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 ">{{__('Impact')}}</label>
                            <div class="col-sm-8">
                                <select name="type" id="impact" class="form-control"  data-placeholder="{{__('Type')}}" style="width: 100%;">
                                    <option value="multiple_user"> {{__('multiple user')}}</option>
                                    <option value="single_user"> {{__('single_user')}}</option>
                                    <option value="undefined" SELECTED> {{__('undefined')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 ">{{__('Issue Type')}}</label>
                            <div class="col-sm-8">
                                <select name="type" id="issue_type" class="select2bs4_2"  data-placeholder="{{__('Type')}}" style="width: 100%;">
                                    <option value=""> {{__('-- select one --')}}</option>
                                    <option value="Automation"> {{__('automation')}}</option>
                                    <option value="Electrical"> {{__('electrical')}}</option>
                                    <option value="Mechanical"> {{__('mechanical')}}</option>
                                    <option value="Software"> {{__('software')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label i class="col-sm-4 col-form-label">{{__('Part creating issue')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="part_creating_issue" placeholder="{{__('Part creating issue')}}">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-4 ">{{__('Component creating issue')}}</label>
                            <div class="col-sm-8">
                                <select name="type" id="componentcreatingissue_id" class="select2bs4_2"  data-placeholder="{{__('Component creating issue')}}" style="width: 100%;">
                                    <option value=""> {{__('-- select one --')}}</option>
                                    <option value="Automation"> {{__('automation')}}</option>
                                    <option value="Electrical"> {{__('electrical')}}</option>
                                    <option value="Mechanical"> {{__('mechanical')}}</option>
                                    <option value="Software"> {{__('software')}}</option>
                                </select>
                            </div>
                        </div>


                    </div>
                </div>
            @endif
        </form>


            <div class="callout callout-primary">
                <button type="button" id="valid" class="btn btn-primary btn-block"><i class="fas fa-check"></i> {{__('Create')}}</button>
            </div>

            <form style="display: none" action="/requestcreated" method="POST" id="redirection">
                {{ csrf_field() }}
                <input type="hidden" id="ref" name="ref" value=""/>
            </form>
        </div>



    </div>
    </div>

@endsection

@section('js')
    {{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>--}}


    <!-- JS -->
    <!-- si js intégré dans app.js, cela ne fonctionne pas : mystere !!-->
    <script src="{{asset('vendor/dropzone/dist/min/dropzone.min.js')}}" type="text/javascript"></script>

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
                }
            );

            //Validation du formulaire par le bouton Valider
            $("#valid").click(function(e) {
                //document.getElementById("changeStatus").value = "update";
               // $("#newRequest").submit();
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


            });

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
                    document.getElementById("valid").addEventListener("click", function(e) {
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
                    //send all the form data along with the files:
                    console.log('on envoie les données formulaire !');
                    dzClosure.on("sendingmultiple", function(data, xhr, formData) {
                        formData.append("title", jQuery("#title").val());
                        formData.append("type", jQuery("#type").val());
                        formData.append("location", jQuery("#location").val());
                        formData.append("description", jQuery("#summernote").val());
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


            $("#location").on('change',function(){
                //alert('on change de site pour '+$("#location").val());
                console.log('Changement de site');
                var selectService = document.getElementById("selectService");
                selectService.innerHTML = "";
                var selectInfoTitle = document.getElementById("selectInfoTitle");
                selectInfoTitle.innerHTML = "";
                var serviceLoader = document.getElementById("serviceLoader");
                serviceLoader.innerHTML = '<div class="input-group-text" id="loader"><img style="height:24px;" src="{{URL::asset('/img/sm_loader.gif')}}"></div>';

                jQuery.ajax({
                    url: window.location.origin + '/ajaxlistservices',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'location_id' : $("#location").val()
                    },
                    success: function(data){
                        //toastr['success']('{{__('Mode has been changed')}}','{{__('User Mode')}}');
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    },
                    complete : function(data){
                        //window.location.reload();
                        //const obj = JSON.parse(data);

                        //console.log(data);
                        console.log(data.responseJSON);
                        TabServices = data.responseJSON;
                        jlist_1 = data.responseJSON;
                        //var select = document.getElementById ("service");
                        for(var i=0;i<jlist_1.length;i++){
                            var newOption = new Option (jlist_1[i].name, jlist_1[i].id);
                            selectService.options.add (newOption);
                            //TabServices[ jlist_1[i].id] = jlist_1[i].description
                            // https://stackoverflow.com/questions/1144705/best-way-to-store-a-key-value-array-in-javascript
                        }
                        serviceLoader.innerHTML ='';
                        console.log(jlist_1[0].id);
                        console.log(jlist_1.length);

                    }
                });
            });

            $("#selectService").on('change',function(){
                //alert($("#selectService").innerHTML);
                var liste = document.getElementById("selectService");
                var index = liste.options.selectedIndex; // index de l'option sélectionnée

                var valeur = liste.options[index].value // la valeur de l'option
                var texte = liste.options[index].innerHTML; //
                //alert(texte);
                var selectInfoTitle = document.getElementById("selectInfoTitle");
                selectInfoTitle.innerHTML = texte;
                console.log(TabServices);
            });



        });





    </script>

@endsection
