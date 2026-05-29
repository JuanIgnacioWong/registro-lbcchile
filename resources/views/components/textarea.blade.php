@props(['label' => null, 'name', 'rows' => 4])

<label class="block text-sm font-medium text-slate-700">
    @if($label)
        <span>{{ $label }}</span>
    @endif
    <textarea name="{{ $name }}" rows="{{ $rows }}" {{ $attributes->merge(['class' => 'panel-input']) }}>{{ $slot }}</textarea>
</label>
