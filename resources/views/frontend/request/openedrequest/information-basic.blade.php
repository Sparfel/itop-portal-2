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
{{--                        <dd class="col-sm-3">{{$Ticket->site_name ?: '-'}}</dd>--}}
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
                            <textarea id="summernote" class="summernote" name="newlog"></textarea>
                            <input type='hidden' id='ref' name='ref' value='{{$Ticket->ref}}'>
                            <input type='hidden' id='id' name='id' value='{{$Ticket->id}}'>
                            <input type='hidden' id='title' name='title' value='{{$Ticket->title}}'>
{{--                            <input type='hidden' id='location' name='location' value='{{$Ticket->site_name}}'>--}}
                            <input type='hidden' id='organization' name='organization' value='{{$Ticket->org_name}}'>
                            <input type='hidden' id='agent_id' name='agent_id' value='{{$Ticket->agent_id}}'>
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
{{--                        <dt class="col-sm-4">{{__('Remote Ref.')}}</dt>--}}
{{--                        <dd class="col-sm-8">{{$Ticket->remote_ref ?: '-'}}</dd>--}}
                        <dt class="col-sm-4">{{__('Priority')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->priority}}</dd>
                        <dt class="col-sm-4">{{__('Service')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->service_name ?: '-'}}</dd>
                        <dt class="col-sm-4">{{__('Service Element')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->servicesubcategory_name ?: '-'}}</dd>
{{--                        <dt class="col-sm-4">{{__('Module')}}</dt>--}}
{{--                        <dd class="col-sm-8">{{$Ticket->module_name ?: '-'}}</dd>--}}
                        {{--                    <dd class="col-sm-8 offset-sm-4">Donec id elit non mi porta gravida at eget metus.</dd>--}}
                        <dt class="col-sm-4">{{__('Start Date')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->start_date}}</dd>
                        <dt class="col-sm-4">{{__('Last Update')}}</dt>
                        <dd class="col-sm-8">{{$Ticket->last_update}}</dd>
                    </dl>
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

    //Validation du formulaire par le bouton Valider
    $("#valid").click(function() {
        document.getElementById("changeStatus").value = "update";
        if ($('#summernote').summernote('isEmpty')) {
            // alert('editor content is empty');
            toastr['info']('{{__('No new entrie')}}','{{__('No change')}}');
        }
        else {$("#newLog").submit();}
    });
    //Marquer le ticket comme résolu
    $("#solve").click(function() {
        document.getElementById("changeStatus").value = "solve";
        $("#newLog").submit();
    });
    $("#close").click(function() {
        document.getElementById("changeStatus").value = "close";
        $("#newLog").submit();
    });
    $("#reopen").click(function() {
        document.getElementById("changeStatus").value = "reopen";
        $("#newLog").submit();
    });
    </script>
@endpush

