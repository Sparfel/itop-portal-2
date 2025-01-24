@props([
'person'
])


<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch sm-text">
    <div class="card bg-light" style="width: 30rem;">

        @if (isset($person->comment) && !(strripos($person->comment,'[Astreinte]')===false))
            <div class=" ribbon-wrapper ribbon-lg  ribbon"><div class="ribbon bg-info">Astreinte</div>
            </div>
        @endif
        @if (isset($person->comment) && !(strripos($person->comment,'astreinte n1')===false))
                <div class=" ribbon-wrapper ribbon-lg  ribbon">
                    <div class="ribbon bg-info">Astreinte N1</div>
                </div>
        @endif
        @if (isset($person->comment) && !(strripos($person->comment,'astreinte n2')===false))
                <div class=" ribbon-wrapper ribbon-lg  ribbon"><div class="ribbon bg-secondary">Astreinte N2</div>
                </div>
        @endif

        <div class="card-header text-muted border-bottom-0">
            @if ($person->org_id ==='1') {{'FIVES'}}
            @else {{$person->org_name}}
            @endif
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-7">
                    <h2 class="lead"><b>{{$person->first_name}} {{$person->name}}</b></h2>
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="far fa-envelope"></i></span><a href="mailto:{{$person->email}}">{{$person->email}}</a></li>
                        @if(!is_null($person->phone) && $person->phone !='' )
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> {{$person->phone}}</li>
                        @endif
                        @isset($person->site_name)
                            <li class="small"><span class="fa-li"><i class="fas fa-map-marker-alt"></i></span> {{$person->site_name}}</li>
                        @endisset
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
