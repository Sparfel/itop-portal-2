@extends('adminlte::page')

@section('css')
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
@endsection

@section('content_header')
    <h1><i class="fas fa-lock"></i>{{ __('Your Closed Requests') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-dark card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            {{ __('Closed Requests') }}
                        </h3>
                        <div class="float-right">
                            <x-front.filter-preference
                                idModal="filterRequestDatatable"
                                labelBtn="Filters"
                                requestFilter=true
                                usrClosedRqt="true">
                            </x-front.filter-preference>
                        </div>
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

    @if(config('app.locale') == 'fr')
        <script>
            (($, DataTable) => {
                $.extend(true, DataTable.defaults, {
                    processing: true,
                    serverSide: true,

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
                            }
                        }
                    }
                });
            })(jQuery, jQuery.fn.dataTable);
        </script>
    @endif

    {{ $dataTable->scripts() }}

@endsection
