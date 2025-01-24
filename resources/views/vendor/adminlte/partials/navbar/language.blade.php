
<ul class="navbar-nav ml-auto">
    <!-- Language Dropdown Menu -->

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            @if (session('locale') == 'en' || is_null(session('locale'))) <i class="flag-icon flag-icon-us"></i>
            @else <i class="flag-icon flag-icon-{{ session('locale') }}"></i>
            @endif
        </a>

        <div id="flags" class="dropdown-menu" aria-labelledby="navbarDropdownFlag">
            @foreach(config('app.languages') as $code=>$locale)

                @if($locale != session('locale'))
                    <a href="{{ route('language', $code) }}" class="dropdown-item">
{{--                    <a href="#" class="dropdown-item">--}}
                        @if ($code == 'en') <i class="flag-icon flag-icon-us mr-2"></i> {{$locale}}
                        @else <i class="flag-icon flag-icon-{{$code}} mr-2"></i> {{$locale}}
                        @endif
                    </a>

                @endif
            @endforeach
        </div>
    </li>

</ul>
