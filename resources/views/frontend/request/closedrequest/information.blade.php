{{--{{dd($Ticket)}}--}}
{{--{{dd($Abuttons)}}--}}
<div class="row">
    <div class="col-md-8">
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
                    <dt class="col-sm-3">{{__('Request\'s Type')}}</dt>
                    <dd class="col-sm-3">{{$Ticket->request_type ?: '-'}}</dd>
                    <dt class="col-sm-3">{{__('Organization')}}</dt>
                    <dd class="col-sm-3">{{$Ticket->org_name ?: '-'}}</dd>
{{--                    <dt class="col-sm-3">{{__('Location')}}</dt>--}}
{{--                    <dd class="col-sm-3">{{$Ticket->site_name ?: '-'}}</dd>--}}
{{--                    <dd class="col-sm-8 offset-sm-4">Donec id elit non mi porta gravida at eget metus.</dd>--}}
                    <dt class="col-sm-3">{{__('Description') }}</dt>
                    <dd class="col-sm-9">{!! $Ticket->description !!}</dd>
               </dl>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-4">

        @if($Ticket->status == 'closed')
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
{{--                        {{$Ticket->resolution_code}}--}}
                        @switch($Ticket->resolution_code)
                            @case('assistance')
                                <i title="{{ __('Assistance') }}" class="far fa-life-ring"></i> {{ __('Assistance') }}
                                @break

                            @case('bug fixed')
                                <i title="{{ __('Bug fixed') }}" class="fas fa-bug"></i> {{ __('Bug fixed') }}
                                @break

                            @case('hardware repair')
                                <i title="{{ __('Hardware repair') }}" class="fas fa-tools"></i> {{ __('Hardware repair') }}
                                @break

                            @case('other')
                                <i title="{{ __('Other') }}" class="fas fa-map-signs"></i> {{ __('Other') }}
                                @break

                            @case('software patch')
                                <i title="{{ __('Software patch') }}" class="fas fa-puzzle-piece"></i> {{ __('Software patch') }}
                                @break

                            @case('system update')
                                <i title="{{ __('System update') }}" class="fas fa-sync-alt"></i> {{ __('System update') }}
                                @break

                            @case('training')
                                <i title="{{ __('Training') }}" class="fas fa-graduation-cap"></i> {{ __('Training') }}
                                @break

                        @endswitch


                    </dd>
                    <dt class="col-sm-4">{{__('Solution')}}</dt>
                    <dd class="col-sm-8">{!! $Ticket->solution !!}</dd>
                </dl>
            </div>
            <!-- /.card-body -->
        </div>
        @endif

        <div class="card card-dark card-outline">
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
{{--                    <dt class="col-sm-4">{{__('Remote Ref.')}}</dt>--}}
{{--                    <dd class="col-sm-8">{{$Ticket->remote_ref ?: '-'}}</dd>--}}
                    <dt class="col-sm-4">{{__('Priority')}}</dt>
                    <dd class="col-sm-8">{{$Ticket->priority}}</dd>
                    <dt class="col-sm-4">{{__('Service')}}</dt>
                    <dd class="col-sm-8">{{$Ticket->service_name ?: '-'}}</dd>
{{--                    <dt class="col-sm-4">{{__('Service Element')}}</dt>--}}
{{--                    <dd class="col-sm-8">{{$Ticket->serviceelement_name ?: '-'}}</dd>--}}
{{--                    <dt class="col-sm-4">{{__('Module')}}</dt>--}}
{{--                    <dd class="col-sm-8">{{$Ticket->module_name ?: '-'}}</dd>--}}
{{--                    <dd class="col-sm-8 offset-sm-4">Donec id elit non mi porta gravida at eget metus.</dd>--}}
                    <dt class="col-sm-4">{{__('Start Date')}}</dt>
                    <dd class="col-sm-8">{{$Ticket->start_date}}</dd>
                    <dt class="col-sm-4">{{__('Last Update')}}</dt>
                    <dd class="col-sm-8">{{$Ticket->last_update}}</dd>
                    <dt class="col-sm-4">{{__('Close Update')}}</dt>
                    <dd class="col-sm-8">{{$Ticket->close_date}}</dd>
                </dl>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>



