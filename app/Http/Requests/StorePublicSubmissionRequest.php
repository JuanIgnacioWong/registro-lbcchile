<?php

namespace App\Http\Requests;

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
            'club_logo' => [
                'required',
                'file',
                'max:2048',
                'mimes:png,jpg,jpeg,webp,svg',
                'mimetypes:image/png,image/jpeg,image/webp,image/svg+xml',
            ],
            'payment_receipt' => ['required', 'file', 'max:10240', 'mimes:pdf,xls,xlsx,docx'],
            'players_roster' => ['required', 'file', 'max:10240', 'mimes:pdf,xls,xlsx,docx'],
            'observations' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
