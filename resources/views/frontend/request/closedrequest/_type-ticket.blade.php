<div class="row">
    <!-- Info-box for Total Tickets -->
    <div class="col-12 col-sm-6 col-md-3">
        <a href="{{ route('closedrequest.filter', ['request_type' => 'all', 'priority' => request('priority', 'all'), 'resolution_code' => request('resolution_code', 'all')]) }}" style="color: inherit;">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-layer-group"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('Total Tickets') }}</span>
                    <span class="info-box-number">{{ $totalTickets }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </a>
    </div>
    <!-- /.col -->

    <!-- Loop through types of tickets only -->
    <!-- Loop through types of tickets only -->
    @foreach ($requestsByType as $type => $count)
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('closedrequest.filter', ['resolution_code' => request('resolution_code', 'all'), 'priority' => request('priority', 'all'), 'request_type' => ($type == '' ? 'undefined' : $type)]) }}" style="color: inherit;">
                <div class="info-box">
{{--                <span class="info-box-icon @if ($type == 'incident') bg-info @elseif ($type == 'service_request') bg-danger @elseif ($type == '') bg-warning @else bg-secondary @endif elevation-1">--}}
                <span class="info-box-icon {{getTypeColor($type,true)}} elevation-1">
                    <i class="fa-solid {{ getTypeIcon($type) }}"></i>
                </span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ __(ucfirst($type == '' ? 'undefined' : $type)) }}</span>
                        <span class="info-box-number">{{ $count }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>
        <!-- /.col -->
    @endforeach
</div>
