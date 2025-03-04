<div class="modal fade" id="modal-new-itop-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="far fa-address-card"></i> {{__('Add a new User')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div class="callout callout-info">
                    <h5>Données de iTop Users</h5>
                    <p>Nous ajoutons ici un enregistrement dans la table iTop Users. Nous pourrons ensuite, à partir
                        de cet enregistrement, créé les comptes et fiches contact iTop.</p>

                </div>

                <form action="/storeitopuser" method="post" id="storeitopuser" name="storeitopuser" enctype="multipart/form-data">

                    <dl class="row">
                        {{ csrf_field() }}

                        <input type="hidden" id="id">

                        <dd class="col-sm-12">
                            <label>{{__('Login / email')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" id="email" class="form-control" placeholder="Email">
                            </div>
                        </dd>


                        <dd class="col-sm-6">
                            <label>{{__('First name')}}</label>
                            <input type="first_name" id="first_name" class="form-control" placeholder="{{__('First name')}}">
                        </dd>

                        <dd class="col-sm-6">
                            <label>{{__('Last name')}}</label>
                            <input type="last_name" id="last_name" class="form-control" placeholder="{{__('Last name')}}">
                        </dd>

                        <dd class="col-sm-6">
                            <label>{{__('Phone')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" id="phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" inputmode="text">
                            </div>
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

                        <dd class="col-sm-12">
{{--                            <organization-location--}}
{{--                                :displayasrow="true"--}}
{{--                                ref="organizationLocation"--}}
{{--                            >--}}
{{--                            </organization-location>--}}
                            <cascading-dropdown
                                ref="cascadingDropdown"
                                v-model:selectedOrganization="selectedOrganization"
                                v-model:selectedLocation="selectedLocation"
                            >
                            </cascading-dropdown>
                        </dd>

                        {{--                            <dt class="col-sm-2">{{__('Type')}}</dt>--}}
                        <dd class="col-sm-6">
                            <label>{{__('Profile')}}</label>
                            <select name="role" id="role" class="form-control"  data-placeholder="{{__('Profile')}}"
                                    style="width: 100%;">
                                @foreach($Roles as $id=>$Role)--}}
                                    <option value={{$Role->id}}> {{$Role->name}}</option>
                                @endforeach
                            </select>

                        </dd>

                        <div id="infoCompl" class="col-sm-6">
                            <div class="row" id="flag">


                            </div>
                            <input type="hidden" id="is_local">
                            <input type="hidden" id="is_in_itop">
                            <input type="hidden" id="has_itop_account">
                        </div>

                        <dd class="col-sm-12">
                            <label>{{__('Comment') }}</label>
                            <textarea required id="comment" class="form-control" name="comment" ></textarea>
                        </dd>


                    </dl>
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

            $('#cancelButton').on('click', function () {
                $('#modal-new-itop-user').modal('hide');
            });

            // Fermeture via la croix
            $('.close').on('click', function () {
                $('#modal-new-itop-user').modal('hide');
            });

            $('#saveChange').on('click', function (e) {
                jQuery.ajax({
                    url: window.location.origin + '/administration/storeitopuser',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'id' : $("#id").val(),
                        'first_name' : $("#first_name").val(),
                        'last_name' : $("#last_name").val(),
                        'email' : $("#email").val(),
                        'phone' : $("#phone").val(),
                        'mobile' : $("#mobile").val(),
                        'organization' : $("#organization").val(),
                        'location' : $("#location").val(),
                        'role' : $("#role").val(),
                        'is_local' : $("#is_local").val(),
                        'is_in_itop' : $("#is_in_itop").val(),
                        'has_itop_account' : $("#has_itop_account").val(),
                        'comment' : $("#comment").val(),
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
                        $('#modal-filter').modal('hide');
                        location.reload()
                    }
                });
            });
        });
    </script>

@endpush
