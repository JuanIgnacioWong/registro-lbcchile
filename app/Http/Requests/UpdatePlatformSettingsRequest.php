<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatformSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform_name' => ['required', 'string', 'max:120'],
            'inscription_heading' => ['required', 'string', 'max:120'],
            'inscription_subheading' => ['required', 'string', 'max:220'],
            'inscription_intro_text' => ['nullable', 'string', 'max:1200'],
            'institutional_logo' => ['nullable', 'file', 'max:2048', 'mimes:png,jpg,jpeg,webp,svg'],
            'payment_button_url' => ['nullable', 'url', 'max:500'],
            'payment_button_text' => ['required', 'string', 'max:80'],
            'roster_template' => ['nullable', 'file', 'max:10240', 'mimes:pdf,xls,xlsx,docx'],
            'notification_email' => ['nullable', 'email:rfc,dns', 'max:120'],
            'max_file_size_kb' => ['required', 'integer', 'min:512', 'max:20480'],
            'allowed_doc_extensions' => ['required', 'string', 'max:120'],
            'allowed_image_extensions' => ['required', 'string', 'max:80'],
            'success_message' => ['nullable', 'string', 'max:500'],
            'correction_instructions' => ['nullable', 'string', 'max:1200'],
            'brand_primary' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'brand_secondary' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'brand_accent' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
        ];
    }
}
