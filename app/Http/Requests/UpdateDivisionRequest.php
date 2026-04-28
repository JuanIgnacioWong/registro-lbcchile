<?php

namespace App\Http\Requests;

use App\Models\Division;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDivisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Division $division */
        $division = $this->route('division');

        return [
            'season_id' => ['required', 'integer', Rule::exists('seasons', 'id')],
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:140',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('divisions', 'slug')
                    ->where(fn ($query) => $query->where('season_id', $this->integer('season_id')))
                    ->ignore($division->id),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
