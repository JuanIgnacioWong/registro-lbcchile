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
            'inscription_heading' => 'Inscripcion de Clubes',
            'inscription_subheading' => 'Plataforma independiente para carga y revision de antecedentes deportivos.',
            'payment_button_url' => 'https://lbcchile.com/pagos',
            'payment_button_text' => 'Pagar cuota',
            'notification_email' => 'registro@lbcchile.com',
            'max_file_size_kb' => '10240',
            'allowed_doc_extensions' => 'pdf,xls,xlsx,docx',
            'allowed_image_extensions' => 'png,jpg,jpeg,webp,svg',
        ];

        foreach ($defaults as $key => $value) {
            PlatformSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
