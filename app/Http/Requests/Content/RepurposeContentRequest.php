<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class RepurposeContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
            'content' => ['required', 'string', 'min:20'],
            'source_type' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'campaign_id.required' => 'Le Blueprint est obligatoire.',
            'campaign_id.exists' => 'Le Blueprint sélectionné est introuvable.',
            'content.required' => 'Le contenu brut est obligatoire.',
            'content.min' => 'Le contenu brut doit contenir au moins 20 caractères.',
        ];
    }
}