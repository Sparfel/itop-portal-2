<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title"> <i class="fa-solid fa-chart-column"></i> {{ __('Overview') }}

        </h3>

        <div class="card-tools">
        @if ((request('request_type') && request('request_type') !== 'all'))
                <a href="{{ route('openedrequest.filter', [
                                                            'priority' => request('priority', 'all'),
                                                            'request_type' => 'all'
                                                        ]) }}">
                  <i class="fa-solid fa-filter text-warning"></i>
                </a>
        @elseif ((request('priority') && request('priority') !== 'all'))
                <i class="fa-solid fa-filter text-secondary"></i>
        @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse">

                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <tbody><tr>
                <th style="width:50%"> <i class="fa fa-layer-group"></i>
                    <a href="{{ route('openedrequest.filter', ['priority' => request('priority', 'all'), 'request_type' => 'all']) }}" class="text-muted">
                    {{__('Total')}}
                    </a>
                </th>
                <td> {{ $totalTickets }}</td>
            </tr>

            @if (!empty($requestsByType['incident']))
                <tr>
                    <th><i class="fa fa-life-ring"></i>
                        <a href="{{ route('openedrequest.filter', ['priority' => request('priority', 'all'), 'request_type' => 'incident']) }}" class="text-muted">
                            {{__('Incident')}}
                        </a>
                    </th>
                    <td> {{ $requestsByType['incident'] }}</td>
                </tr>
            @endif
            @if (!empty($requestsByType['service_request']))
            <tr>
                <th> <i class="fa fa-handshake"></i>
                    <a href="{{ route('openedrequest.filter', ['priority' => request('priority', 'all'), 'request_type' => 'service_request']) }}" class="text-muted">
                        {{__('Service request')}}
                    </a>
                </th>
                <td> {{ $requestsByType['service_request'] }}</td>
            </tr>
            @endif
            @if (!empty($requestsByType['']))
            <tr>
                <th>
{{--                    <i class="fa-regular fa-circle-question"></i> --}}
                    <a href="{{ route('openedrequest.filter', ['priority' => request('priority', 'all'), 'request_type' => 'undefined']) }}" class="text-muted">
                    <i class="fa fa-ellipsis-h"></i>
                    {{__('Undefined')}}
                    </a>
                </th>
                <td> {{ $requestsByType[''] }}</td>
            </tr>
            @endif

            </tbody></table>
    </div>
    <!-- /.card-body -->
</div>
