<li @isset($item['id']) id="{{ $item['id'] }}" @endisset>

    <a class="dropdown-item {{ $item['class'] }}" href="{{ $item['href'] }}"
       @isset($item['target']) target="{{ $item['target'] }}" @endisset
       {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @isset($item['icon'])
            <i class="{{ $item['icon'] ?? '' }} {{
                isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''
            }}"></i>
        @endisset

        {{-- Text --}}
        {{ __($item['text']) }}

        {{-- Label (optional) --}}
        @isset($item['label'])
            <span class="badge badge-{{ $item['label_color'] ?? 'primary' }}">
                {{ __($item['label']) }}
            </span>
        @endisset

    </a>

</li>
