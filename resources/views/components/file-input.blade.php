@props(['label' => null, 'name'])

<label class="block text-sm font-medium text-slate-700">
    @if($label)
        <span>{{ $label }}</span>
    @endif
    <input type="file" name="{{ $name }}" {{ $attributes->merge(['class' => 'panel-input']) }}>
</label>
