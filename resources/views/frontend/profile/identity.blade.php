<!-- Profile Image -->
<div class="card card-primary card-outline ">
    <div class="card-body box-profile">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle" src="{{asset('/storage/'.$user->avatar)}}" >
{{--                                            <img class="profile-user-img img-fluid img-circle" src="{{asset($user->avatar)}}" alt="{{$$module_name_singular->name}}">--}}
        </div>
        {{--                            <h3 class="profile-username text-center">{{$$module_name_singular->name}}</h3>--}}
        <p class="text-muted text-center">
{{--            {{ Auth::user()->adminlte_desc() }}--}}
            {{$pref->org_name}}
        </p>
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>{{__('Name')}}</b> <a class="float-right">{{$user->first_name}} {{$user->last_name}}</a>
            </li>
            <li class="list-group-item">
                <b>{{__('Login')}}</b> <a class="float-right">{{$user->email}}</a>
            </li>
            <li class="list-group-item">
                <b>{{__('Email')}}</b> <a class="float-right">{{$user->email}}</a>
            </li>

        </ul>
        <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
    </div>
    <!-- /.card-body -->
</div>
