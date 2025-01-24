<!-- About Me Box -->
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">About Me</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <strong><i class="fas fa-sitemap"></i> {{$pref->org_name}}</strong>

        <p class="text-muted">
           @if ($user->is_multi_organization == 1)
                {{__('Multi-organization')}}
           @else
               {{__('Mono-organization')}}
           @endif
        </p>

        <hr>

        <strong><i class="fas fa-map-marker-alt mr-1"></i> {{$pref->loc_name}}</strong>

        <p class="text-muted">
            @empty($pref->loc_id)
                {{__('Your profile is not associated with any location')}}
            @endempty


        </p>
        <hr>
        <strong><i class="far fa-file-alt mr-1"></i> {{__('Notes')}}</strong>

        <p class="text-muted">
            @isset($user->role){{ $user->role->name}}@endisset
        </p>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
