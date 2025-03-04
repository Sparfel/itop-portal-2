<div class="modal fade" id="modal-del-users">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title  text-danger"><i class="fas fa-radiation"></i></i> {{__('Delete User(s)')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <p class="text-center">

                <span id="nbDelete"></span>

            </p>


            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="submit" name="submit" value="submit" class="btn btn-danger" id="deleteUsers">{{__('Delete selection')}}</button>
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
            $('#deleteUsers').on('click', function (e) {
               //alert(Aselected.length);
                deleteUsers();
                $('#modal-del-users').modal('hide');
                document.getElementById("nbDelete").innerHTML ='';
            });
        });
    </script>

@endpush
