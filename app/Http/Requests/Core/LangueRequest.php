<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LangueRequest extends FormRequest
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
        $langueId = $this->route('langue')?->id;
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'code' => [$required, 'string', 'max:10', Rule::unique('langues', 'code')->ignore($langueId)],
            'nom' => [$required, 'string', 'max:255'],
            'locale' => [$required, 'string', 'max:10'],
            'active' => ['boolean'],
        ];
    }
}
