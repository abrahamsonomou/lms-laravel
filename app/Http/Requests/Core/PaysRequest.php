<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaysRequest extends FormRequest
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
        $paysId = $this->route('pays')?->id;
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'code' => [$required, 'string', 'max:10', Rule::unique('pays', 'code')->ignore($paysId)],
            'iso2' => [$required, 'string', 'size:2'],
            'iso3' => [$required, 'string', 'size:3'],
            'nom' => [$required, 'string', 'max:255'],
            'indicatif_telephone' => ['nullable', 'string', 'max:10'],
            'active' => ['boolean'],
        ];
    }
}
