<x-admin-layout>
    <x-slot name="heading">Configuracion</x-slot>

    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}" class="max-w-4xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        <div><label class="text-sm font-medium">Nombre de plataforma</label><input name="platform_name" value="{{ old('platform_name', $settings['platform_name']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Logo institucional</label><input type="file" name="institutional_logo" class="mt-1 w-full rounded border-slate-300" accept=".png,.jpg,.jpeg,.webp,.svg"></div>
        <div><label class="text-sm font-medium">URL boton de pago</label><input name="payment_button_url" value="{{ old('payment_button_url', $settings['payment_button_url']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Texto boton de pago</label><input name="payment_button_text" value="{{ old('payment_button_text', $settings['payment_button_text']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Plantilla oficial de nomina</label><input type="file" name="roster_template" class="mt-1 w-full rounded border-slate-300" accept=".pdf,.xls,.xlsx,.docx"></div>
        <div><label class="text-sm font-medium">Correo receptor notificaciones</label><input name="notification_email" value="{{ old('notification_email', $settings['notification_email']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Tamano maximo de archivos (KB)</label><input type="number" name="max_file_size_kb" value="{{ old('max_file_size_kb', $settings['max_file_size_kb']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Formatos documentos permitidos</label><input name="allowed_doc_extensions" value="{{ old('allowed_doc_extensions', $settings['allowed_doc_extensions']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Formatos imagen permitidos</label><input name="allowed_image_extensions" value="{{ old('allowed_image_extensions', $settings['allowed_image_extensions']) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <button class="rounded bg-red-600 px-4 py-2 text-white">Guardar configuracion</button>
    </form>
</x-admin-layout>
