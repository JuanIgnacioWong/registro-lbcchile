<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'platform_name' => 'Registro LBC Chile',
            'inscription_heading' => 'Inscipción de Clubes',
            'inscription_subheading' => 'Plataforma independiente para carga y revision de antecedentes deportivos.',
            'inscription_intro_text' => 'Completa los datos institucionales y sube los antecedentes oficiales en un solo envio.',
            'payment_button_url' => 'https://lbcchile.com/pagos',
            'payment_button_text' => 'Pagar cuota',
            'notification_email' => 'registro@lbcchile.com',
            'max_file_size_kb' => '10240',
            'allowed_doc_extensions' => 'pdf,xls,xlsx,docx',
            'allowed_image_extensions' => 'png,jpg,jpeg,webp,svg',
            'success_message' => 'Antecedentes enviados correctamente. Te contactaremos tras la revision administrativa.',
            'correction_instructions' => 'Este enlace permite subir una correccion oficial para tu club. El sistema conserva todo el historial por version.',
            'brand_primary' => '#0C2340',
            'brand_secondary' => '#1F4E8C',
            'brand_accent' => '#35BDFE',
        ];

        foreach ($defaults as $key => $value) {
            PlatformSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
