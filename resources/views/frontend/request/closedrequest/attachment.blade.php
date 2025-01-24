<div class="row">
    @isset($Aattach)

        <div class="col-md-12">
            <div class="card card-dark card-outline">
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
                                    Download <i class="fas fa-download"></i>
                                </a>

                            </div>

                        </div>

                    @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    @endif

</div>


