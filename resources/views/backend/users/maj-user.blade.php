<div class="modal fade" id="modal-maj-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="far fa-address-card"></i> {{__('Modify a User')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
{{--               <div class="callout callout-info">--}}
{{--                    <h5>Compte utilisateur sur le portail</h5>--}}
{{--                    <p></p>--}}
{{--                </div>--}}


                <form action="/storeuser" method="post" id="storeuser" name="storeuser" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">{{__('Settings')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">{{__('Professional Datas')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">{{__('Personal Datas')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">{{__('Picture')}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                    <input type="hidden" id="id">
                                    <dl class="row">
                                        <dd class="col-sm-6">
                                            <label>{{__('Login / email')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">@</span>
                                                </div>
                                                <input type="email" id="email" class="form-control" placeholder="Email">
                                            </div>
                                        </dd>

                                        <dd class="col-sm-6">
                                            <label><span id="passwordLabel"></span>{{__('Password')}}</label>
                                            <input type="password" id="password" class="form-control" placeholder="Password">
                                        </dd>

                                        <dd class="col-sm-6">
                                            <label>{{__('First name')}}</label>
                                            <input type="text" id="first_name" class="form-control" placeholder="{{__('First name')}}">
                                        </dd>

                                        <dd class="col-sm-4">
                                            <label>{{__('Last name')}}</label>
                                            <input type="text" id="last_name" class="form-control" placeholder="{{__('Last name')}}">
                                        </dd>

                                        <dd class="col-sm-2">
                                            <label>{{__('Gender')}}</label>
                                            <select name="role" id="gender" class="form-control"  data-placeholder="{{__('Gender')}}"
                                                    style="width: 100%;">
                                                <option value='M'> {{__('Male')}}</option>
                                                <option value='F'> {{__('Female')}}</option>
                                            </select>
                                        </dd>

                                        {{--                            <dt class="col-sm-2">{{__('Type')}}</dt>--}}
                                        <dd class="col-sm-6">
                                            <label>{{ __('Profile') }}</label>
                                            <select name="role[]" id="role" class="select2bs4" multiple="multiple" data-placeholder="{{ __('Profile') }}" style="width: 100%;">
                                                @foreach($Roles as $Role)
                                                    <option value="{{ $Role->id }}">{{ $Role->name }}</option>
                                                @endforeach
                                            </select>
                                        </dd>


                                        <div id="infoCompl" class="col-sm-6">
                                            <div class="row" id="flag">
                                                <dd class="col-sm-6"><label id="itop_id_label">{{__('Portal ID')}}</label>
                                                    <input disabled type="text" id="portal_id" class="form-control">
                                                </dd>
                                                <dd class="col-sm-6"><label id="itop_id_label">{{__('iTop ID')}}</label>
                                                    <input disabled type="text" id="itop_id" class="form-control">
                                                </dd>

                                            </div>

                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row" id="flag">
                                                <dd class="col-sm-6"><label id="domainLabel">{{__('Domain')}}</label>
                                                    <input disabled type="text" id="domain" class="form-control">
                                                </dd>
                                                <dd class="col-sm-6"><label id="guidLabel">{{__('GUID')}}</label>
                                                    <input disabled type="text" id="guid" class="form-control">
                                                </dd>

                                            </div>

                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row" id="flag">
                                                <dd class="col-sm-4"><label id="domainLabel"><i class="fas fa-lock"></i> {{__('PC 1')}}<i class="fas fa-lock-alt"></i> </label>
                                                    <input disabled type="text" id="pc1" class="form-control">
                                                </dd>
                                                <dd class="col-sm-4"><label id="guidLabel"><i class="fas fa-lock"></i> {{__('PC 2')}}</label>
                                                    <input disabled type="text" id="pc2" class="form-control">
                                                </dd>
                                                <dd class="col-sm-4"><label id="guidLabel"><i class="fas fa-lock"></i> {{__('PC 3')}}</label>
                                                    <input disabled type="text" id="pc3" class="form-control">
                                                </dd>

                                            </div>

                                        </div>


                                        <div class="col-sm-4">
                                            <x-front.tab-panel-input
                                                label="{{__('Is active')}}"
                                                name='is_active'
                                                input='checkbox' >
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-4">
                                            <x-front.tab-panel-input
                                                label="{{__('Is Staff')}}"
                                                name='is_staff'
                                                input='checkbox' >
                                            </x-front.tab-panel-input>
                                        </div>
                                         <div class="col-sm-4">
                                            <x-front.tab-panel-input
                                                label="{{__('Is Multi Organization')}}"
                                                name='is_multi_organization'
                                                input='checkbox' >
                                            </x-front.tab-panel-input>
                                        </div>


                                        <dd class="col-sm-6">
                                            <label>{{__('iTop')}}</label>
                                            <select name="itop_cfg" id="itop_cfg" class="form-control"  data-placeholder="{{__('iTop')}}"
                                                    style="width: 100%;">
                                                @foreach($itopCfg as $id=>$itop)--}}
                                                <option value={{$id}}> {{$itop['name']}}</option>
                                                @endforeach
                                            </select>

                                        </dd>


                                    </dl>
                                </div>


                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                    <dl class="row">
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Department')}}"
                                                name='department'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Service')}}"
                                                name='service'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>

                                        <dd class="col-sm-6">
                                            <label>{{__('Phone')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" id="internal_phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" inputmode="text">
                                            </div>
                                        </dd>

                                        <dd class="col-sm-6">
                                            <label>{{__('Office')}}</label>
                                            <select name="office" id="office_1" class="form-control"  data-placeholder="{{__('Office')}}"
                                                    style="width: 100%;">
{{--                                                @foreach($Offices as $id=>$Office)--}}
{{--                                                <option value={{$id}}> {{$Office}}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </dd>

                                        <dd class="col-sm-6">
                                            <label>{{__('Phone 2')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" id="internal_phone_2" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" inputmode="text">
                                            </div>
                                        </dd>

                                        <dd class="col-sm-6">
                                            <label>{{__('Office 2')}}</label>
                                            <select name="office" id="office_2" class="form-control"  data-placeholder="{{__('Office')}}"
                                                    style="width: 100%;">
{{--                                                @foreach($Offices as $id=>$Office)--}}
{{--                                                <option value={{$id}}> {{$Office}}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </dd>


                                        <dd class="col-sm-6">
                                            <label>{{__('Mobile')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" id="mobile" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" inputmode="text">
                                            </div>
                                        </dd>
                                        <dd class="col-sm-6">
                                            <label>{{__('DECT')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" id="dect_phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" inputmode="text">
                                            </div>
                                        </dd>

                                        <dd class="col-sm-6">
                                            <label>{{__('SDA')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" id="sda_phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" inputmode="text">
                                            </div>
                                        </dd>



                                        <dd class="col-sm-12">
                                            <label>{{__('About') }}</label>
                                            <textarea required id="about" class="form-control" name="about" ></textarea>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                    <dl class="row">
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Address')}}"
                                                name='address'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Postal Code')}}"
                                                name='postal_code'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('City')}}"
                                                name='city'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Country')}}"
                                                name='country'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>


                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                label="{{__('My address is visible')}}"
                                                name='is_address_visible'
                                                input='checkbox' >
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                label="{{__('Is localized')}}"
                                                name='is_localized'
                                                input='checkbox' >
                                            </x-front.tab-panel-input>
                                        </div>

                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Longitude')}}"
                                                name='longitude'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>
                                        <div class="col-sm-6">
                                            <x-front.tab-panel-input
                                                title="{{__('Latitude')}}"
                                                name='latitude'
                                                input='text'
                                                :required="false">
                                            </x-front.tab-panel-input>
                                        </div>




                                    </dl>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                    <dl class="row">
                                        <img class="profile-user-img img-fluid img-circle" style="object-fit: cover; border-radius: '50%'; width: 200px; height: 200px;" id="myAvatar" >
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>

                </form>
            </div>



            <div class="modal-footer justify-content-between">
                <button type="button" id="cancelButton" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="submit" name="submit" value="submit" class="btn btn-primary" id="saveChange">{{__('Save')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@push('js')

{{--    buggé ! remet à 0 les flag !!--}}
{{--    et bizarrerie sur les locations (pas activées?)--}}

    <script>
        $(document).ready(function() {

          // $('.select2').select2(); // Active le SELECT multiple
            $('#role').select2( {theme: 'bootstrap4'}); // Active le SELECT multiple
            // Fermeture via le bouton "Cancel"
            $('#cancelButton').on('click', function () {
                $('#modal-maj-user').modal('hide');
            });

// Fermeture via la croix
            $('.close').on('click', function () {
                $('#modal-maj-user').modal('hide');
            });


            $('#saveChange').on('click', function (e) {
                let roles = $("#role").val(); // Récupère tous les rôles sélectionnés
                jQuery.ajax({
                    url: window.location.origin + '/administration/storeuser',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'id' : $("#id").val(),
                        'first_name' : $("#first_name").val(),
                        'last_name' : $("#last_name").val(),
                        'gender' :  $("#gender").val(),
                        'email' : $("#email").val(),
                        'password' : $("#password").val(),
                        'itop_id' : $("#itop_id").val(),
                        // 'role' : $("#role").val(),
                        'role': roles,  // Envoyer les rôles sous forme de tableau
                        'guid' : $("#guid").val(),
                        'domain' : $("#domain").val(),
                        'pc1' : $("#pc1").val(),
                        'pc2' : $("#pc2").val(),
                        'pc3' : $("#pc3").val(),
                        'is_active' :document.getElementById('is_active').checked,
                        'is_staff' : document.getElementById('is_staff').checked,
                        'is_multi_organization' : document.getElementById('is_multi_organization').checked,
                        'itop_cfg' : $("#itop_cfg").val(),
                        //
                        'department' : $("#department").val(),
                        'service' : $("#service").val(),
                        'internal_phone' : $("#internal_phone").val(),
                        'office_id' : $("#office_1").val(),
                        'internal_phone_2' : $("#internal_phone_2").val(),
                        'office_id_2' : $("#office_2").val(),
                        'mobile' : $("#mobile").val(),
                        'dect_phone' : $("#dect_phone").val(),
                        'sda_phone' : $("#sda_phone").val(),
                        'about' : $("#about").val(),
                        //
                        'address' : $("#address").val(),
                        'postal_code' : $("#postal_code").val(),
                        'city' : $("#city").val(),
                        'country' : $("#country").val(),
                        'is_localized' : document.getElementById('is_localized').checked,
                        'is_address_visible' : document.getElementById('is_address_visible').checked,
                        'longitude' : $("#longitude").val(),
                        'latitude' : $("#latitude").val(),
                    },
                    success: function(data){
                    },
                    error :function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        console.log(thrownError);
                    },
                    complete : function(data){
                        //window.location.reload();
                        console.log(data);
                        $('#modal-maj-user').modal('hide');
                        location.reload()
                    }
                });
            });
        });
    </script>

@endpush
