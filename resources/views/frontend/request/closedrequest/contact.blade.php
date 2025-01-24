
<div class="row">
    @isset($Acontact)
        <div class="col-md-12">
            <div class="card card-dark card-outline">
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

</div>
