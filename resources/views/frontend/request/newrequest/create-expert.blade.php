{{--<form action="/newrequest" method="post" id="newRequest" enctype="multipart/form-data">--}}
<div class="col-md-8">
{{--    --}}
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-pencil-alt"></i>
                {{__('New Ticket')}}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <dl class="row">
                {{ csrf_field() }}
                <input type="hidden" id="mode" name="mode" value="expert"/>
                {{--                            <dt class="col-sm-2">{{__('Title')}}</dt>--}}
                <dd class="col-sm-12">
                    <label>{{__('Title')}}</label>
                    <input required type='text' id='title' name='title' class="form-control"
                           placeholder="{{__('Few words briefly describing your request')}}">
                </dd>
                {{--                            <dt class="col-sm-2">{{__('Location')}}</dt>--}}
                <dd class="col-sm-6">
                    <location :location_id="{{$user_loc_id}}"></location>
                </dd>
                {{--                            <dt class="col-sm-2">{{__('Type')}}</dt>--}}
                <dd class="col-sm-6">
                    <label>{{__('Request Type')}}</label>
                    <select name="type" id="type" class="form-control" data-placeholder="{{__('Type')}}"
                            style="width: 100%;">
                        <option value="user_issue">Incident</option>
                        <option value="service_request">Services Request</option>
                        <option value="other">Other</option>
                    </select>

                </dd>

                <dd class="col-sm-12">
                    <service-module :displayasrow="true"></service-module>
                </dd>

                <dd class="col-sm-12">
                    <label>{{__('Description') }}</label>
                    <textarea required id="summernote" class="summernote" name="description"></textarea>
                </dd>

            </dl>

            <input hidden type="submit" id="validNewLog" value="Valider">


        </div>
        <!-- /.card-body -->
    </div>
</div>

<div class="col-md-4">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tags"></i>
                {{__('Complements')}}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i
                        class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-4 ">{{__('Impact')}}</label>
                <div class="col-sm-8">
                    <select name="impact" id="impact" class="form-control" data-placeholder="{{__('Type')}}"
                            style="width: 100%;">
                        <option value="multiple_user">{{__('multiple user')}}</option>
                        <option value="single_user">{{__('single_user')}}</option>
                        <option value="undefined" SELECTED>{{__('undefined')}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 ">{{__('Issue Type')}}</label>
                <div class="col-sm-8">
                    <select name="issuetype" id="issuetype" class="form-control" data-placeholder="{{__('Type')}}"
                            style="width: 100%;">
                        <option value="">{{__('-- Select an issue type --')}}</option>
                        <option value="Automation">{{__('automation')}}</option>
                        <option value="Electrical">{{__('electrical')}}</option>
                        <option value="Mechanical">{{__('mechanical')}}</option>
                        <option value="Software">{{__('software')}}</option>
                    </select>
                </div>
            </div>

            <component-failure-type></component-failure-type>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-paperclip"></i>
                {{__('Attachments')}}
            </h3>
            <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <dd class="col-sm-12">
                <div class="dropzone" id="myDropzone" style="background-color:lightgray;">
                    <div class="dz-message" data-dz-message><span>{{__('Drop files here to upload') }}</span></div>
                </div>
            </dd>
        </div>
    </div>

    <div class="callout callout-primary">
        <button type="button" name="valid" id="valid-expert" class="btn btn-primary btn-block"><i class="fas fa-check"></i> {{__('Create')}}</button>
    </div>

</div>

{{--</form>--}}
