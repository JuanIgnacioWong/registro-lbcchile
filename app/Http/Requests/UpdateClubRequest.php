<?php

namespace App\Http\Requests;

use App\Models\Club;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Club $club */
        $club = $this->route('club');

        return [
            'season_id' => ['required', 'integer', Rule::exists('seasons', 'id')],
            'division_id' => [
                'required',
                'integer',
                Rule::exists('divisions', 'id')->where(fn ($query) => $query->where('season_id', $this->integer('season_id'))),
            ],
            'name' => ['required', 'string', 'max:160'],
            'slug' => [
                'required',
                'string',
                'max:180',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('clubs', 'slug')
                    ->where(fn ($query) => $query
                        ->where('season_id', $this->integer('season_id'))
                        ->where('division_id', $this->integer('division_id')))
                    ->ignore($club->id),
            ],
            'logo_path' => [
                'nullable',
                'file',
                'max:2048',
                'mimes:png,jpg,jpeg,webp,svg',
                'mimetypes:image/png,image/jpeg,image/webp,image/svg+xml',
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
