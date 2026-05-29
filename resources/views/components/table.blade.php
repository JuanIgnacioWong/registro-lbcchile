<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-200']) }}>
    <table class="min-w-full text-sm">
        {{ $slot }}
    </table>
</div>
