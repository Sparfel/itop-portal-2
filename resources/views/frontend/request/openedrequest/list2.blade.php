@extends('adminlte::page')

@section('css')
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">--}}
    <style>
        a > * { pointer-events: none; }
    </style>
@endsection

@section('content_header')
    <h1> <i class="fas fa-clipboard-list"></i> {{ __('Your Opened Requests') }}

        @php
            $type = request('request_type', 'all');
            $typeIcon = '';
            $typeLabel = 'Tous'; // Valeur par défaut
            $typeClass = 'badge-info'; // Couleur par défaut

            if ($type == 'incident') {
                $typeLabel = __('Incident');;
                $typeIcon = '<i class="fa fa-life-ring"></i>';
            } elseif ($type == 'service_request') {
                $typeLabel = __('Service request');
                $typeIcon = '<i class="fa fa-handshake"></i>';
            } elseif ($type == 'undefined') {
                $typeLabel = __('Undefined');
                $typeIcon = '<i class="fa fa-ellipsis-h"></i>';
            }
        @endphp

        @if($type != 'all') <!-- Si un type est sélectionné -->
        <a href="{{ route('openedrequest.filter', [
                                                            'priority' => request('priority', 'all'),
                                                            'request_type' => 'all'
                                                        ]) }}">
            <span class="badge {{ $typeClass }}"> {!! $typeIcon !!} {{ $typeLabel }}</span>
        </a>
        @endif

        @php
            $priority = request('priority', 'Aucune');
            $priorityClass = 'badge-secondary'; // Valeur par défaut
            $priorityLabel = 'Aucune'; // Valeur par défaut
            $priorityIcon = 'fa fa-filter'; // Valeur par défaut pour l'icône

            if ($priority == 1) {
                $priorityLabel = __('Critical');
                $priorityClass = 'badge-danger';
                $priorityIcon = 'fa-solid fa-fire'; // Icône pour la priorité critique
            } elseif ($priority == 2) {
                $priorityLabel = __('High');
                $priorityClass = 'badge-warning';
                $priorityIcon = 'fa-solid fa-triangle-exclamation'; // Icône pour la haute priorité
            } elseif ($priority == 3) {
                $priorityLabel =__('Medium');
                $priorityClass = 'badge-info';
                $priorityIcon = 'fa-solid fa-circle-info'; // Icône pour la priorité moyenne
            } elseif ($priority == 4) {
                $priorityLabel = __('Low');
                $priorityClass = 'badge-secondary';
                $priorityIcon = 'fa-solid fa-check-circle'; // Icône pour la faible priorité
            }
        @endphp
        &nbsp;
        @if ($priorityLabel != 'Aucune')
            <a href="{{ route('openedrequest.filter', [
        'priority' => 'all',
        'request_type' => request('request_type', 'all')
    ]) }}">
        <span class="badge {{ $priorityClass }}">
            <i class="{{ $priorityIcon }}"></i> {{ $priorityLabel }}
        </span>
            </a>
        @endif

    </h1>
@endsection

@section('content')

    <section class="content">
        <div class="container-fluid text-sm">
            <div class="row">
                <div class="col-md-3">

{{--                   @include('frontend.request.openedrequest._manage-ticket')--}}
                   @include('frontend.request.openedrequest._type-ticket')
                   @include('frontend.request.openedrequest._stats-ticket')
                </div>

                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i>
                                {{ __('Opened Requests') }}
                            </h3>
                            <div class="float-right">
                                <x-front.filter-preference
                                    idModal="filterRequestDatatable"
                                    labelBtn="Filters"
                                    requestFilter=true
                                    usrOpenedRqt="true">
                                </x-front.filter-preference>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            {{ $dataTable->table(['class' => 'table table-bordered table-hover table-sm'], true) }}
                        </div>
                </div>

                <!-- /.col -->
            </div>
        </div>
    </section>




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
                            },

                        }
                    }

                });
            })(jQuery, jQuery.fn.dataTable);
        </script>
    @endif

    {{ $dataTable->scripts() }}



@endsection
