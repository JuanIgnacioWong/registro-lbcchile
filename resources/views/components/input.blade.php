@props(['label' => null, 'name', 'type' => 'text'])

<label class="block text-sm font-medium text-slate-700">
    @if($label)
        <span>{{ $label }}</span>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'panel-input']) }}>
</label>
