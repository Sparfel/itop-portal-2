@props([
'input',
'name',
'required' => false,
'title',
'rows' => 3,
'title',
'label',
'options',
'value' => '',
'Values',
'multiple' => false,
])
<div class="form-group">
    @isset($title)
        <label for="{{ $name }}">@lang($title)</label>
    @endisset
    @if ($input === 'textarea')
        <textarea
            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            rows="{{ $rows }}"
            id="{{ $name }}"
            name="{{ $name }}"
            @if ($required) required @endif>{{ old($name, $value) }}</textarea>

    @elseif ($input === 'checkbox')
        <div class="custom-control custom-switch custom-switch-off-secondary custom-switch-on-primary">
{{--            <div class="custom-control custom-checkbox">--}}
                <input
{{--                    class="custom-control-input"--}}
                    id="{{ $name }}"
                    name="{{ $name }}"
                    type="checkbox"
                    class="custom-control-input"
                    data-bootstrap-switch data-size="mini"
                    {{ filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                <label
{{--                    class="custom-control-label"--}}
                    class="custom-control-label"
                    for="{{ $name }}">
                    {{ __($label) }}
                </label>
{{--            </div>--}}
        </div>
    @elseif ($input === 'select')
        <select
            @if($required) required @endif
        class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            name="{{ $name }}"
            id="{{ $name }}">
            @foreach($options as $option)
                <option
                    value="{{ $option }}"
{{--                    {{ old($name) ? (old($name) == $option ? 'selected' : '') : ($option == $value ? 'selected' : '') }}>--}}
                        {{$option == $value ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
     @elseif ($input === 'select2')
        <select
            @if($required) required @endif
        class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            name="{{ $name }}"
            id="{{ $name }}">
            @foreach($options as $key=>$option)
                <option
                    value="{{ $key }}"
{{--                    {{ old($name) ? (old($name) == $option ? 'selected' : '') : ($option == $value ? 'selected' : '') }}>--}}
                        {{$key == $value ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
    @elseif ($input === 'selectMultiple')
        <select
            multiple
            @if($required) required @endif
{{--            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"--}}
            class="select2bs4" multiple="multiple" style="width:100%;"
            name="{{ $name }}[]"
            id="{{ $name }}">
{{--            @foreach($options as $id => $title)--}}
{{--                <option--}}
{{--                    value="{{ $id }}"--}}
{{--                    {{ old($name) ?--}}
{{--                            (in_array($id, old($name)) ? 'selected' : '')--}}
{{--                            :--}}
{{--                            ($values->has($id) ? 'selected' : '') }}>--}}
{{--                    {{ $title }}--}}
{{--                </option>--}}
{{--            @endforeach--}}
            @foreach($options as $id => $title)
                <option
                    value="{{ $id }}"
                    {{ old($name) ? (in_array($id, old($name)) ? 'selected' : '') : ($values->contains('id', $id) ? 'selected' : '') }}>
                    {{ $title }}
                </option>
            @endforeach
        </select>

    @elseif ($input === 'selectMultiple_has')
        <select
            multiple
            @if($required) required @endif
            {{--            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"--}}
            class="select2bs4" multiple="multiple" style="width:100%;"
            name="{{ $name }}[]"
            id="{{ $name }}">
                        @foreach($options as $id => $title)
                            <option
                                value="{{ $id }}"
                                {{ old($name) ?
                                        (in_array($id, old($name)) ? 'selected' : '')
                                        :
                                        ($values->has($id) ? 'selected' : '') }}>
                                {{ $title }}
                            </option>
                        @endforeach

        </select>
    @else
        <input
            type="text"
            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif>


    @endif
    @if ($errors->has($name))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif

</div>
