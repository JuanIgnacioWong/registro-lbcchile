<?php

namespace App\Http\Requests;

use App\Models\PlatformSetting;
use Illuminate\Foundation\Http\FormRequest;

class StoreCorrectionSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxFileSize = (int) PlatformSetting::value('max_file_size_kb', '10240');
        $allowedImages = $this->extensions('allowed_image_extensions', ['png', 'jpg', 'jpeg', 'webp', 'svg']);
        $allowedDocs = $this->extensions('allowed_doc_extensions', ['pdf', 'xls', 'xlsx', 'docx']);

        return [
            'club_logo' => ['nullable', 'file', 'max:2048', 'mimes:'.implode(',', $allowedImages)],
            'payment_receipt' => ['nullable', 'file', 'max:'.$maxFileSize, 'mimes:'.implode(',', $allowedDocs)],
            'players_roster' => ['nullable', 'file', 'max:'.$maxFileSize, 'mimes:'.implode(',', $allowedDocs)],
            'observations' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $hasFiles = $this->hasFile('club_logo')
                || $this->hasFile('payment_receipt')
                || $this->hasFile('players_roster');

            if (! $hasFiles) {
                $validator->errors()->add('files', 'Debes adjuntar al menos un archivo para la correccion.');
            }
        });
    }

    private function extensions(string $key, array $defaults): array
    {
        $value = PlatformSetting::value($key, implode(',', $defaults));

        $extensions = collect(explode(',', (string) $value))
            ->map(fn (string $item): string => strtolower(trim($item)))
            ->filter(fn (string $item): bool => (bool) preg_match('/^[a-z0-9]+$/', $item))
            ->values()
            ->all();

        return $extensions === [] ? $defaults : $extensions;
    }
}
