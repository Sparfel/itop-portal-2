{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1><i class="fa-solid fa-chart-line"></i> {{__('Dashboard')}}</h1>
@endsection

@section('content')


    <div class="container-fluid">




        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">



                @can('list_requests')
                    <!-- Request List -->
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clipboard-list "></i>
                                {{__('Opened Requests')}}</h3>
                            <div class="card-tools">
{{--                                Filtres--}}
{{--                                <x-front.filter-preference--}}
{{--                                    idModal="filterRequestDatatable"--}}
{{--                                    labelBtn="Filter Requests"--}}
{{--                                    requestFilter=true--}}
{{--                                    usrOpenedRqt="true"--}}
{{--                                >--}}
{{--                                </x-front.filter-preference>--}}
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" >
{{--                            <ticket-list--}}
{{--                                buttons-pagination--}}
{{--                                alternating--}}
{{--                                :headers='@json($tableHeaders)'--}}
{{--                                :items='@json($tableDatas)'--}}
{{--                                :theme-color="'#EA7D1E'"--}}
{{--                                multi-sort>--}}

{{--                            </ticket-list>--}}

{{--                            <datatable-component--}}
{{--                                :headers="{{ json_encode($tableHeaders1) }}"--}}
{{--                                :data="{{ json_encode($tableDatas1) }}">--}}
{{--                            </datatable-component>--}}



                        <ticket-list2

{{--                            :headers='@json($tableHeaders)'--}}
{{--                            :items='@json($tableDatas)'--}}

{{--                            alternating--}}
{{--                            multi-sort--}}
                        ></ticket-list2>


                    </div>


                        <!-- /.card-body -->
                    </div>
                    <!--/.Request List -->
                @endcan

            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">
{{--                @include('frontend.home._widget')--}}
                @include('frontend.home._info-box')


                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa-brands fa-uncharted"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{$iTop->name}}</span>
                        <span class="info-box-number">{{$iTop->version}}</span>
                        {{--                    <div class="progress">--}}
                        {{--                        <div class="progress-bar bg-info" style="width: 70%"></div>--}}
                        {{--                    </div>--}}
                        <span class="progress-description">
                        {{$iTop->installed}}
                    </span>
                    </div>
                </div>


                <bar-chart
                    title = "{{__('Tickets the last 12 months')}}"
                    labels= "{{ json_encode($bar_labels->getData()) }}"
                    data-prop="{{ json_encode($bar_datas->getData()) }}">
                </bar-chart>


                <ul>
                    <li><i class="fa-solid fa-regular fa-comment-dots text-danger" title="New"></i> New</li>
                    <li><i class="fa fa-hourglass-start text-warning"></i> Escalated TTO</li>
                    <li><i class="fa-solid fa-user-check text-info" title="Assigned"></i> Assigned</li>
                    <li><i class="fa fa-hourglass-end text-danger"></i> Escalated TTR</li>
                    <li><i class="fa fa-clock text-info"></i> Waiting for Approval</li>
                    <li><i class="fa fa-times-circle text-danger"></i> Reject</li>
                    <li><i class="fa fa-check-circle text-success"></i> Approved</li>
                    <li><i class="fa-solid fa-hourglass-half text-warning " title="Pending"></i> Pending</li>
                    <li><i class="fa-regular fa-thumbs-up text-success" title="Resolved"></i> Resolved</li>
                    <li><i class="fa fa-lock text-secondary" title="Closed"></i> Closed</li>
                </ul>


{{--                <test-component></test-component>--}}

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->



{{--    <div class="card card-primary card-outline">--}}
{{--        <div class="card-header">--}}
{{--            <h4 class="m-0"><i class="fa-light fa-circle-info"></i> {{__('Informations')}}</h4>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            <div class="row d-flex align-items-stretch">--}}
{{--                <div class="col-md-4 col-sm-6 col-12">--}}
{{--                    <div class="info-box">--}}
{{--                        <span class="info-box-icon bg-info"><i class="fa-light fa-circle-nodes"></i></span>--}}
{{--                        <div class="info-box-content">--}}
{{--                            <span class="info-box-text">{{$iTop->name}}</span>--}}
{{--                            <span class="info-box-number">{{$iTop->version}}</span>--}}
{{--                            --}}{{--                    <div class="progress">--}}
{{--                            --}}{{--                        <div class="progress-bar bg-info" style="width: 70%"></div>--}}
{{--                            --}}{{--                    </div>--}}
{{--                            <span class="progress-description">--}}
{{--                        {{$iTop->installed}}--}}
{{--                    </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}



{{--                @if (session('status'))--}}
{{--                    <div class="alert alert-success" role="alert">--}}
{{--                        {{ session('status') }}--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                {{ __('You are logged in!') }}--}}
{{--                <br>--}}
{{--                    App::currentLocale() => {{ App::currentLocale()}}--}}
{{--                <br>--}}
{{--                    App::getLocale() => {{App::getLocale()}}--}}
{{--                <br>--}}
{{--                    Session::get('locale') => {{Session::get('locale')}}--}}
{{--                <br>--}}
{{--                Préference => {{get_user_preference(Auth::user()->id,'locale')}}--}}
{{--            </div>--}}
{{--            <div class="row d-flex align-items-stretch">--}}
{{--                <div class="col-md-4 col-sm-6 col-12">--}}
{{--                    hello !--}}
{{--                    <test-component></test-component>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--           <div class="row d-flex align-items-stretch">--}}
{{--                <div class="col-md-12 col-sm-12 col-12">--}}
{{--                    Datatable--}}
{{--                    <datatable-component--}}
{{--                        :headers="{{ json_encode($tableHeaders) }}"--}}
{{--                        :data="{{ json_encode($tableData) }}">--}}
{{--                    </datatable-component>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row d-flex align-items-stretch">--}}
{{--                <div class="col-md-12 col-sm-12 col-12">--}}


{{--                    @php--}}
{{--                        $headers = [--}}
{{--                            ["text" => "PLAYER", "value" => "player"],--}}
{{--                            ["text" => "TEAM", "value" => "team"],--}}
{{--                            ["text" => "NUMBER", "value" => "number"],--}}
{{--                            ["text" => "POSITION", "value" => "position"],--}}
{{--                            ["text" => "HEIGHT", "value" => "indicator.height"],--}}
{{--                            ["text" => "WEIGHT (lbs)", "value" => "indicator.weight", "sortable" => true],--}}
{{--                            ["text" => "LAST ATTENDED", "value" => "lastAttended", "width" => 200],--}}
{{--                            ["text" => "COUNTRY", "value" => "country"],--}}
{{--                        ];--}}

{{--                        $items = [--}}
{{--                            [--}}
{{--                                "player" => "Stephen Curry",--}}
{{--                                "team" => "GSW",--}}
{{--                                "number" => 30,--}}
{{--                                "position" => 'G',--}}
{{--                                "indicator" => ["height" => '6-2', "weight" => 185],--}}
{{--                                "lastAttended" => "Davidson",--}}
{{--                                "country" => "USA"--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                "player" => "Lebron James",--}}
{{--                                "team" => "LAL",--}}
{{--                                "number" => 6,--}}
{{--                                "position" => 'F',--}}
{{--                                "indicator" => ["height" => '6-9', "weight" => 250],--}}
{{--                                "lastAttended" => "St. Vincent-St. Mary HS (OH)",--}}
{{--                                "country" => "USA"--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                "player" => "Kevin Durant",--}}
{{--                                "team" => "BKN",--}}
{{--                                "number" => 7,--}}
{{--                                "position" => 'F',--}}
{{--                                "indicator" => ["height" => '6-10', "weight" => 240],--}}
{{--                                "lastAttended" => "Texas-Austin",--}}
{{--                                "country" => "USA"--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                "player" => "Giannis Antetokounmpo",--}}
{{--                                "team" => "MIL",--}}
{{--                                "number" => 34,--}}
{{--                                "position" => 'F',--}}
{{--                                "indicator" => ["height" => '6-11', "weight" => 242],--}}
{{--                                "lastAttended" => "Filathlitikos",--}}
{{--                                "country" => "Greece"--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                "player" => "Emmanuel Lozachmeur",--}}
{{--                                "team" => "Fives",--}}
{{--                                "number" => 23,--}}
{{--                                "position" => 'F',--}}
{{--                                "indicator" => ["height" => '83', "weight" => 180],--}}
{{--                                "lastAttended" => "Syleps",--}}
{{--                                "country" => "France"--}}
{{--                            ],--}}
{{--                        ];--}}
{{--                    @endphp--}}

{{--                        <!-- Utilisation de v-bind pour passer les données JSON correctement -->--}}
{{--                    <vue-datatable--}}
{{--                        :headers='@json($headers)'--}}
{{--                        :items='@json($items)'--}}
{{--                        alternating--}}

{{--                        multi-sort>--}}
{{--                    </vue-datatable>--}}
{{--                </div>--}}
{{--            </div>--}}


{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <h3 class="card-title">Incidents en cours</h3>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="table-responsive">--}}
{{--                        <table class="table table-bordered table-striped">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('Reference') }}</th>--}}
{{--                                <th>{{ __('Organization') }}</th>--}}
{{--                                <th>{{ __('Caller') }}</th>--}}
{{--                                <th>{{ __('Agent') }}</th>--}}
{{--                                <th>{{ __('Title') }}</th>--}}
{{--                                <th>{{ __('Status') }}</th>--}}
{{--                                <th>{{ __('Description') }}</th>--}}
{{--                                <th>{{ __('Start Date') }}</th>--}}

{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($test as $info)--}}
{{--                                <tr>--}}
{{--                                    <td>{{$info->ref}}</td>--}}
{{--                                    <td>{{$info->org_name}}</td>--}}
{{--                                    <td>{{$info->caller_name}}</td>--}}
{{--                                    <td>{{$info->agent_name}}</td>--}}
{{--                                    <td>{{$info->title}}</td>--}}
{{--                                    <td>{{$info->status}}</td>--}}
{{--                                    <td>{!! $info->description !!}</td>--}}
{{--                                    <td>{{$info->start_date}}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            <!-- Repeat similar rows for other incidents -->--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}




{{--    </div>--}}


    <!-- /.card-body -->
@endsection
