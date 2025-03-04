@extends('adminlte::page')

@section('css')
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
@endsection

@section('content_header')
    <h1> <i class="fas fa-users"></i> {{ __('Portal Accounts') }}</h1>
@endsection

@section('content')


{{-- Modal d'ajout d'un compte --}}
@include('backend.users.maj-user')
@include('backend.users.del-users')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            {{ __('List of Portal accounts') }}</h3>
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
            console.log('on est dans la place');
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


                $('#listusers-table').on('stateLoadParams.dt', function() {
                    console.log('event')
                    $('#deleteModal').modal('show');
                })

                $('#listusers-table').on('click', 'tbody tr', function () {
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

                $('#listusers-table').on('click', 'input[type="checkbox"]', function () {
                    console.log('click checkbox');
                    var tr = $(this).closest("tr");
                    var row = $("#listusers-table").DataTable().rows(tr);

                    var tr = $(this).closest('tr');
                    var row = $("#listusers-table").DataTable().rows(tr).data()[0];
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

                $('#listusers-table').on('click', '.active', function () {

                    var tr = $(this).closest('tr');
                    //console.log(tr);
                    //var row = $("#listusers-table").DataTable().rows(tr).data()[0];
                    console.log($("#listusers-table").DataTable().rows(tr).data());
                    // var idx = Aselected.indexOf(row.id);
                    // alert(idx);

                });

              })(jQuery, jQuery.fn.dataTable);




            //Script globaux à la grille, appelé à partir des bouton du Datatable
            //On importe tous les contacts Itop actifs


            //On selectionne toutes les lignes affichées
            function checkAll(){
                var table = $('#listusers-table').DataTable();
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



            // On ouvre la modal pour mettre à jour les données d'un user
            function majUser(id) {
                //on vide la liste des sites, on la repeuple après

                //alert('iTop Id : '+itop_user_id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url       : '/administration/edituser',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'id' : id
                    },
                    success : function(data){
                        console.log(data);
                        //Settings
                        $('#id').val(data.user.id);
                        $('#email').val(data.user.email);
                        $('#first_name').val(data.user.first_name);
                        $('#last_name').val(data.user.last_name);
                        $("#gender").val(data.user.gender);
                        if (data.user.domain === null) {
                            $("#password").prop('disabled', false);
                            document.getElementById('passwordLabel').innerHTML = '';}
                        else {$("#password").prop('disabled', true);
                            document.getElementById('passwordLabel').innerHTML = '<i class="fas fa-lock text-primary"></i> [AD] ';
                        }
                        $('#password').val(data.user.password);
                        $('#portal_id').val(data.user.id);
                        $('#itop_id').val(data.user.itop_id);
                        // $("#role").val(data.user.role_id);
                        let roles = data.user.roles.map(role => role.id); // Récupère tous les ID des rôles du user
                        $("#role").val(roles).trigger("change"); // Sélectionne les rôles dans le SELECT

                        $("#guid").val(data.user.guid);
                        $("#domain").val(data.user.domain);
                        $("#pc1").val(data.user.pc1);
                        $("#pc2").val(data.user.pc2);
                        $("#pc3").val(data.user.pc3);
                        if (data.user.is_active === 1) {document.getElementById('is_active').checked = true;}
                        else {document.getElementById('is_active').checked = false;}
                        if (data.user.is_staff == 1) {document.getElementById('is_staff').checked = true;}
                        else {document.getElementById('is_staff').checked = false;}
                        if (data.user.is_multi_organization == 1) {document.getElementById('is_multi_organization').checked = true;}
                        else {document.getElementById('is_multi_organization').checked = false;}
                        $('#itop_cfg').val(data.user.itop_cfg);

                        //Professiona Datas
                        $('#department').val(data.user.department);
                        $("#department").prop('disabled', true);
                        $('#service').val(data.user.service);
                        $("#service").prop('disabled', true);
                        $('#internal_phone').val(data.user.internal_phone);
                        $("#office_1").val(data.user.office_id);
                        $('#internal_phone_2').val(data.user.internal_phone_2);
                        $("#office_2").val(data.user.office_id_2);
                        $('#mobile').val(data.user.mobile);
                        $('#dect_phone').val(data.user.dect_phone);
                        $('#sda_phone').val(data.user.sda_phone);
                        $('#about').val(data.user.about);

                        //Personal Datas
                        $('#address').val(data.user.address);
                        $("#postal_code").val(data.user.postal_code);
                        $('#city').val(data.user.city);
                        $("#country").val(data.user.country);
                        if (data.user.is_localized == 1) {document.getElementById('is_localized').checked = true;}
                        else {document.getElementById('is_localized').checked = false;}
                        if (data.user.is_address_visible == 1) {document.getElementById('is_address_visible').checked = true;}
                        else {document.getElementById('is_address_visible').checked = false;}
                        $('#longitude').val(data.user.longitude);
                        $("#latitude").val(data.user.latitude);
                        //Picture
                        // document.getElementById("myAvatar").src="/storage/"+data.user.avatar;
                        document.getElementById("myAvatar").src="/storage/users/default.png";

                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        toastr.warning('{{ __('Nothing was found') }}');
                    },
                    complete : function(data){
                        //$('#modal-new-itop-user').html(data.responseText);
                        //console.log(data.responseJSON);
                        //console.log(data);
                        $('#modal-maj-user').modal('show');

                    }
                });
            }

            // on efface des users
            function delUsers(){
                if ( Aselected.length > 0) {
                    document.getElementById("nbDelete").innerHTML = Aselected.length + ' compte(s) vont être supprimé(s).'
                    $('#modal-del-users').modal('show');
                }
                else {toastr.warning('{{ __('Nothing was selected') }}');}

            }

            function deleteUsers() {
               $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                jQuery.ajax({
                    url       : '/administration/deleteusers',
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
                        $('#listusers-table').DataTable().ajax.reload();
                    }
                });
            };

            // on efface un user
            function delUser(user_id) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                jQuery.ajax({
                    url       : '/administration/deleteuser',
                    type      : 'post',
                    dataType : 'json',
                    data      :{
                        'id' : user_id
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

                        $('#listusers-table').DataTable().ajax.reload();
                    }
                });
            };




        </script>
{{--    @endif--}}

    {{ $dataTable->scripts() }}






@endsection
