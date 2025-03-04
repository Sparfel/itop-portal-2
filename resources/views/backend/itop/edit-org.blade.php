<div class="modal fade" id="modal-edit-org">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-solid fa-building"></i> {{__('Organization')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="callout callout-info">
                    <h5>Données de iTop Organization</h5>
                    <p>Les données Organization proviennent de iTop et sont gérées par iTop. On peut cependant les enrichir ici.
                    Mais ces informations sont local au portail et ne sont pas remonté vers iTop.
                    La synchronisation conserve ces informations, elles seront perdues si vous supprimez et réimporter l'Organisation.</p>

                </div>

                <form action="/storeitoporg" method="post" id="storeitoporg" name="storeitoporg" enctype="multipart/form-data">

                    <dl class="row">
                        {{ csrf_field() }}

                        <dd class="col-sm-6">
                            <label>{{__('Id')}}</label>
                            <input type="Id" disabled id="id" class="form-control" placeholder="{{__('Id')}}">
                        </dd>

                        <dd class="col-sm-6">
                            <label>{{__('Name')}}</label>
                            <input type="name"  disabled id="name" class="form-control" placeholder="{{__('Name')}}">
                        </dd>

                        <dd class="col-sm-12">
                            <label>{{__('Delivery Model')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="deliverymodel_id_friendlyname" id="deliverymodel_id_friendlyname" class="form-control" placeholder="{{__('Delivery Model')}}">
                            </div>
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
                $('#modal-edit-org').modal('hide');
            });
            // Fermeture via la croix
            $('.close').on('click', function () {
                $('#modal-edit-org').modal('hide');
            });

            $('#saveChange').on('click', function (e) {
                jQuery.ajax({
                    url: window.location.origin + '/administration/storeitoporg',
                    datatype: 'json',
                    method : "POST",
                    data      :{
                        "_token": "{{ csrf_token() }}",
                        'id' : $("#id").val(),
                        'name' : $("#name").val(),
                        'deliverymodel_id_friendlyname' : $("#deliverymodel_id_friendlyname").val(),

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
