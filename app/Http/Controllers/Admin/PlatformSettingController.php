<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePlatformSettingsRequest;
use App\Models\PlatformSetting;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlatformSettingController extends Controller
{
    private const KEYS = [
        'platform_name',
        'inscription_heading',
        'inscription_subheading',
        'inscription_intro_text',
        'institutional_logo',
        'payment_button_url',
        'payment_button_text',
        'roster_template',
        'notification_email',
        'max_file_size_kb',
        'allowed_doc_extensions',
        'allowed_image_extensions',
        'success_message',
        'correction_instructions',
        'brand_primary',
        'brand_secondary',
        'brand_accent',
    ];

    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => $this->currentSettings(),
        ]);
    }

    public function update(UpdatePlatformSettingsRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('institutional_logo')) {
            $currentLogo = PlatformSetting::value('institutional_logo');
            if ($currentLogo && Storage::disk('local')->exists($currentLogo)) {
                Storage::disk('local')->delete($currentLogo);
            }

            $payload['institutional_logo'] = $request->file('institutional_logo')->store('settings', 'local');
        }

        if ($request->hasFile('roster_template')) {
            $currentTemplate = PlatformSetting::value('roster_template');
            if ($currentTemplate && Storage::disk('local')->exists($currentTemplate)) {
                Storage::disk('local')->delete($currentTemplate);
            }

            $payload['roster_template'] = $request->file('roster_template')->store('settings', 'local');
        }

        foreach (self::KEYS as $key) {
            if (! array_key_exists($key, $payload)) {
                continue;
            }

            $value = is_string($payload[$key]) ? trim($payload[$key]) : (string) $payload[$key];
            PlatformSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('platform:shared-settings');

        $this->auditLogger->log($request->user(), 'platform_settings_updated', 'platform_setting', null, 'Configuracion global actualizada.');

        return back()->with('success', 'Configuracion actualizada correctamente.');
    }

    private function currentSettings(): array
    {
        $stored = PlatformSetting::query()->whereIn('key', self::KEYS)->pluck('value', 'key')->all();

        return array_merge([
            'platform_name' => 'Registro LBC Chile',
            'inscription_heading' => 'Inscipción de Clubes',
            'inscription_subheading' => 'Plataforma independiente para carga y revision de antecedentes deportivos.',
            'inscription_intro_text' => 'Completa los datos institucionales y sube los antecedentes oficiales en un solo envio.',
            'institutional_logo' => null,
            'payment_button_url' => null,
            'payment_button_text' => 'Pagar cuota',
            'roster_template' => null,
            'notification_email' => null,
            'max_file_size_kb' => '10240',
            'allowed_doc_extensions' => 'pdf,xls,xlsx,docx',
            'allowed_image_extensions' => 'png,jpg,jpeg,webp,svg',
            'success_message' => 'Antecedentes enviados correctamente. Te contactaremos tras la revision administrativa.',
            'correction_instructions' => 'Este enlace permite subir una correccion oficial para tu club. El sistema conserva todo el historial por version.',
            'brand_primary' => '#0C2340',
            'brand_secondary' => '#1F4E8C',
            'brand_accent' => '#35BDFE',
        ], $stored);
    }
}
