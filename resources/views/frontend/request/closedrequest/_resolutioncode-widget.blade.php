<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-chart-pie"></i> {{__('Resolution Code overview')}}</h3>
        <div class="card-tools">
            @if ((request('resolution_code') && request('resolution_code') !== 'all'))
                <a href="{{ route('closedrequest.filter', [
                                                                    'resolution_code' => 'all',
                                                                    'request_type' => request('request_type', 'all'),
                                                                    'priority' => request('priority', 'all')
                                                                ]) }}">
                    <i class="fa-solid fa-filter text-warning"></i>
                </a>

            @elseif ((request('priority') && request('priority') !== 'all') || (request('request_type') && request('request_type') !== 'all')
                    )
                <a href="{{ route('closedrequest.filter', [
                                                            'resolution_code' => 'all',
                                                            'priority' => request('priority', 'all'),
                                                            'request_type' => request('request_type', 'all')
                                                        ]) }}">
                    <i class="fa-solid fa-filter text-info"></i>
                </a>
{{--            @else--}}
{{--                    <i class="fa-solid fa-filter text-secondary"></i>--}}
            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <pie-chart
            title="Quick Stats"
{{--            :labels="JSON.stringify(['Qualified', 'Resolved', 'New', 'Pending'])"--}}
{{--            :data-prop="JSON.stringify([30, 5, 2,1])"--}}
            labels="{{ json_encode($pie_labels->getData()) }}"
            data-prop="{{ json_encode($pie_data->getData()) }}"
            type="doughnut"
            :card="false"
        />
        <!-- /.row -->
    </div>
    <!-- /.card-body -->

    <div class="card-footer p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item active">
                <a href="{{ route('closedrequest.filter', ['resolution_code' => 'all','priority' => request('priority', 'all'),'request_type' => request('request_type', 'all')]) }}" class="nav-link">
                    <i class="fa fa-layer-group text-primary"></i> {{__('All')}}
                    <span class="badge bg-primary float-right "> {{ $totalTickets }}</span>
                </a>
            </li>


            @foreach ($requestsByResolutioncode as $code => $count)
                @if ($count > 0)
                    <li class="nav-item">
                        <a href="{{ route('closedrequest.filter', [ 'resolution_code' => $code,'priority' => request('priority', 'all'),'request_type' => request('request_type', 'all')
                        ]) }}" class="nav-link">
                            <i class="{{ getResolutioncodeIcon($code)}} {{getResolutioncodeColor($code)}}"></i> {{ __($code) }}
                            <span class="badge {{getResolutioncodeColor($code,true)}} float-right">{{ $count }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
    <!-- /.footer -->
</div>


