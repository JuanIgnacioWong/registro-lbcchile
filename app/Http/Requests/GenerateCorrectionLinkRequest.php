<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateCorrectionLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'season_id' => ['required', 'integer', Rule::exists('seasons', 'id')],
            'division_id' => [
                'required',
                'integer',
                Rule::exists('divisions', 'id')->where(fn ($query) => $query->where('season_id', $this->integer('season_id'))),
            ],
            'club_id' => [
                'required',
                'integer',
                Rule::exists('clubs', 'id')->where(fn ($query) => $query
                    ->where('season_id', $this->integer('season_id'))
                    ->where('division_id', $this->integer('division_id'))),
            ],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
