
{{--@isset($Aattach)--}}
{{--    <div class="row">--}}
{{--        @foreach( $Aattach as $attach)--}}
{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box">--}}
{{--                    <span class="info-box-icon bg-primary">--}}
{{--                        @switch($attach->contents->mimetype)--}}
{{--                            @case('image/jpeg')--}}
{{--                            <i class="far fa-file-image"></i>--}}
{{--                            @break--}}
{{--                            @case('image/png')--}}
{{--                            <i class="far fa-file-image"></i>--}}
{{--                            @break--}}
{{--                            @case('application/pdf')--}}
{{--                            <i class="far fa-file-pdf"></i>--}}
{{--                            @break--}}
{{--                            @case('application/vnd.ms-excel')--}}
{{--                            <i class="far fa-file-excel"></i>--}}
{{--                            @break--}}
{{--                            @case('application/zip')--}}
{{--                            <i class="far fa-file-archive"></i>--}}
{{--                            @break--}}
{{--                            @case('application/x-7z-compressed')--}}
{{--                            <i class="far fa-file-archive"></i>--}}
{{--                            @break--}}
{{--                            @case('application/msword')--}}
{{--                            <i class="far fa-file-word"></i>--}}
{{--                            @break--}}
{{--                            @default--}}
{{--                            <i class="far fa-file-alt"></i>--}}
{{--                        @endswitch--}}
{{--                    </span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text ">{{($attach->contents->filename)}}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}
{{--@endif--}}

<div class="row">
    @isset($Aattach)

        <div class="col-md-8">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-paperclip"></i>
                        {{__('Attachments')}}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                    @foreach( $Aattach as $attach)

                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-light">
                                <div class="inner">
                                                            <h3></h3>
                                    <p><b>{{($attach->contents->filename)}}</b></p>
                                    <br>
                                    <p>{{round(strlen(base64_decode($attach->contents->data))/ 1024,2)}} Ko</p>
                                </div>
                                <div class="icon">
                                    @switch($attach->contents->mimetype)
                                        @case('image/jpeg')
                                        <i class="far fa-file-image"></i>
                                        @break
                                        @case('image/png')
                                        <i class="far fa-file-image"></i>
                                        @break
                                        @case('application/pdf')
                                        <i class="far fa-file-pdf"></i>
                                        @break
                                        @case('application/vnd.ms-excel')
                                        <i class="far fa-file-excel"></i>
                                        @break
                                        @case('application/zip')
                                        <i class="far fa-file-archive"></i>
                                        @break
                                        @case('application/x-7z-compressed')
                                        <i class="far fa-file-archive"></i>
                                        @break
                                        @case('application/msword')
                                        <i class="far fa-file-word"></i>
                                        @break
                                        @case('application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                        <i class="far fa-file-word"></i>
                                        @break
                                        @default
                                        <i class="far fa-file-alt"></i>
                                    @endswitch
                                </div>
                                <a href="/downloadattachment/{{$attach->id}}" class="small-box-footer ">
                                    {{__('Download')}} <i class="fas fa-download"></i>
                                </a>

                            </div>

                        </div>

                    @endforeach
{{--                        <button type="button" id="downloadAll" class="btn btn-block btn-primary">Download all</button>--}}


                    </div>
                    @isset($Aattach)
                        <a id="downloadAll" class="btn btn-app bg-primary">
                            <span class="badge bg-dark">{{count($Aattach)}}</span>
                            <i class="far fa-file-archive"></i> Download all
                        </a>
                    @endisset
                </div>
                <!-- /.card-body -->

            </div>

        </div>
    @endif
    @can('upload_attachment')
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-upload"></i>
                        {{__('Add Attachments')}}
                    </h3>
                    <div class="card-tools"><button type="button" class="btn btn-tool float-left" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="file-dropzone">
{{--                    <div class="dropzone" id="file-dropzone"></div>--}}
                    <form action="{{route('uploadattachment')}}"
                          class="dropzone"
                          id="dropzone" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="dz-message" data-dz-message><span>{{__('Drop files here to upload') }}</span></div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    @endcan
</div>

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

@push('js')
{{--    <script src="{{asset('vendor/dropzone/dist/min/dropzone.min.js')}}" type="text/javascript"></script>--}}
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        Dropzone.autoDiscover = false;

        //Dropzone : Ajout de pièces jointes
        //Dropzone pour les pièces jointes
        var fileList = new Object();
        $('#dropzone').dropzone({
            url: '{{ route('uploadattachment') }}',
            method: 'post',
            //acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            maxFilesize: 2,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append("request_id", "{{$Ticket->id}}");
                });
            },
            removedfile: function(file)
            {
                var name = file.upload.filename;
                if (file.upload.progress == 100)
                {var fileid = fileList[file.upload.uuid]['fileid'];}
                else {fileid = '-';}
                $.ajax({
                    type: 'POST',
                    url: '{{ route('removeattachment') }}',
                    data: { "_token": "{{ csrf_token() }}",
                        name: name,
                        file_id: fileid },
                    success: function (data){
                        console.log("File "+ fileid +" has been successfully removed!");
                        toastr['success']('{{__('File has been successfully removed!')}}','{{__('File removed')}}');
                        console.log(data);
                    },
                    error: function(e) {
                        console.log(e);
                    }});
                var fileRef;
                return (fileRef = file.previewElement) != null ?
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
            success: function (file, response) {
                fileList[file.upload.uuid] = { "fileName" : file.name, "fileid" : response.request_id };
                toastr['success']('{{__('File added to the request')}}','{{__('File added')}}');
            },
        });


// Download de toutes les PJ en 1 fois

        var zip = new JSZip();
        @isset($Aattach)
            @foreach( $Aattach as $attach){
                zip.file("{{$attach->contents->filename}}","{{$attach->contents->data}}",{base64: true});
            }
            @endforeach
        @endisset

        jQuery("#downloadAll").on("click", function () {
                getZip();
        });

        jQuery("#downloadAllSmall").on("click", function () {
            getZip();
        });

        function getZip(){
            zip.generateAsync({
                type: "base64"
            }).then(function(content) {
                var link = document.createElement('a');
                link.href = "data:application/zip;base64," + content;
                link.download = "{{$Ticket->ref}}.zip";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

    </script>
@endpush

