@extends('adminlte::page')

@section('css')
     <style>
        a > * { pointer-events: none; }
    </style>
@endsection

@section('content_header')
    <h1> <i class="fa-solid fa-location-dot"></i> {{ __('Locations from iTop') }}</h1>
@endsection

@section('content')

    {{-- Modal d'édition --}}
    @include('backend.itop.edit-loc')



    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            {{ __('List of Organizations from iTop\'s import') }}</h3>
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


            // Écouteur d'événements pour l'erreur
            $('#listlocations-table').on('error.dt', function(e, settings, techNote, message) {
                console.error('Erreur DataTable : ', message);
            });

            $('#listlocations-table').on('stateLoadParams.dt', function() {
                console.log('event')
            })

            $('#listlocations-table').on('click', 'tbody tr', function () {
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

            $('#listlocations-table').on('click', 'input[type="checkbox"]', function () {
                console.log('click checkbox');
                var tr = $(this).closest("tr");
                var row = $("#listlocations-table").DataTable().rows(tr);

                var tr = $(this).closest('tr');
                var row = $("#listlocations-table").DataTable().rows(tr).data()[0];
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


        //On importe tous les contacts Itop actifs
        function importLoc() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // quand on demande de confirmer, c dajà fait !! a revoir
            document.body.style.cursor = 'wait';
            toastr.info('{{ __('Importing datas from iTop') }}');

            jQuery.ajax({
                url       : '/administration/importLoc',
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
                    $('#listlocations-table').DataTable().ajax.reload();
                    return data;
                }
            });
        };

        //On selectionne toutes les lignes affichées
        function checkAll(){
            var table = $('#listlocations-table').DataTable();
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

        // on efface des organisations (sélectionnées par case à cocher)
        function delLocs() {
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            jQuery.ajax({
                url       : '/administration/deleteitoplocs',
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
                    $('#listlocations-table').DataTable().ajax.reload();
                }
            });
        };

        // on efface une organisation
        function delLoc(itop_loc_id) {
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            jQuery.ajax({
                url       : '/administration/deleteitoploc',
                type      : 'post',
                dataType : 'json',
                data      :{
                    'id' : itop_loc_id
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
                    $('#listlocations-table').DataTable().ajax.reload();
                }
            });
        };

        // On ouvre la modal pour mettre à jour les données d'un user
        function editLoc(itop_loc_id) {
            //alert('iTop Id : '+itop_user_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url       : '/administration/edititoploc',
                type      : 'post',
                dataType : 'json',
                data      :{
                    'itop_loc_id' : itop_loc_id
                },
                success : function(data){
                    console.log(data);
                    $('#id').val(data.itoploc.id);
                    $('#name').val(data.itoploc.name);
                    $('#org_name').val(data.itoploc.organization_name);
                    $('#phonecode').val(data.itoploc.phonecode);
                    $('#address').val(data.itoploc.address);
                    $('#postal_code').val(data.itoploc.postal_code);
                    $('#city').val(data.itoploc.city);
                    $('#country').val(data.itoploc.country);
                    $('#latitude').val(data.itoploc.latitude);
                    $('#longitude').val(data.itoploc.longitude);
                    $('#deliverymodel_id').val(data.itoploc.deliverymodel_id);
                    $('#deliverymodel_id_friendlyname').val(data.itoploc.deliverymodel_id_friendlyname);

// Gestion des checkboxes
                    $('#is_active').prop('checked', data.itoploc.is_active == 1);
                    $('#is_localized').prop('checked', data.itoploc.is_localized == 1);
                    // $('#deliverymodel_id_friendlyname').val(data.itoploc.deliverymodel_id_friendlyname);
                },
                error :function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                    toastr.warning('{{ __('Nothing was found') }}');
                },
                complete : function(data){
                    //$('#modal-new-itop-user').html(data.responseText);
                    //console.log(data.responseJSON);
                    $('#modal-edit-loc').modal('show');

                }
            });
        }

    </script>
    {{--    @endif--}}

    {{ $dataTable->scripts() }}

@endsection


@section('footer')
    &nbsp;
@endsection

