<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class VilleRequest extends FormRequest
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
            'pays_id' => [$required, 'integer', 'exists:pays,id'],
            'nom' => [$required, 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'active' => ['boolean'],
        ];
    }
}
