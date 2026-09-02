<?php

namespace App\Http\Controllers\Concerns;

use App\Http\Requests\Catalogue\FormationRequest;
use App\Models\Catalogue\Formation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesFormationPersistence
{
    /**
     * Build the fillable attributes for a formation, resolving a unique slug,
     * a default code and handling the cover image upload.
     *
     * @return array<string, mixed>
     */
    protected function buildFormationData(FormationRequest $request, ?Formation $formation = null): array
    {
        $data = $request->safe()->except('image');
        $data['slug'] = $this->uniqueSlug($data['titre'], $formation);
        $data['code'] ??= $formation?->code ?? Str::upper(Str::slug($data['titre'], '_'));

        if ($request->hasFile('image')) {
            if ($formation?->image !== null) {
                Storage::disk('public')->delete($formation->image);
            }
            $data['image'] = $request->file('image')->store('formations', 'public');
        }

        return $data;
    }

    private function uniqueSlug(string $titre, ?Formation $formation = null): string
    {
        $base = Str::slug($titre) ?: 'formation';
        $slug = $base;
        $suffix = 1;

        while (
            Formation::query()
                ->where('slug', $slug)
                ->when($formation !== null, fn ($query) => $query->whereKeyNot($formation->id))
                ->exists()
        ) {
            $slug = "{$base}-".(++$suffix);
        }

        return $slug;
    }
}
