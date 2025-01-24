<div class="container-fluid">

    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-8 connectedSortable">

            <div class="card card-dark card-outline" style="overflow:hidden;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle primary"></i>
                        {{__('General Informations')}}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool float-left" data-card-widget="maximize"><i class="fas fa-expand"></i></button>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">{{__('Caller')}}</dt>
                        <dd class="col-sm-3">{{$Ticket->caller_id_friendlyname}}</dd>
                        <dt class="col-sm-3">{{__('Request Type')}}</dt>
                        <dd class="col-sm-3">{{$Ticket->request_type ?: '-'}}</dd>
                        <dt class="col-sm-3">{{__('Organization')}}</dt>
                        <dd class="col-sm-3">{{$Ticket->org_name ?: '-'}}</dd>
                        <dt class="col-sm-3">{{__('Location')}}</dt>
                        <dd class="col-sm-3">{{$Ticket->site_name ?: '-'}}</dd>
                        {{--                    <dd class="col-sm-8 offset-sm-4">Donec id elit non mi porta gravida at eget metus.</dd>--}}
                        <dt class="col-sm-3">{{__('Description') }}</dt>
                        <dd class="col-sm-9">{!! $Ticket->description !!}</dd>
                    </dl>
                </div>
                <!-- /.card-body -->
            </div>

            @can('update_opened_request')
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pencil-alt"></i>
                            {{__('New log')}}
                        </h3>
                        <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
{{--                        <form action="/openedrequest/{{$Ticket->id}}" method="post" id="newLog">--}}
                            {{ csrf_field() }}
                            <input type="hidden" id="mode" name="mode" value="expert"/>
                            <textarea id="summernote" class="summernote" name="newlog"></textarea>
                            <input type='hidden' id='ref' name='ref' value='{{$Ticket->ref}}'>
                            <input type='hidden' id='id' name='id' value='{{$Ticket->id}}'>
                            <input type='hidden' id='title' name='title' value='{{$Ticket->title}}'>
                            <input type='hidden' id='location' name='location' value='{{$Ticket->site_name}}'>
                            <input type='hidden' id='organization' name='organization' value='{{$Ticket->org_name}}'>
                            <input type='hidden' id='agent_email' name='agent_email' value='{{$Ticket->agent_email}}'>
                            <input type='hidden' id='changeStatus' name='changeStatus' value=''>
                            <input hidden type="submit" id="validNewLog" value="Valider">
{{--                        </form>--}}

                    </div>
                    <!-- /.card-body -->
                </div>
            @endcan

        </section>

        <section class="col-lg-4 connectedSortable">
            @if($Ticket->status == 'resolved')
                <div class="card card-success ">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-thumbs-up"></i>
                            {{__('Solution')}}
                        </h3>
                        <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">{{__('Resolution Date')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->resolution_date}}</dd>
                            <dt class="col-sm-4">{{__('Resolution Code')}}</dt>
                            <dd class="col-sm-8">
{{--                                {{$Ticket->resolution_code}}--}}
                                @switch($Ticket->resolution_code)
                                    @case ('assistance_util')
                                    <i title="{{__('User assistance')}}" class="far fa-life-ring"></i> {{__('User assistance')}}
                                    @break
                                    @case('assistance_param')
                                    <i title="{{__('Paramater assistance')}}" class="fas fa-wrench"></i> {{__('Paramater assistance')}}
                                    @break
                                    @case('bug')
                                    <i title="{{__('Bug')}}" class="fas fa-bug"></i> {{__('Bug')}}
                                    @break
                                    @case('couldnotreproduce')
                                    <i title="{{__('Could not reproduce')}}" class="far fa-question-circle"></i> {{__('Could not reproduce')}}
                                    @break
                                    @case('evolution')
                                    <i title="{{__('Evolution')}}" class="fas fa-chevron-circle-up"></i> {{__('Evolution')}}
                                    @break
                                    @case('finitionaffaire')
                                    <i title="{{__('End of contract')}}" class="fas fa-hourglass-end"></i> {{__('End of contract')}}
                                    @break
                                    @case('formation')
                                    <i title="{{__('Training')}}" class="fas fa-graduation-cap"></i> {{__('Training')}}
                                    @break
                                    @case('hardwarefailure')
                                    <i title="{{__('Hardware failure')}}" class="fas fa-microchip"></i> {{__('Hardware failure')}}
                                    @break
                                    @case('outofcontract')
                                    <i title="{{__('Out of contract')}}" class="fas fa-times-circle"></i> {{__('Out of contract')}}
                                    @break
                                    @case('solved_by_customer')
                                    <i title="{{__('Solved by customer')}}" class="fas fa-medal"></i> {{__('Solved by customer')}}
                                    @break
                                    @case('other')
                                    <i title="{{__('Other')}}" class="fas fa-map-signs"></i> {{__('Other')}}
                                    @break
                                @endswitch
                            </dd>
                            <dt class="col-sm-4">{{__('Solution')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->solution}}</dd>
                        </dl>
                    </div>
                    <!-- /.card-body -->
                </div>
            @endif
            @if($Ticket->status == 'pending')
                <div class="card card-warning ">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-pause-circle"></i>
                            {{__('Pending')}}
                        </h3>
                        <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">{{__('Pending Reason')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->pending_reason}}</dd>
                        </dl>
                    </div>
                    <!-- /.card-body -->
                </div>
            @endif


            @if(strlen($Ticket->estimated_budget)> 0)
                <div class="card card-info ">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-coins"></i>
                            {{__('Estimated budget')}}
                        </h3>
                        <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">{{__('Cost')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->estimated_budget}}</dd>
                        </dl>
                    </div>
                    <!-- /.card-body -->
                </div>
            @endif

            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-compass"></i>
                        {{__('Qualifications')}}
                    </h3>
                    <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <dl class="row">

                        <dt class="col-sm-4">{{__('Status')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->status}}</dd>
                        <dt class="col-sm-4">{{__('Agent')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->agent_id_friendlyname ?: '-'}}</dd>
                        <dt class="col-sm-4">{{__('Remote Ref.')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->remote_ref ?: '-'}}</dd>
                        <dt class="col-sm-4">{{__('Priority')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->priority}}</dd>
                        <dt class="col-sm-4">{{__('Start Date')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->start_date}}</dd>
                        <dt class="col-sm-4">{{__('Last Update')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->last_update}}</dd>
                    </dl>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tags"></i>
                        {{__('Complements')}}
                    </h3>
                    <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    @if($Ticket->status == 'pending'
                            || $Ticket->status == 'new'
                            || $Ticket->status == 'qualified'
                            )

                        <service-module :location_id="{{$Ticket->site_id}}"
                            :service_id="{{$Ticket->service_id}}"
                            :module_id="{{$Ticket->module_id}}"
                        >
                        </service-module>

                        <div class="form-group row">
                            <label class="col-sm-4 ">{{__('Impact')}}</label>
                            <div class="col-sm-8">
                                <select name="impact" id="impact" class="form-control" data-placeholder="{{__('Type')}}"
                                        style="width: 100%;">
                                    <option value="multiple_user" :selected="'{{$Ticket->impact}}'=='multiple_user'">{{__('multiple user')}}</option>
                                    <option value="single_user" :selected="'{{$Ticket->impact}}'=='single_user'">{{__('single_user')}}</option>
                                    <option value="undefined" :selected="'{{$Ticket->impact}}'=='undefined'">{{__('undefined')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 ">{{__('Issue Type')}}</label>
                            <div class="col-sm-8">
                                <select name="issuetype" id="issuetype" class="form-control" data-placeholder="{{__('Type')}}"
                                        style="width: 100%;">
                                    <option value="">{{__('-- Select an issue type --')}}</option>
                                    <option value="Automation" :selected="'{{$Ticket->issue_type}}'=='Automation'" >{{__('automation')}}</option>
                                    <option value="Electrical" :selected="'{{$Ticket->issue_type}}'=='Electrical'">{{__('electrical')}}</option>
                                    <option value="Mechanical" :selected="'{{$Ticket->issue_type}}'=='Mechanical'">{{__('mechanical')}}</option>
                                    <option value="Software" :selected="'{{$Ticket->issue_type}}'=='Software'">{{__('software')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 ">{{__('Part creating issue')}}</label>
                            <div class="col-sm-8">
                                <input class="form-control" id="part_creating_issue" name ="part_creating_issue" type="text" placeholder="" value="{{$Ticket->part_creating_issue}}">
                            </div>
                        </div>


                        <component-failure-type
                            :service_id="{{$Ticket->service_id}}"
                            :failurecomponentissue_id="{{$Ticket->componentcreatingissue_id}}"
                            :failuremode_id="{{$Ticket->failuremode_id}}"
                        >
                        </component-failure-type>

                    @else
                        <dl class="row">
                            <dt class="col-sm-4">{{__('Service')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->service_name ?: '-'}}</dd>
                            <dt class="col-sm-4">{{__('Service Element')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->serviceelement_name ?: '-'}}</dd>
                            <dt class="col-sm-4">{{__('Module')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->module_name ?: '-'}}</dd>

                            <dt class="col-sm-4">{{__('Impact')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->impact ?: '-'}}</dd>
                            <dt class="col-sm-4">{{__('Part creating issue')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->part_creating_issue ?: '-'}}</dd>
                            <dt class="col-sm-4">{{__('Component')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->componentcreatingissue_id_friendlyname}}</dd>
                            <dt class="col-sm-4">{{__('Failure mode')}}</dt>
                            <dd class="col-sm-8">{{$Ticket->failuremode_id_friendlyname}}</dd>

                        </dl>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>

            @can('update_opened_request')
                <buttons-request locale = "{{session('locale')}}" buttons={{ json_encode($Abuttons)}}></buttons-request>
            @endcan



        </section>
    </div>
</div>


@push('js')

    <script>
    $('#summernote').summernote(
        {
            height: 300,
            placeholder: '{{__('Please include as much detail as possible in your message')}}',
        }
    );

    // var form_clean;
    //serialize clean form
    // window.onload = function(e){
    //     form_clean = $("#newLog").serialize();
    //     console.log('la' + form_clean);
    // }



    //Validation du formulaire par le bouton Valider
    {{--$("#valid").click(function() {--}}
    {{--document.getElementById("changeStatus").value = "update";--}}
    {{--    form_dirty = $("#newLog").serialize();--}}
    {{--    console.log('clean');--}}
    {{--    console.log(form_clean);--}}
    {{--    console.log('dirty');--}}
    {{--    console.log(form_dirty);--}}

    {{--    if(form_clean != form_dirty)--}}
    {{--        {//$("#newLog").submit();--}}
    {{--        }--}}
    {{--    else{toastr['warning']('{{__('No new entrie. Please enter something in the log')}}','{{__('No change')}}');}--}}
    {{--});--}}

    {{--//Marquer le ticket comme résolu--}}
    {{--$("#solve").click(function() {--}}
    {{--    document.getElementById("changeStatus").value = "solve";--}}
    {{--    $("#newLog").submit();--}}
    {{--});--}}
    {{--$("#close").click(function() {--}}
    {{--    document.getElementById("changeStatus").value = "close";--}}
    {{--    $("#newLog").submit();--}}
    {{--});--}}
    {{--$("#reopen").click(function() {--}}
    {{--    document.getElementById("changeStatus").value = "reopen";--}}
    {{--    $("#newLog").submit();--}}
    {{--});--}}


    </script>
@endpush

