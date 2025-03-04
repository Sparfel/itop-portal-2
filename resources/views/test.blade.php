{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1><i class="fa-solid fa-flask-vial"></i> {{__('Test')}}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">

                <!-- Request List -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-vial"></i>
                            {{__('Test')}}</h3>
                        <div class="card-tools">
                           <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" >
{{--                        <organization-location--}}
{{--                            :displayasrow="true"--}}
{{--                            :organization_id="selectedOrgId"--}}
{{--                            ref="organizationLocation"--}}
{{--                        >--}}
{{--                        </organization-location>--}}
                        <cascading-dropdown
                            ref="cascadingDropdown"
                            v-model:selectedOrganization="selectedOrganization"
                            v-model:selectedLocation="selectedLocation"
                        >
                        </cascading-dropdown>
                    </div>
                    <!-- /.card-body -->
                </div>
            </section>
            <!-- /.Left col -->
            <section class="col-lg-5 connectedSortable">


            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->


@endsection

@section('js')
    <script>


        $(document).ready(function() {
        // Code à exécuter lorsque la page est complètement chargée


        // Exemple : mettre à jour un élément du DOM
        // $('#someElement').text('Le contenu a été mis à jour après le chargement de la page');

        // Appeler une fonction
        myFunction();
    });

    function myFunction() {
    // Exemple de fonction à appeler
    console.log('Ma fonction');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url       : '/administration/edititopuser',
            type      : 'post',
            dataType : 'json',
            data      :{
                'itop_user_id' : 28
            },
            success : function(data){

                console.log("Données récupérées :", data);

                let locations = data.Locations.map(loc => ({
                    id: loc.id,
                    name: loc.name
                }));
                console.log('##Myfunction, location_id = '+ data.itopuser.location_id);
                // Accéder au composant Vue et mettre à jour ses valeurs
                if (window.vueInstance && window.vueInstance.$refs.cascadingDropdown) {
                    window.vueInstance.$refs.cascadingDropdown.updateValues(data.itopuser.org_id, data.itopuser.location_id, locations);
                }

            },
            error :function(xhr, ajaxOptions, thrownError){
                alert(xhr.status);
                alert(thrownError);
                toastr.warning('{{ __('Nothing was found') }}');
            },
            complete : function(data){
                //$('#modal-new-itop-user').html(data.responseText);
                console.log(data.responseJSON);

                // $('#modal-new-itop-user').modal('show');

            }
        });
    }
    </script>

@endsection
