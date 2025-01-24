<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3> {{$requestsByType['incident']}}</h3>

                <p>{{__('Incidents')}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-life-ring"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$requestsByType['service_request']}}</h3>

                <p>{{__('Services Request')}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-handshake"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{$requestsByType['']}}</h3>

                <p>{{__('Request Undefined')}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-ellipsis-h"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
{{--    <div class="col-lg-3 col-6">--}}
{{--        <!-- small card -->--}}
{{--        <div class="small-box bg-danger">--}}
{{--            <div class="inner">--}}
{{--                <h3>65</h3>--}}

{{--                <p>Unique Visitors</p>--}}
{{--            </div>--}}
{{--            <div class="icon">--}}
{{--                <i class="fas fa-chart-pie"></i>--}}
{{--            </div>--}}
{{--            <a href="#" class="small-box-footer">--}}
{{--                More info <i class="fas fa-arrow-circle-right"></i>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}
    <!-- ./col -->
</div>
