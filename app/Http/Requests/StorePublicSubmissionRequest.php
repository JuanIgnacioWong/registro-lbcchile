<?php

namespace App\Http\Requests;

use App\Models\PlatformSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicSubmissionRequest extends FormRequest
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
            'season_id' => ['required', 'integer', Rule::exists('seasons', 'id')->where('is_active', true)],
            'division_id' => [
                'required',
                'integer',
                Rule::exists('divisions', 'id')->where(function ($query) {
                    $query->where('season_id', $this->integer('season_id'))->where('is_active', true);
                }),
            ],
            'club_id' => [
                'required',
                'integer',
                Rule::exists('clubs', 'id')->where(function ($query) {
                    $query
                        ->where('season_id', $this->integer('season_id'))
                        ->where('division_id', $this->integer('division_id'))
                        ->where('is_active', true);
                }),
            ],
            'responsible_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email:rfc,dns', 'max:120'],
            'club_logo' => ['required', 'file', 'max:2048', 'mimes:'.implode(',', $allowedImages)],
            'payment_receipt' => ['required', 'file', 'max:'.$maxFileSize, 'mimes:'.implode(',', $allowedDocs)],
            'players_roster' => ['required', 'file', 'max:'.$maxFileSize, 'mimes:'.implode(',', $allowedDocs)],
            'observations' => ['nullable', 'string', 'max:2000'],
        ];
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
