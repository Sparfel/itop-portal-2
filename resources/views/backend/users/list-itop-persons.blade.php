@extends('adminlte::page')

@section('css')
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
@endsection

@section('content_header')
    <h1> <i class="fas fa-user-plus"></i> {{ __('Contact from iTop') }}</h1>
@endsection

@section('content')


{{-- Modal d'ajout d'un compte --}}
@include('backend.users.new-itop-user')



    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            {{ __('List of Persons from iTop\'s import') }}</h3>
{{--                        <div class="float-right">--}}
{{--                            <x-front.filter-preference--}}
{{--                                idModal="filterRequestDatatable"--}}
{{--                                labelBtn="Filters"--}}
{{--                                requestFilter=true--}}
{{--                                usrOpenedRqt="true">--}}
{{--                            </x-front.filter-preference>--}}
{{--                        </div>--}}

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{ $dataTable->table(['class' => 'table table-bordered table-hover table-sm'], true) }}
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>


@endsection

@section('footer')
    &nbsp;
@endsection

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

{{--    @if(config('app.locale') == 'fr')--}}
        <script>
            var Aselected = [];
            (($, DataTable) => {

                $.extend(true, DataTable.defaults, {
                    processing: true,
                    serverSide: true,
                    "drawCallback": function( data) {
                        // console.log($('#customCheckbox1').is(':checked'));

                        Aselected.forEach((id) => {
                           //console.log(id);
                            if (document.getElementById("customCheckbox"+id)) {
                                document.getElementById("customCheckbox"+id).checked = true;}
                        });
                    },
                    "rowCallback": function( row, data ) {
                        //Permet de colorer les lignes après changement
                        // if ( $.inArray(data.id, Aselected) !== -1 ) {
                        //     //document.getElementById("customCheckbox"+data.id).checked = true;
                        //     $(row).addClass('selected');
                        //
                        // }
                    },

                    language: {
                        "sEmptyTable":     "Aucune donnée disponible dans le tableau",
                        "sInfo":           "Affichage des éléments _START_ à _END_ sur _TOTAL_ éléments",
                        "sInfoEmpty":      "Affichage de l'élément 0 à 0 sur 0 élément",
                        "sInfoFiltered":   "(filtré à partir de _MAX_ éléments au total)",
                        "sInfoPostFix":    "",
                        "sInfoThousands":  ",",
                        "sLengthMenu":     "Afficher _MENU_ éléments",
                        "sLoadingRecords": "Chargement...",
                        "sProcessing":     "Traitement...",
                        "sSearch":         "Rechercher :",
                        "sZeroRecords":    "Aucun élément correspondant trouvé",
                        "oPaginate": {
                            "sFirst":    "Premier",
                            "sLast":     "Dernier",
                            "sNext":     "Suivant",
                            "sPrevious": "Précédent"
                        },
                        "oAria": {
                            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                            "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                        },
                        "select": {
                            "rows": {
                                "_": "%d lignes sélectionnées",
                                "0": "Aucune ligne sélectionnée",
                                "1": "1 ligne sélectionnée"
                            },

                        }
                    }

                });


                $('#listitoppersons-table').on('stateLoadParams.dt', function() {
                    console.log('event')
                })

                $('#listitoppersons-table').on('click', 'tbody tr', function () {
                    // console.log('click checkbox');
                    // var table = $('#listitoppersons-table').DataTable();
                    // var row = table.row($(this)).data();
                    // console.log(row);
                    // var id = row.id;
                    // var index = $.inArray(id, Aselected);
                    // if ( index === -1 ) {
                    //     Aselected.push( id );
                    // } else {
                    //     Aselected.splice( index, 1 );
                    // }
                    // document.getElementById("customCheckbox"+row.id).checked = !document.getElementById("customCheckbox"+row.id).checked;
                    // $(this).toggleClass('selected');
                });

                // Array to track the ids of the details displayed rows
                var detailRows = [];

                $('#listitoppersons-table').on('click', 'input[type="checkbox"]', function () {
                    console.log('click checkbox');
                    var tr = $(this).closest("tr");
                    var row = $("#listitoppersons-table").DataTable().rows(tr);

                    var tr = $(this).closest('tr');
                    var row = $("#listitoppersons-table").DataTable().rows(tr).data()[0];
                    var idx = Aselected.indexOf(row.id);

                    // Add to the 'open' array
                    if (idx === -1) {
                        Aselected.push(row.id);
                    }
                    else {
                        Aselected.splice(idx, 1);
                    }
                   console.log (Aselected);
                });
              })(jQuery, jQuery.fn.dataTable);




            //Script globaux à la grille, appelé à partir des bouton du Datatable
            //On importe tous les contacts Itop actifs
            function importPerson() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // quand on demande de confirmer, c dajà fait !! a revoir
                document.body.style.cursor = 'wait';
                toastr.info('{{ __('Importing datas from iTop') }}');

                jQuery.ajax({
                    url       : '/administration/importPerson',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'query' : 'import'
                    },
                    success : function(data){
                        document.body.style.cursor = 'default';
                        console.log(data);
                        //console.log(data.message);
                        //var count  = Object.keys(data.objects).length;
                        var count = data;
                        console.log('Count : '+count)
                        //$Org_to_import_data = data.objects;
                        // Reset input value
                        toastr.success('{{ __('Datas imported from iTop') }}');
                        //('Some translatable string with :attribute',['attribute' => $attribute_var])
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Import nothing') }}');
                    },
                    complete : function(data){
                        $('#listitoppersons-table').DataTable().ajax.reload();
                        return data;
                    }
                });
            };

            //On vide la table itop_user pour un nouveau reimport
            function truncatePerson() {
                // quand on demande de confirmer, c dajà fait !! a revoir
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                document.body.style.cursor = 'wait';
                jQuery.ajax({
                    url       : '/administration/truncatePerson',
                    type      : 'post',
                    dataType : 'json',
                    data      :{

                    },
                    success : function(data){
                        document.body.style.cursor = 'default';
                        console.log(data);
                        //console.log(data.message);
                        //var count  = Object.keys(data.objects).length;
                        var count = data;
                        console.log('Count : '+count)
                        //$Org_to_import_data = data.objects;
                        // Reset input value
                        toastr.info('{{ __('Synchronized Datas deleted') }}');
                        //('Some translatable string with :attribute',['attribute' => $attribute_var])
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Delete nothing') }}');
                    },
                    complete : function(data){
                        $('#listitoppersons-table').DataTable().ajax.reload();
                        return data;

                    }
                });
            };

            //On selectionne toutes les lignes affichées
            function checkAll(){
                var table = $('#listitoppersons-table').DataTable();
                    table.rows().every( function () {
                    var row = this.data();
                    document.getElementById("customCheckbox"+row.id).checked = !document.getElementById("customCheckbox"+row.id).checked;
                    var idx = Aselected.indexOf(row.id);
                    // Add to the 'open' array
                    if (idx === -1) {Aselected.push(row.id);}
                    else {Aselected.splice(idx, 1);}
                } );
                console.log(Aselected);
            }

            //On créé les users sélectionnés !
            {{--function createUsers() {--}}
            {{--    $.ajaxSetup({--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        }--}}
            {{--    });--}}
            {{--    // quand on demande de confirmer, c dajà fait !! a revoir--}}
            {{--    document.body.style.cursor = 'wait';--}}
            {{--    toastr.info('{{ __('Creating account') }}');--}}

            {{--    jQuery.ajax({--}}
            {{--        url       : '/administration/ajxcreateusers',--}}
            {{--        type      : 'post',--}}
            {{--        dataType : 'json',--}}
            {{--        data      :{--}}
            {{--            'listId' : Aselected--}}
            {{--        },--}}
            {{--        success : function(data){--}}
            {{--            document.body.style.cursor = 'default';--}}
            {{--            console.log(data);--}}
            {{--            console.log(data.message);--}}
            {{--            // var count  = Object.keys(data.objects).length;--}}
            {{--            var count = data;--}}
            {{--            console.log('Count : '+count);--}}
            {{--            console.log(count);--}}
            {{--            //$Org_to_import_data = data.objects;--}}
            {{--            // Reset input value--}}
            {{--            toastr.success('{{ __('Account created') }}');--}}
            {{--            //('Some translatable string with :attribute',['attribute' => $attribute_var])--}}
            {{--        },--}}
            {{--        error :function(xhr, ajaxOptions, thrownError){--}}
            {{--            alert(xhr.status);--}}
            {{--            alert(thrownError);--}}
            {{--            toastr.warning('{{ __('Nothing was created') }}');--}}
            {{--        },--}}
            {{--        complete : function(data){--}}
            {{--            $('#listitoppersons-table').DataTable().ajax.reload();--}}
            {{--            Aselected.forEach((id) => {--}}
            {{--                //console.log(id);--}}
            {{--                if (document.getElementById("customCheckbox"+id)) {--}}
            {{--                    document.getElementById("customCheckbox"+id).checked = false;}--}}
            {{--            });--}}
            {{--            Aselected.length = 0; //on vide le tableau--}}
            {{--            return data;--}}
            {{--        }--}}
            {{--    });--}}
            {{--}--}}

            function createUsers() {
                if (Aselected.length === 0) {
                    toastr.warning("{{ __('No user selected') }}");
                    return;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                document.body.style.cursor = 'wait';
                toastr.info("{{ __('Creating account') }}");

                jQuery.ajax({
                    url: '/administration/ajxcreateusers',
                    type: 'post',
                    dataType: 'json',
                    data: { 'listId': Aselected },
                    success: function(response) {
                        document.body.style.cursor = 'default';
                        console.log(response);

                        if (response.success) {
                            toastr.success("{{ __('Account created') }}");
                        } else {
                            toastr.warning(response.message || "{{ __('Some accounts could not be created') }}");
                        }
                    },
                    error: function(xhr) {
                        document.body.style.cursor = 'default';
                        console.error(xhr);

                        let errorMessage = "{{ __('An error occurred') }}";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        $('#listitoppersons-table').DataTable().ajax.reload();

                        Aselected.forEach((id) => {
                            let checkbox = document.getElementById("customCheckbox" + id);
                            if (checkbox) {
                                checkbox.checked = false;
                            }
                        });

                        Aselected.length = 0; // Vide la sélection
                    }
                });
            }




            //On affiche la modal pour ajouter un user
            function newUser(){
                //On vide le formulaire
                document.getElementById("storeitopuser").reset();
                //On vide la liste déroulante
                document.getElementById("location").length = 0;
                // on vide les champs rempli et disable
                $("input[type=text]").val("");
                $("input[type=hidden]").val("");
                //On masque les flag d'information
                var infoCompl = document.getElementById("infoCompl");
                infoCompl.style.visibility  = 'hidden';

                $("#modal-new-itop-user").modal("show");
            }



            // On ouvre la modal pour mettre à jour les données d'un user
            function majUser(itop_user_id) {

                //alert('iTop Id : '+itop_user_id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url       : '/administration/edititopuser',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'itop_user_id' : itop_user_id
                    },
                    success : function(data){
                        console.log(data);
                        $('#id').val(data.itopuser.id);
                        $('#email').val(data.itopuser.email);
                        $('#first_name').val(data.itopuser.first_name);
                        $('#last_name').val(data.itopuser.last_name);
                        $('#phone').val(data.itopuser.phone);
                        // $('#organization').val(data.itopuser.org_id);
                        // //On peuple la liste à partir de l'organisation
                        // var selectLoc = document.getElementById("location");
                        // console.log('##list-itop-persons');
                        // console.log(data.Locations);
                        // for(index in data.Locations) {
                        //     //console.log(data.Locations[index]);
                        //     selectLoc.options[selectLoc.options.length] = new Option(data.Locations[index].name, data.Locations[index].id);
                        // }
                        // //et on pré-sélectionne le site défini
                        // selectLoc.value = data.itopuser.location_id;

                        let locations = (data.Locations || []).map(loc => ({
                            id: loc.id,
                            name: loc.name
                        }));
                        // Accéder au composant Vue et mettre à jour ses valeurs
                        if (window.vueInstance && window.vueInstance.$refs.cascadingDropdown) {
                            window.vueInstance.$refs.cascadingDropdown.updateValues(data.itopuser.org_id, data.itopuser.location_id, locations);
                        }
                        $("#role").val(data.itopuser.role_id);
                        $("#is_local").val(data.itopuser.is_local);
                        $("#is_in_itop").val(data.itopuser.is_in_itop);
                        $("#has_itop_account").val(data.itopuser.has_itop_account);

                        $('#phone').val(data.itopuser.phone);
                        $('#mobile').val(data.itopuser.mobile_phone);
                        $('#comment').val(data.itopuser.comment);
                        // $('#exampleModal form input[name=UserName]').val(result.UserName);
                        //En update, si contact existe sur iTop, on affiche l'ID
                        var infoCompl = document.getElementById("infoCompl");

                        //console.log(data.itopuser.itop_id.length);
                        if (data.itopuser.itop_id != null ){
                            console.log(data.itopuser);
                            infoCompl.style.visibility  = 'visible';
                            // document.getElementById("itop_id").value = data.itopuser.itop_id;
                            var flag = document.getElementById("flag");
                            flag.innerHTML = ('<dd class="col-sm-3"><label id="itop_id_label">{{__('iTop ID')}}</label>' +
                                ' <input disabled type="text" id="itop_id" class="form-control" value="'+data.itopuser.itop_id+'">' +
                                '</dd>');
                            flag.innerHTML += ('<dd class="col-sm-3"><label id="itop_id_label">{{__('Portal ID')}}</label>' +
                                ' <input disabled type="text" id="portal_id" class="form-control" value="'+data.itopuser.portal_id+'">' +
                                '</dd>');

                            var innerHTML;
                            innerHTML = '<div class="col-sm-6">';

                            if (data.itopuser.is_local == 0){
                                innerHTML += ('<dd class="col-sm-12"><label>{{__("Local account")}}</label>' +
                                    ' <i class="fas fa-minus-circle text-danger"></i>' +
                                    '</dd>');
                            } else {
                                innerHTML += ('<dd class="col-sm-12"><label>{{__("Local account")}}</label>' +
                                    ' <i class="fas fa-check text-success"></i>' +
                                    '</dd>');
                            }
                            if (data.itopuser.is_in_itop == 0){
                                innerHTML += ('<dd class="col-sm-12"><label>{{__("Contact in iTop")}}</label>' +
                                    ' <i class="fas fa-minus-circle text-danger"></i>' +
                                    '</dd>');
                            } else {
                                innerHTML += ('<dd class="col-sm-12"><label>{{__("Contact in iTop")}}</label>' +
                                    ' <i class="fas fa-check text-success"></i>' +
                                    '</dd>');
                            }
                            if (data.itopuser.has_itop_account == 0){
                                innerHTML += ('<dd class="col-sm-12"><label>{{__("iTop account")}}</label>' +
                                    ' <i class="fas fa-minus-circle text-danger"></i>' +
                                    '</dd>');
                            } else {
                                innerHTML += ('<dd class="col-sm-12"><label>{{__("iTop account")}}</label>' +
                                    '<i class="fas fa-check text-success"></i>' +
                                    '</dd>');
                            }
                            innerHTML += '</div>';
                            flag.innerHTML += innerHTML;

                        }
                        else {infoCompl.style.visibility  = 'hidden';
                            infoCompl.value = '';
                            console.log('none');}

                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Nothing was found') }}');
                    },
                    complete : function(data){
                        //$('#modal-new-itop-user').html(data.responseText);
                        //console.log(data.responseJSON);
                        $('#modal-new-itop-user').modal('show');

                    }
                });
            }


            // on efface des users
            function delUsers() {
               $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                jQuery.ajax({
                    url       : '/administration/deleteitopusers',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'listId' : Aselected
                    },
                    success : function(data){
                        toastr.warning(Aselected.length+' {{ __('record(s) deleted') }}');
                        console.log(data);
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Nothing was delete') }}');
                    },
                    complete : function(data){
                        Aselected.length = 0; //on vide le tableau
                        $('#listitoppersons-table').DataTable().ajax.reload();
                    }
                });
            };

            // on efface un user
            function delUser(itop_user_id) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                jQuery.ajax({
                    url       : '/administration/deleteitopuser',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'id' : itop_user_id
                    },
                    success : function(data){
                        toastr.warning('{{ __('Data deleted') }}');
                        console.log(data);
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Nothing was delete') }}');
                    },
                    complete : function(data){

                        $('#listitoppersons-table').DataTable().ajax.reload();
                    }
                });
            };


            // on efface des users
            function notifyUsers() {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                jQuery.ajax({
                    url       : '/administration/notifyitopusers',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'listId' : Aselected
                    },
                    success : function(data){
                        toastr.warning(data.length+' {{ __('User(s) notified') }}');
                        console.log(data);
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Nobody was notified') }}');
                    },
                    complete : function(data){
                        Aselected.length = 0; //on vide le tableau
                        $('#listitoppersons-table').DataTable().ajax.reload();
                    }
                });
            };

        </script>
{{--    @endif--}}

    {{ $dataTable->scripts() }}






@endsection
