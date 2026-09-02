<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class TauxChangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'devise_source_id' => [$required, 'integer', 'exists:devises,id'],
            'devise_cible_id' => [$required, 'integer', 'exists:devises,id', 'different:devise_source_id'],
            'taux' => [$required, 'numeric', 'gt:0'],
            'date_effet' => [$required, 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_effet'],
            'source' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }
}
