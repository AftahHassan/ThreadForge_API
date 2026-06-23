<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'tone' => ['nullable', 'string', 'max:255'],
            'max_characters' => ['required', 'integer', 'min:50', 'max:1000'],
            'max_hashtags' => ['required', 'integer', 'min:0', 'max:10'],
            'style_rules' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du Blueprint est obligatoire.',
            'max_characters.required' => 'Le nombre maximum de caractères est obligatoire.',
            'max_characters.integer' => 'Le nombre maximum de caractères doit être un nombre.',
            'max_hashtags.required' => 'Le nombre maximum de hashtags est obligatoire.',
            'max_hashtags.integer' => 'Le nombre maximum de hashtags doit être un nombre.',
        ];
    }
}