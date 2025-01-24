<div class="table-responsive">
    <table class="table">
        <tbody><tr>
            <th style="width:50%">{{__('Portal Version')}}</th>
            <td>{{Config::get('app.version')}}</td>
        </tr>
        <tr>
            <th>{{__('iTop Version')}}</th>
            <td>{{ $itop->version }} ({{$itop->name}})</td>
        </tr>
        <tr>
            <th>{{__('Environment')}}</th>
            <td>
                <?php  $itopCfg = config('itop');
                        $Cfg = $itopCfg[$user->itop_cfg];
                ?>
                {{$Cfg['name']}}
            </td>
        </tr>
        <tr>
            <th>{{__('User Linked between Portal & iTop')}}</th>
            <td>
                @if (isset($itopAccount) && $itopAccount->email != $user->email) <span class="text-warning"><i class="icon fas fa-exclamation-triangle"></i> {{__('Verify email')}}</span>
                @elseif (isset($itopAccount)) <span class="text-success"><i class="icon fas fa-check"></i></span>
                @else <span class="text-danger"><i class="icon fas fa-ban"></i></span>
                @endif

                </td>
        </tr>
        </tbody></table>
</div>
