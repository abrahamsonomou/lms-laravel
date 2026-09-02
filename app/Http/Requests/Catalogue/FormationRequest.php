<?php

namespace App\Http\Requests\Catalogue;

use App\Models\Catalogue\Formation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormationRequest extends FormRequest
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
        return [
            'titre' => ['required', 'string', 'max:255'],
            'categorie_id' => ['nullable', 'integer', 'exists:categories_formations,id'],
            'description' => ['nullable', 'string'],
            'objectifs' => ['nullable', 'string'],
            'niveau' => ['nullable', Rule::in(Formation::NIVEAUX)],
            'duree' => ['nullable', 'integer', 'min:0'],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'devise_id' => ['nullable', 'integer', 'exists:devises,id'],
            'type' => ['required', Rule::in(Formation::TYPES)],
            'statut' => ['required', Rule::in(Formation::STATUTS)],
            'date_publication' => ['nullable', 'date'],
            'date_expiration' => ['nullable', 'date', 'after_or_equal:date_publication'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
