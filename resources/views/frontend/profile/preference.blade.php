

{{--<div class="card card-outline card-primary">--}}
{{--    <div class="card-header">--}}
{{--        <h3 class="card-title">{{__('Data visibility')}}</h3>--}}
{{--    </div>--}}
{{--    <form role="form" method="POST" >--}}
        <div class="callout callout-secondary">
            <div class="card-body">
                <h5>{{__('Requests')}}</h5>
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <x-front.tab-panel-input
                            label="See only my opened requests"
                            name='userFilter'
                            :value="$pref->userFilter"
                            input='checkbox' >
                        </x-front.tab-panel-input>
                    </div>
                    <div class="col-sm-6">
                        <x-front.tab-panel-input
                            label="See only my closed requests"
                            name='userFilterClosedRequest'
                            :value="$pref->userFilterClosedRequest"
                            input='checkbox'>
                        </x-front.tab-panel-input>
                    </div>
    {{--                <div class="col-sm-4">--}}
    {{--                    <div class="row">--}}
    {{--                        <div class="col-sm-6"><label>{{__('Change the language')}}</label></div>--}}
    {{--                        <div class="col-sm-6">@include('adminlte::partials.navbar.language')</div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <x-front.tab-panel-input
                            title="{{__('Prefered sites for requests')}}"
                            name='{{$pref->paramName_RequestUserLocation}}'
{{--                            :options="$pref->locations_list"--}}
                            :options="$pref->getOrgLocationFilter($pref->paramName_Location)"
                            input='selectMultiple_has'
                            :values="$pref->getOrgLocationFilter($pref->paramName_RequestUserLocation)"
                            :required="false">
                        </x-front.tab-panel-input>
                    </div>
                </div>


            </div>
{{--        <div class="card-footer">--}}
{{--            <button type="submit" class="btn btn-primary float-right">Valider</button>--}}
{{--        </div>--}}
        </div>

        <div class="callout callout-secondary">
            <div class="card-body">
                <h5>{{__('Statistics')}}</h5>
                <div class="row">
                    <div class="col-sm-12">
                        <x-front.tab-panel-input
                            title="{{__('Prefered sites for statistics in time')}}"
                            name='{{$pref->paramName_StatUserLocation}}'
{{--                            :options="$pref->locations_list"--}}
                            :options="$pref->getOrgLocationFilter($pref->paramName_Location)"
                            input='selectMultiple_has'
{{--                            :values="$pref->getStatUserLocationFilter()"--}}
                            :values="$pref->getOrgLocationFilter($pref->paramName_StatUserLocation)"
                            :required="false">
                        </x-front.tab-panel-input>
                    </div>
                </div>

                @php
                    $config = [
                        "showDropdowns" => true,
                        "startDate" => "{{$stat_start}}",
                        "endDate" =>  "{{$stat_end}}",
                        "minYear" => 2011,
                        "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                        "timePicker" => false,
                        "timePicker24Hour" => true,
                        "timePickerIncrement" => 30,
                        "locale" => ["format" => "DD/MM/YYYY"],
                        "opens" => "center"
                    ];
                @endphp
                <x-adminlte-date-range name="datefilter" label="Date/Time Range" :config="$config">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                    <x-slot name="appendSlot">
                        <x-adminlte-button  id="initBtn" label="Init" icon="far fa-trash-alt"/>
                    </x-slot>
                </x-adminlte-date-range>

            </div>
        </div>

@push('js')
    <script>
       $('#initBtn').on('click', function (e) {
            $("#datefilter").data('daterangepicker').setStartDate('01/01/2010');
            $("#datefilter").data('daterangepicker').setEndDate('01/01/2010');
            $("#datefilter").val('');


        });
    </script>

@endpush
