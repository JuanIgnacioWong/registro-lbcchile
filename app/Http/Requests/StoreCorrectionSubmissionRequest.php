<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCorrectionSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'club_logo' => [
                'nullable',
                'file',
                'max:2048',
                'mimes:png,jpg,jpeg,webp,svg',
                'mimetypes:image/png,image/jpeg,image/webp,image/svg+xml',
            ],
            'payment_receipt' => ['nullable', 'file', 'max:10240', 'mimes:pdf,xls,xlsx,docx'],
            'players_roster' => ['nullable', 'file', 'max:10240', 'mimes:pdf,xls,xlsx,docx'],
            'observations' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasFiles = $this->hasFile('club_logo')
                || $this->hasFile('payment_receipt')
                || $this->hasFile('players_roster');

            if (! $hasFiles) {
                $validator->errors()->add('files', 'Debes adjuntar al menos un archivo para la correccion.');
            }
        });
    }
}
