<div class="row">
    @php
        $cards = [
            'incident' => [
                'count' => $requestsByType['incident'] ?? 0,
                'label' => __('Incidents'),
                'icon' => getTypeIcon('incident'),
                'color' => getTypeColor('incident', true),
            ],
            'service_request' => [
                'count' => $requestsByType['service_request'] ?? 0,
                'label' => __('Services Request'),
                'icon' => getTypeIcon('service_request'),
                'color' => getTypeColor('service_request', true),
            ],
            'undefined' => [
                'count' => $requestsByType[''] ?? 0,
                'label' => __('Request Undefined'),
                'icon' => getTypeIcon(''),
                'color' => getTypeColor('', true),
            ],
        ];
    @endphp

    @foreach($cards as $type => $card)
        <div class="col-lg-4 col-6">
            <!-- small card -->
            <div class="small-box {{ $card['color'] }}">
                <div class="inner">
                    <h3>{{ $card['count'] }}</h3>
                    <p>{{ $card['label'] }}</p>
                </div>
                <div class="icon">
                    <i class="{{ $card['icon'] }}"></i>
                </div>
                <a href="{{ route('openedrequest.filter', ['status' => request('status', 'all'), 'priority' => request('priority', 'all'), 'request_type' => $type === 'undefined' ? '' : $type]) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    @endforeach

</div>
