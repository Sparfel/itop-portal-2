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

                <communication title="Communication"></communication>

{{--                @can('list_requests')--}}
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
{{--                @endcan--}}

            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">
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
{{--                @include('frontend.home._widget')--}}
                @include('frontend.home._info-box')
                <bar-chart
                    title = "{{__('Tickets the last 12 months')}}"
                    labels= "{{ json_encode($bar_labels->getData()) }}"
                    data-prop="{{ json_encode($bar_datas->getData()) }}">
                </bar-chart>

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->


    <!-- /.card-body -->
@endsection
@section('footer')
    &nbsp;
@endsection
