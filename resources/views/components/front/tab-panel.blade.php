@props([
'active' => true,
'id',
'title',
])

<div class="@if($active) active @endif tab-pane" id="{{ $id }}">
    {{ $slot }}
</div>


