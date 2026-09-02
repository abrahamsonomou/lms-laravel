---
paths:
  - 'app/Http/Controllers/**,app/Models/Catalogue/**,resources/views/catalogue/**'
---

# Catalogue

## Module Catalogue/Formations (patterns)
Le module Catalogue est décliné sur 3 surfaces réutilisant les mêmes briques:
- Validation partagée: `App\Http\Requests\Catalogue\FormationRequest` (backoffice ET formateur).
- Persistance partagée: trait `App\Http\Controllers\Concerns\HandlesFormationPersistence::buildFormationData()` — génère un slug UNIQUE (base Str::slug + suffixe -n), un `code` par défaut, et gère l'upload d'image (disque public, suppression de l'ancienne). Le contrôleur ajoute `created_by`/`updated_by`.
- Champs de formulaire partagés: partial `resources/views/catalogue/_formation-fields.blade.php` (SANS <form>/@csrf/submit) inclus par les vues create/edit backoffice et formateur. Le <form enctype=multipart>, @csrf, @method et boutons sont dans chaque create/edit.
- Constantes sur `Formation`: STATUTS (BROUILLON/PUBLIE/ARCHIVE), TYPES (GRATUITE/PAYANTE/ABONNEMENT), NIVEAUX. Scope `publie()` (statut PUBLIE + non expiré). Relation `createur()` via created_by.
- Espace formateur: formations filtrées par `created_by = auth id`; ownership vérifiée via `abort_unless($formation->created_by === user->id, 403)`.
- Public: route model binding par slug `{formation:slug}`; `show()` fait `abort_unless($formation->isPublie(), 404)`.
- Badges statut (couleurs): BROUILLON=secondary, PUBLIE=success, ARCHIVE=dark.
Répliquer ce schéma (FormRequest + trait de persistance + partial de champs partagé) pour les entités multi-surfaces des prochains modules (Cours, Leçons...).
