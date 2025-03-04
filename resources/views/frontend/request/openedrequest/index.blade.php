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
            // Récupération des informations sur le type de requête
            $type = request('request_type', 'all');
            $typeIcon = $type !== 'all' ? getTypeIcon($type) : '';
            $typeClass = $type !== 'all' ? getTypeColor($type, true) : 'bg-info';
            $typeLabel = $type !== 'all' ? __($type === '' ? 'Undefined' : ucfirst($type)) : 'Tous';

            // Récupération des informations sur la priorité
            $priority = request('priority', 'all');
            $priorityIcon = $priority !== 'all' ? getPriorityIcon((int) $priority) : 'fa fa-filter';
            $priorityClass = $priority !== 'all' ? getPriorityColor((int) $priority, true) : 'bg-secondary';
            $priorityLabel = $priority !== 'all' ? __(['1' => 'Critical', '2' => 'High', '3' => 'Medium', '4' => 'Low'][(int) $priority] ?? 'Aucune') : 'Aucune';

            // Récupération des informations sur le statut
            $status = request('status', 'all');
            $statusIcon = $status !== 'all' ? getStatusIcon($status) : '';
            $statusClass = $status !== 'all' ? getStatusColor($status, true) : 'bg-info';
            $statusLabel = $status !== 'all' ? ucfirst($status) : 'Tous';
        @endphp

            <!-- Affichage du type de requête si sélectionné -->
        @if($type !== 'all')
            <a href="{{ route('openedrequest.filter', ['priority' => request('priority', 'all'), 'status' => request('status', 'all'), 'request_type' => 'all']) }}">
        <span class="badge {{ $typeClass }}">
            <i class="{{ $typeIcon }}"></i> {{ $typeLabel }} &times;
        </span>
            </a>&nbsp;
        @endif

        <!-- Affichage de la priorité si sélectionnée -->
        @if($priority !== 'all')
            <a href="{{ route('openedrequest.filter', ['priority' => 'all', 'status' => request('status', 'all'), 'request_type' => request('request_type', 'all')]) }}">
        <span class="badge {{ $priorityClass }}">
            <i class="{{ $priorityIcon }}"></i> {{ $priorityLabel }} &times;
        </span>
            </a>&nbsp;
        @endif

        <!-- Affichage du statut si sélectionné -->
        @if($status !== 'all')
            <a href="{{ route('openedrequest.filter', ['priority' => request('priority', 'all'), 'request_type' => request('request_type', 'all'), 'status' => 'all']) }}">
        <span class="badge {{ $statusClass }}">
            <i class="{{ $statusIcon }}"></i> {{ $statusLabel }} &times;
        </span>
            </a>&nbsp;
        @endif

    </h1>
@endsection

@section('content')

    <section class="content">
        <div class="container-fluid text-sm">
            <div class="row">
                <!-- Datatable à gauche (col-md-9) -->
                <div class="col-md-9">
                    @include('frontend.request.openedrequest._type-ticket')
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i>
                                {{ __('Opened Requests') }}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            {{ $dataTable->table(['class' => 'table table-bordered table-hover table-sm'], true) }}
                        </div>
                    </div>
                </div>
                <!-- Statistiques à droite (col-md-3) -->
                <div class="col-md-3">
                    @include('frontend.request.openedrequest._status-widget')
                    @include('frontend.request.openedrequest._priority-ticket')
                </div>
            </div>
        </div>
    </section>

@endsection

@section('footer')
    &nbsp;
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
