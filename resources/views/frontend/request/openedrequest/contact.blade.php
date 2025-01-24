
<div class="row">
{{--   @dump($Acontact)--}}
    @isset($Acontact)
        <div class="col-md-10">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        {{__('List of Contacts')}}
                    </h3>
                    <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <dl class="row">

                        @foreach($Acontact as  $person)
                            <x-front.small-contact-card :person="$person" >
                            </x-front.small-contact-card>
                        @endforeach

                    </dl>
                </div>
                    <!-- /.card-body -->
            </div>
        </div>
    @endisset
        <div class="col-md-2">
            <div class="callout callout-primary">
                <button type="button" id="valid" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-addContact"><i class="fas fa-user-plus"></i> {{(__('Add Contact'))}}</button>
            </div>
        </div>

</div>




<div class="modal fade" id="modal-addContact">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Add Contacts')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{__('Contacts List')}} {{$Ticket->org_name}}</label>
                    <select id="addContactList" class="select2bs4" multiple="multiple" data-placeholder="{{__('Select one or more contacts')}}"
                            style="width: 100%;">

                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="button" class="btn btn-primary"  id="saveAddContact">{{__('Save')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('js')
    <script>

        //Ajout des contacts au ticket
        $("#saveAddContact").click(function() {
            //alert('on ajoute ces contacts et on raffaichit la page '+$("#addContactList").val());
            jQuery.ajax({
                url: window.location.origin + '/addcontactlist',
                datatype: 'json',
                method : "POST",
                data      :{
                    "_token": "{{ csrf_token() }}",
                    'list' : $("#addContactList").val(),
                    'request_id': {{$Ticket->id}}
                },
                success: function(data){
                    toastr['success']('{{__('Person(s) added as contact to the request')}}','{{__('Contact added')}}');
                },
                error :function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete : function(data){
                    //window.location.reload();
                    console.log(data);
                    $('#modal-addContact').modal('hide');

                    location.reload()
                }
            });
        });

        //Utilisé pour la liste multiple des contacts à ajouter
        $('.select2bs4').select2({
            theme: 'bootstrap4',
            placeholder: "{{__('Select one or more contacts')}}",
            maximumSelectionLength: 5, //On limite l'ajout à 5 contacts d'un coup
            ajax: {
                url: window.location.origin+'/getcontactlist/',
                datatype: 'json',
                delay: 250,
                minimumInputLength: 3,
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    //console.log(query);
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    //console.log(data);
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
        })
    </script>
@endpush
