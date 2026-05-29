<x-admin-layout>
    <x-slot name="heading">Configuración Global</x-slot>

    <x-card title="Parámetros institucionales" subtitle="Estos ajustes controlan textos, límites y branding del formulario público.">
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}" class="grid gap-4 lg:grid-cols-2">
            @csrf
            @method('PUT')

            <x-input label="Nombre de plataforma" name="platform_name" :value="old('platform_name', $settings['platform_name'])" required />
            <x-input label="Título de inscripción" name="inscription_heading" :value="old('inscription_heading', $settings['inscription_heading'])" required />
            <x-input label="Subtítulo de inscripción" name="inscription_subheading" :value="old('inscription_subheading', $settings['inscription_subheading'])" required />
            <x-input label="Correo receptor" name="notification_email" :value="old('notification_email', $settings['notification_email'])" />
            <x-input label="URL botón de pago" name="payment_button_url" :value="old('payment_button_url', $settings['payment_button_url'])" />
            <x-input label="Texto botón de pago" name="payment_button_text" :value="old('payment_button_text', $settings['payment_button_text'])" required />
            <x-input type="number" label="Tamaño máximo (KB)" name="max_file_size_kb" :value="old('max_file_size_kb', $settings['max_file_size_kb'])" required />
            <x-input label="Formatos documentos" name="allowed_doc_extensions" :value="old('allowed_doc_extensions', $settings['allowed_doc_extensions'])" required />
            <x-input label="Formatos imagen" name="allowed_image_extensions" :value="old('allowed_image_extensions', $settings['allowed_image_extensions'])" required />
            <x-input label="Color primario" name="brand_primary" :value="old('brand_primary', $settings['brand_primary'])" placeholder="#0C2340" />
            <x-input label="Color secundario" name="brand_secondary" :value="old('brand_secondary', $settings['brand_secondary'])" placeholder="#1F4E8C" />
            <x-input label="Color acento" name="brand_accent" :value="old('brand_accent', $settings['brand_accent'])" placeholder="#35BDFE" />

            <div class="lg:col-span-2">
                <x-textarea label="Texto introductorio" name="inscription_intro_text" rows="3">{{ old('inscription_intro_text', $settings['inscription_intro_text']) }}</x-textarea>
            </div>
            <div class="lg:col-span-2">
                <x-textarea label="Mensaje de éxito" name="success_message" rows="2">{{ old('success_message', $settings['success_message']) }}</x-textarea>
            </div>
            <div class="lg:col-span-2">
                <x-textarea label="Instrucciones de corrección" name="correction_instructions" rows="3">{{ old('correction_instructions', $settings['correction_instructions']) }}</x-textarea>
            </div>

            <x-file-input label="Logo institucional" name="institutional_logo" accept=".png,.jpg,.jpeg,.webp,.svg" />
            <x-file-input label="Plantilla de nómina" name="roster_template" accept=".pdf,.xls,.xlsx,.docx" />

            <div class="lg:col-span-2">
                <x-button type="submit">Guardar configuración</x-button>
            </div>
        </form>
    </x-card>
</x-admin-layout>
