<div class="modal fade" id="modal-edit-loc">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-solid fa-location-dot"></i> {{__('Location')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="callout callout-info">
                    <h5>Données de iTop Location</h5>
                    <p>Les données Location proviennent de iTop et sont gérées par iTop. On peut cependant les enrichir ici.
                    Mais ces informations sont local au portail et ne sont pas remontées vers iTop.
                    La synchronisation conserve ces informations, elles seront perdues si vous supprimez et réimporter la Location.</p>

                </div>
{{--                <div class="callout callout-warning">--}}
{{--                    <h5>Post Save</h5>--}}
{{--                    <p>Pensez à mettre iTop à jour si modification des champs communs (adresses)</p>--}}

{{--                </div>--}}

                <form action="/storeitoploc" method="post" id="storeitoploc" name="storeitoploc" enctype="multipart/form-data">

                    <dl class="row">
                        {{ csrf_field() }}

                        <dd class="col-sm-2">
                            <label>{{ __('Id') }}</label>
                            <input type="text" disabled id="id" class="form-control" placeholder="{{ __('Id') }}">
                        </dd>

                        <dd class="col-sm-5">
                            <label>{{ __('Name') }}</label>
                            <input type="text" disabled id="name" class="form-control" placeholder="{{ __('Name') }}">
                        </dd>

                        <dd class="col-sm-5">
                            <label>{{ __('Organization') }}</label>
                            <input type="text" disabled id="org_name" class="form-control" placeholder="{{ __('Organization') }}">
                        </dd>


                        <dd class="col-sm-6">
                            <label>{{ __('Address') }}</label>
                            <input type="text" id="address" class="form-control" placeholder="{{ __('Address') }}">
                        </dd>

                        <dd class="col-sm-3">
                            <label>{{ __('Postal Code') }}</label>
                            <input type="text" id="postal_code" class="form-control" placeholder="{{ __('Postal Code') }}">
                        </dd>

                        <dd class="col-sm-3">
                            <label>{{ __('City') }}</label>
                            <input type="text" id="city" class="form-control" placeholder="{{ __('City') }}">
                        </dd>

                        <dd class="col-sm-3">
                            <label>{{ __('Country') }}</label>
                            <input type="text" id="country" class="form-control" placeholder="{{ __('Country') }}">
                        </dd>
                        <dd class="col-sm-3">
                            <label>{{ __('Phone Code') }}</label>
                            <input type="text" id="phonecode" class="form-control" placeholder="{{ __('Phone Code') }}">
                        </dd>

                        <dd class="col-sm-3">
                            <x-front.tab-panel-input
                                label="{{ __('Is Active') }}"
                                name="is_active"
                                :value="1"
                                input="checkbox">
                            </x-front.tab-panel-input>
                        </dd>

                        <dd class="col-sm-3">
                            <x-front.tab-panel-input
                                label="{{ __('Is Localized') }}"
                                name="is_localized"
                                :value="0"
                                input="checkbox">
                            </x-front.tab-panel-input>
                        </dd>

                        <dd class="col-sm-6">
                            <label>{{ __('Latitude') }}</label>
                            <input type="text" id="latitude" class="form-control" placeholder="{{ __('Latitude') }}">
                        </dd>

                        <dd class="col-sm-6">
                            <label>{{ __('Longitude') }}</label>
                            <input type="text" id="longitude" class="form-control" placeholder="{{ __('Longitude') }}">
                        </dd>



                        <dd class="col-sm-6">
                            <label>{{ __('Delivery Model ID') }}</label>
                            <input type="text" id="deliverymodel_id" class="form-control" placeholder="{{ __('Delivery Model ID') }}">
                        </dd>

                        <dd class="col-sm-6">
                            <label>{{ __('Delivery Model Friendly Name') }}</label>
                            <input type="text" id="deliverymodel_id_friendlyname" class="form-control" placeholder="{{ __('Delivery Model Friendly Name') }}">
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

    <script>
        $(document).ready(function() {
            $('#cancelButton').on('click', function () {
                $('#modal-edit-loc').modal('hide');
            });
            // Fermeture via la croix
            $('.close').on('click', function () {
                $('#modal-edit-loc').modal('hide');
            });

            $('#saveChange').on('click', function (e) {
                jQuery.ajax({
                    url: window.location.origin + '/administration/storeitoploc',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'id': $("#id").val(),
                        'name': $("#name").val(),
                        'org_id': $("#org_id").val(),
                        'phonecode': $("#phonecode").val(),
                        'address': $("#address").val(),
                        'postal_code': $("#postal_code").val(),
                        'city': $("#city").val(),
                        'country': $("#country").val(),
                        'latitude': $("#latitude").val(),
                        'longitude': $("#longitude").val(),
                        'is_active': $("#is_active").prop("checked") ? 1 : 0,
                        'is_localized': $("#is_localized").prop("checked") ? 1 : 0,
                        'deliverymodel_id': $("#deliverymodel_id").val(),
                        'deliverymodel_id_friendlyname': $("#deliverymodel_id_friendlyname").val()

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
                        $('#modal-edit-loc').modal('hide');
                        location.reload()
                    }
                });
            });
        });
    </script>

@endpush
