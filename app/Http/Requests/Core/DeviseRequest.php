<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviseRequest extends FormRequest
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
        $deviseId = $this->route('devise')?->id;
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'code' => [$required, 'string', 'max:10', Rule::unique('devises', 'code')->ignore($deviseId)],
            'symbole' => [$required, 'string', 'max:10'],
            'nom' => [$required, 'string', 'max:255'],
            'nombre_decimales' => ['sometimes', 'integer', 'min:0', 'max:6'],
            'active' => ['boolean'],
        ];
    }
}
