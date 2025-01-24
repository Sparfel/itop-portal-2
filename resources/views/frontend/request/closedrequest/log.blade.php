
<div class="row">
    <div class="col-md-9">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-comments"></i>
                    {{__('Logs')}}
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool float-left" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="direct-chat-messages" style="height:auto;">
                {{--                {{dd($Ticket->public_log)}}--}}

                @php {{$pref = session('preferences');}}
                @endphp
                @foreach($Ticket->public_log->entries as $log)
                    <!-- Message. Default to the left -->
                        @php {{
                             if ($pref->org_id == $AlogContact[$log->user_id]['org_id'])
                                {   $side_start = 'left';
                                    $side_end = 'right';
                                }
                             else {   $side_start = 'right';
                                    $side_end = 'left';
                                }
                        }}
                        @endphp

                        <div class="direct-chat-msg {{$side_start}}">
                            <div class="direct-chat-infos clearfix">
                                @if ($side_start == 'left')
                                    <span class="direct-chat-name float-{{$side_start}}">{{$log->user_login}}</span>
                                    <span class="direct-chat-timestamp float-{{$side_end}}">{{$log->date}}</span>
                                @else
                                    <span class="direct-chat-timestamp float-{{$side_end}}">{{$log->date}}</span>
                                    <span class="direct-chat-name float-{{$side_start}}">{{$log->user_login}}</span>
                                @endif
                            </div>
                            <!-- /.direct-chat-infos -->
                            <img class="direct-chat-img" src="{{ asset('storage/'.$AlogContact[$log->user_id]['avatar']) }}" alt="message user image">
                            <!-- /.direct-chat-img -->
                            <div class="direct-chat-text">
                                {!! $log->message_html !!}
                            </div>
                            <!-- /.direct-chat-text -->
                        </div>
                    @endforeach
                </div>
          </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="col-md-3">
        <h4>{{__('Life cycle')}}</h4>
        <div class="timeline">
            <!-- Timeline time label -->
            <div class="time-label">
                <span class="bg-green">{{$Ticket->last_update}}</span>
            </div>
{{--            {{dd($Ahistory)}}--}}
            @foreach ($Ahistory as $histo)
                <div>
                    <!-- Before each timeline item corresponds to one icon on the left scale -->
{{--                    <i class="fas fa-envelope bg-blue"></i>--}}
                {!! $histo->icone !!}
                    <!-- Timeline item -->
                    <div class="timeline-item">
                        <!-- Time -->
                        <span class="time"><i class="fas fa-clock"></i> {{$histo->date}}</span>
                        <!-- Header. Optional -->
                        <h3 class="timeline-header"><a href="#"></a> {{$histo->newvalue}}</h3>
                        <!-- Body -->
                        <div class="timeline-body">
                            {{$histo->userinfo}}
                        </div>
                        <!-- Placement of additional controls. Optional -->
    {{--                    <div class="timeline-footer">--}}
    {{--                        <a class="btn btn-primary btn-sm">Read more</a>--}}
    {{--                        <a class="btn btn-danger btn-sm">Delete</a>--}}
    {{--                    </div>--}}
                    </div>
                </div>
            @endforeach
            <!-- The last icon means the story is complete -->

            <div class="time-label">
                <span class="bg-green">{{$Ticket->start_date}}</span>
            </div>

            <div>
                <i class="fas fa-clock bg-gray"></i>
            </div>
        </div>
    </div>
</div>
