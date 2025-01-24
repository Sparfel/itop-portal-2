<div class="col-md-8">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-pencil-alt"></i>
                {{__('New Ticket')}}
            </h3>
            <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
            <!-- /.card-header -->
        <div class="card-body">
{{--            <form action="/newrequest" method="post" id="newRequest" enctype="multipart/form-data">--}}
            <dl class="row">
                {{ csrf_field() }}
                <input type="hidden" id="mode" name="mode" value="basic"/>
                <dd class="col-sm-12">
                    <label>{{__('Title')}}</label>
                    <input type='text' id='title' name='title' class="form-control"  required placeholder="{{__('Few words briefly describing your request')}}">
                </dd>
               <dd class="col-sm-6">
                    <location :location_id="{{$user_loc_id}}"></location>
                </dd>
                    {{--                            <dt class="col-sm-2">{{__('Type')}}</dt>--}}
                <dd class="col-sm-6">
                    <label>{{__('Request Type')}}</label>
                    <select name="type" id="type" class="form-control"  data-placeholder="{{__('Type')}}"
                                style="width: 100%;">
                            <option value="incident"> Incident</option>
                            <option value="service_request"> Services Request</option>
{{--                            <option value="other"> Other</option>--}}
                    </select>

                </dd>

                <dd class="col-sm-12">
                    <label>{{__('Description') }}</label>
                    <textarea required id="summernote" class="summernote form-control" name="description" ></textarea>
                </dd>
            </dl>
                <input hidden type="submit" id="validNewLog" value="Valider">
{{--                <dd class="col-sm-12">--}}
{{--                    <div class="dropzone" id="myDropzone" style="background-color:lightgray;">--}}
{{--                        <div class="dz-message" data-dz-message><span>{{__('Drop files here to upload') }}</span></div>--}}
{{--                    </div>--}}
{{--                </dd>--}}
{{--            </form>--}}
            <!-- /.card-body -->
        </div>
    </div>
</div>

<div class="col-md-4">

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
        <button type="button" name="valid" id="valid-basic" class="btn btn-primary btn-block"><i class="fas fa-check"></i> {{__('Create')}}</button>
    </div>

</div>


