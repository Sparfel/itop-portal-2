@extends('adminlte::page')
@section('content_header')
    <h1><i class="fas fa-id-card"></i> {{ __('Your Profile') }}</h1>
@endsection

@section('content')
    @include('frontend.profile._password')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <section class="content">
        <div class="container-fluid text-sm">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.profile.identity')
                    @include('frontend.profile.about')
                </div>

                <div class="col-md-9">
                    <form role="form" method="POST" >
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" role="tablist">
{{--                    <div id="tabSettings" class="card">--}}
{{--                        <div class="card-header p-2 card-primary card-outline card-outline-tab ">--}}
{{--                            <ul class="nav nav-pills">--}}
{{--                                <li class="nav-item"><a class="nav-link active" href="#preferences" data-toggle="tab">{{__('Preferences')}}</a></li>--}}
                                <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">{{__('Settings')}}</a></li>
                                @if ($user->is_staff == 1)<li class="nav-item"><a class="nav-link" href="#proData" data-toggle="tab">{{__('Professional Datas')}}</a></li>@endif
                                @if ($user->is_staff == 1)<li class="nav-item"><a class="nav-link" href="#privateData" data-toggle="tab">{{__('Personal Datas')}}</a></li>@endif
                                <li class="nav-item"><a class="nav-link" href="#aboutPortal" data-toggle="tab">{{__('About the Portal')}}</a></li>
{{--                                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">{{__('Change your password')}}</a></li>--}}
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

{{--                                <div class="active tab-pane" id="preferences">--}}
{{--                                    @include('frontend.profile.preference')--}}
{{--                                </div>--}}
{{--                                <div class=" tab-pane" id="proData">--}}
{{--                                    @include('frontend.profile.professional')--}}
{{--                                </div>--}}
{{--                                <div class="tab-pane" id="privateData">--}}
{{--                                    @include('frontend.profile.personal')--}}
{{--                                </div>--}}
                                <div class="active tab-pane" id="settings">
                                    @include('frontend.profile.setting')
                                </div>
                                <div class="tab-pane" id="aboutPortal">
                                    @include('frontend.profile.portal')
                                </div>
{{--                                <div class="tab-pane" id="password">--}}
{{--                                    @include('frontend.profile._password')--}}
{{--                                </div>--}}



                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right">Valider</button>
                        </div>
                    </div>

                </form>
                    <!-- /.nav-tabs-custom -->
                </div>

                <!-- /.col -->
            </div>
        </div>
    </section>

@endsection

@section('footer')
&nbsp;
@endsection


@section('css')
    <style>
        .select2-container--bootstrap4.select2-container--focus .select2-selection {
        border-color: #EA7D1E;
        -webkit-box-shadow: 0 0 0 0.2rem rgba(234, 125, 30, 0.25);
        box-shadow: 0 0 0 0.2rem rgba(234, 125, 30, 0.25); }
    </style>
@endsection

@section('js')

    <script>

        $('.select2bs4').select2({
            theme: 'bootstrap4',
            placeholder: "{{__('Select one or more locations')}}",
            //maximumSelectionLength: 5, //On limite l'ajout à 5 contacts d'un coup
            // ajax: {
            //     url: window.location.origin+'/getcontactlist/',
            //     datatype: 'json',
            //     delay: 250,
            //     minimumInputLength: 3,
            //     data: function (params) {
            //         var query = {
            //             search: params.term,
            //             type: 'public'
            //         }
            //         //console.log(query);
            //         // Query parameters will be ?search=[term]&type=public
            //         return query;
            //     },
            //     processResults: function (data, params) {
            //         params.page = params.page || 1;
            //         //console.log(data);
            //         return {
            //             results: data.results,
            //             pagination: {
            //                 more: (params.page * 30) < data.total_count
            //             }
            //         };
            //     },
            //     cache: true
            // },
        })

    </script>
@endsection
