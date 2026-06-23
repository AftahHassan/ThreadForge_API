<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'target_audience' => ['sometimes', 'nullable', 'string', 'max:255'],
            'tone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'max_characters' => ['sometimes', 'required', 'integer', 'min:50', 'max:1000'],
            'max_hashtags' => ['sometimes', 'required', 'integer', 'min:0', 'max:10'],
            'style_rules' => ['sometimes', 'nullable', 'string'],
        ];
    }
}