@props([
'idModal' => 'filter',
'labelBtn' => 'my button',
'requestFilter' => false,
'statFilter' => false,
'usrClosedRqt'=>false,
'usrOpenedRqt'=>false,
'statFilterDate'=> false
])

@php
    $pref = session('preferences');
    $user_filter = $pref->getUserFilter();
    $user_filter_closed_request = $pref->getUserFilterClosedRequest();
    $locations = $pref->getOrgLocationFilter($pref->paramName_Location)->toArray();
    $locations_filter = $pref->getOrgLocationFilter($pref->paramName_RequestUserLocation)->toArray();
    $stat_locations_filter = $pref->getOrgLocationFilter($pref->paramName_StatUserLocation)->toArray();
    //Récupération des dates pour les statisitiques
    $statDate = $pref->getStatInTimePref2();
    $stat_start = $statDate->get('start_date');
    $stat_end = $statDate->get('end_date');
    $filter_date = $statDate->get('filtred');

    // Colorisation du logo filtre
    $color = false;
    // test si on  filtre les sites pour les tickets
    if ($requestFilter =='true' && count($locations)!=count($locations_filter)) { $color = true; }
    if ($statFilter =='true' && count($locations)!=count($stat_locations_filter)) { $color = true; }
    if ($usrOpenedRqt =='true' && $user_filter) { $color = true; }
    if ($usrClosedRqt =='true' && $user_filter_closed_request) { $color = true; }
    if ($statFilterDate =='true' && $filter_date) { $color = true; }

@endphp


<button type="button" class="btn btn-default" data-toggle="modal" data-target="#{{$idModal}}">
    <i class="fas fa-filter @if ($color) text-danger @endif"></i> {{$labelBtn}}
</button>

<!-- modal -->
<div class="modal fade" id="{{$idModal}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Filters')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/changelocationfilter" method="post" id="changeFilter" name="changeFilter" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($requestFilter == "true")
                        <label>
                            {{__('Locations list')}}
                        </label>
                        <select name="locations[]" id="{{$pref->paramName_RequestUserLocation}}{{$idModal}}" class="select2bs4" multiple="multiple" data-placeholder="{{__('Site')}}"
                                style="width: 100%;">
                            @foreach($locations as $id=>$location)
                                <option @if (array_key_exists($id,$locations_filter)) SELECTED @endif value="{{$id}}"> {{$location}}</option>
                            @endforeach
                        </select>
                        <br>
                    @endif

                    @if($statFilter == "true")

                        <label>
                            {{__('Locations list for Statistics')}}
                        </label>
                        <select name="locations[]" id="{{$pref->paramName_StatUserLocation}}{{$idModal}}" class="select2bs4" multiple="multiple" data-placeholder="{{__('Site')}}"
                                style="width: 100%;">
                            @foreach($locations as $id=>$location)
                                <option @if (array_key_exists($id,$stat_locations_filter)) SELECTED @endif value="{{$id}}"> {{$location}}</option>
                            @endforeach
                        </select>
                    @endif

                    @if($usrOpenedRqt == "true")
                        <x-front.tab-panel-input
                            label="See only my opened requests"
                            name='{{$pref->paramName_UserFilter}}{{$idModal}}'
                            id="{{$pref->paramName_UserFilter}}{{$idModal}}"
                            :value="$user_filter"
                            input='checkbox' >
                        </x-front.tab-panel-input>
                        <br>
                    @endif

                    @if($usrClosedRqt == "true")
                       <x-front.tab-panel-input
                            label="See only my closed requests"
                            name='{{$pref->paramName_UserFilterClosedRequest}}{{$idModal}}'
                            id="{{$pref->paramName_UserFilterClosedRequest}}{{$idModal}}"
                            :value="$user_filter_closed_request"
                            input='checkbox' >
                        </x-front.tab-panel-input>
                        <br>
                    @endif

{{--                // Date range picker--}}
                    @if ($statFilterDate == "true")
                        @php
                            $config = [
                                "showDropdowns" => true,
                                "startDate" => "{{$stat_start}}",
                                "endDate" => "{{$stat_end}}",
                                "minYear" => 2011,
                                "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                                "timePicker" => false,
                                "timePicker24Hour" => true,
                                "timePickerIncrement" => 30,
                                "locale" => ["format" => "DD/MM/YYYY"],
                                "opens" => "center"
                            ];
                        @endphp
                        <x-adminlte-date-range name="{{$pref->paramName_StatInTime}}{{$idModal}}" label="Date/Time Range" :config="$config">
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                            <x-slot name="appendSlot">
                                <x-adminlte-button  id="initBtn" label="Init" icon="far fa-trash-alt"/>
                            </x-slot>
                        </x-adminlte-date-range>
                    @endif

                </div>
            </form>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChange{{$idModal}}">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@push('js')
    <script>
        // $(document).ready(function() {
            //Utilisé pour la liste multiple des contacts à ajouter
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: "{{__('Select one or more sites')}}",
                data : @json($locations),
            })

        $('#initBtn').on('click', function (e) {
            $("#{{$pref->paramName_StatInTime}}{{$idModal}}").data('daterangepicker').setStartDate('01/01/2010');
            $("#{{$pref->paramName_StatInTime}}{{$idModal}}").data('daterangepicker').setEndDate('01/01/2010');
            $("#{{$pref->paramName_StatInTime}}{{$idModal}}").val('');


        });




            $('#saveChange{{$idModal}}').on('click', function (e) {
                // var data = e.params.data;
                // console.log(data);
                //console.log(e);
                //console.log(document.changeFilter.serializeArray());
                console.log($("#locations").val());
                //document.changeFilter.submit();

                jQuery.ajax({
                    url: window.location.origin + '/changeparam',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        @if($requestFilter == "true") '{{$pref->paramName_UserLocation}}' : $("#{{$pref->paramName_UserLocation}}{{$idModal}}").val(), @endif
                        @if($statFilter == "true") '{{$pref->paramName_StatUserLocation}}' : $("#{{$pref->paramName_StatUserLocation}}{{$idModal}}").val(), @endif
                        @if($usrOpenedRqt == "true") '{{$pref->paramName_UserFilter}}' : $("#{{$pref->paramName_UserFilter}}{{$idModal}}").is(':checked'), @endif
                        @if($usrClosedRqt == "true") '{{$pref->paramName_UserFilterClosedRequest}}' : $("#{{$pref->paramName_UserFilterClosedRequest}}{{$idModal}}").is(':checked'), @endif
                        @if($statFilter == "true") 'startDate' : $("#{{$pref->paramName_StatInTime}}{{$idModal}}").data('daterangepicker').startDate.format('DD/MM/YYYY'),
                                                    'endDate' : $("#{{$pref->paramName_StatInTime}}{{$idModal}}").data('daterangepicker').endDate.format('DD/MM/YYYY'),
                        @endif
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
                        $('#{{$idModal}}').modal('hide');
                        location.reload()
                    }
                });
            });

        // });
    </script>

@endpush



