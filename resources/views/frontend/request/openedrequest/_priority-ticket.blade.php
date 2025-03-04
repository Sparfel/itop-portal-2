<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-flag"></i> {{__('Priority')}}</h3>

        <div class="card-tools">
            @if ((request('priority') && request('priority') !== 'all'))
                <a href="{{ route('openedrequest.filter', [
                                                            'priority' => 'all',
                                                            'request_type' => request('request_type', 'all'),
                                                            'status' => request('status', 'all')
                                                        ]) }}">
                    <i class="fa-solid fa-filter text-warning"></i>
                </a>
            @elseif ((request('request_type') && request('request_type') !== 'all')
                    || (request('status') && request('status') !== 'all'))
                <i class="fa-solid fa-filter text-info"></i>
            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
         </div>
    </div>
    <!-- /.card-header -->
{{--    <div class="card-body">--}}
{{--        {{ dump(json_encode($pie_labels->getData())) }}--}}
{{--        <pie-chart--}}
{{--            title="Quick Stats"--}}
{{--                        :labels="JSON.stringify(['Qualified', 'Resolved', 'New', 'Pending'])"--}}
{{--                        :data-prop="JSON.stringify([30, 5, 2,1])"--}}
{{--            labels="{{ json_encode($pie_labels->getData()) }}"--}}
{{--            data-prop="{{ json_encode($pie_data->getData()) }}"--}}
{{--            type="doughnut"--}}
{{--            :card="false"--}}
{{--        />--}}
        <!-- /.row -->
{{--    </div>--}}
    <!-- /.card-body -->

    <div class="card-footer p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item active">
                <a href="{{ route('openedrequest.filter', ['priority' => 'all', 'request_type' => request('request_type', 'all'), 'status' => request('status', 'all')]) }}" class="nav-link">
                    <i class="fa fa-layer-group text-primary"></i> {{__('All')}}
                    <span class="badge bg-primary float-right "> {{ $totalTickets }}</span>
                </a>
            </li>
            @if (isset($requestsByPriority['1']))
            <li class="nav-item">
                <a href="{{ route('openedrequest.filter', ['priority' => 1, 'request_type' => request('request_type', 'all'), 'status' => request('status', 'all')]) }}" class="nav-link">
                    <i class="fa-solid fa-fire @if ($requestsByPriority['1']> 0) text-danger @endif"></i> {{__('Critical')}}
                    <span class="badge @if ($requestsByPriority['1']> 0) bg-danger @else bg-secondary @endif float-right">{{ $requestsByPriority['1'] }}</span>
                </a>
            </li>
            @endif
            @if (isset($requestsByPriority['2']))
            <li class="nav-item">
                <a href="{{ route('openedrequest.filter', ['priority' => 2, 'request_type' => request('request_type', 'all'), 'status' => request('status', 'all')]) }}" class="nav-link">
                    <i class="fa-solid fa-triangle-exclamation @if ($requestsByPriority['2']> 0) text-warning @endif"></i> {{__('High')}}
                    <span class="badge @if ($requestsByPriority['2']> 0) bg-warning @else bg-secondary @endif float-right">{{ $requestsByPriority['2'] }}</span>
                </a>
            </li>
            @endif
            @if (isset($requestsByPriority['3']))
            <li class="nav-item">
                <a href="{{ route('openedrequest.filter', ['priority' => 3, 'request_type' => request('request_type', 'all'), 'status' => request('status', 'all')]) }}" class="nav-link">
                    <i class="fa-solid fa-circle-info @if ($requestsByPriority['3'] > 0) text-info @endif"></i> {{__('Medium')}}
                    <span class="badge @if ($requestsByPriority['3']> 0) bg-info @else bg-secondary @endif float-right">{{ $requestsByPriority['3'] }}</span>
                </a>
            </li>
            @endif
            @if (isset($requestsByPriority['4']))
            <li class="nav-item">
                <a href="{{ route('openedrequest.filter', ['priority' => 4, 'request_type' => request('request_type', 'all'), 'status' => request('status', 'all')]) }}" class="nav-link">
                    <i class="fa-solid fa-check-circle @if ($requestsByPriority['4'] > 0) text-secondary @endif"></i> {{__('Low')}}
                    <span class="badge @if ($requestsByPriority['4']> 0) bg-secondary @else bg-secondary @endif float-right">{{ $requestsByPriority['4'] }}</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
    <!-- /.footer -->
</div>


