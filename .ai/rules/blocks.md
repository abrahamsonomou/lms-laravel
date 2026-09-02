---
paths:
  - 'app/Http/Controllers/Backoffice/Studio*,app/Http/Controllers/Public/LandingController.php,resources/views/public/blocks/**'
---

# Blocks

## Module Studio no-code (patterns)
Constructeur de pages no-code. Un `StudioProject` (type LANDING) contient des `StudioPage`. Les blocs d'une page sont stockés dans `StudioPage.contenu_json` (casté array) — PAS dans studio_components (non utilisé pour ce MVP).

Types de blocs: `StudioPageController::BLOCS` = hero/texte/cta. Chaque bloc = tableau associatif `{type, titre?, sous_titre?, contenu?, bouton_texte?, bouton_url?}`. `addBlock` valide + `array_filter` (retire les null) + append ; `removeBlock(page, index)` fait `array_splice` + `array_values`. Slug de page unique global (route publique) via helper.

Backoffice (`admin.studio.*`): StudioProjectController (CRUD projet, param route `{studio}`), StudioPageController (store page sous projet, `builder` = éditeur méta+blocs, addBlock/removeBlock, update meta avec slug unique). L'éditeur est en formulaires POST classiques (pas de drag-drop JS).

Rendu public: `Public\LandingController@show(StudioPage $page)` route `/p/{page:slug}` (name `landing.show`), `abort_unless($page->active, 404)`. La vue `public.landing` (autonome) boucle `contenu_json` et `@include('public.blocks.'.$type)` (garde `in_array($type, BLOCS)`). Partials de blocs dans `resources/views/public/blocks/`. Seed démo: `StudioSeeder` (page `/p/bienvenue`).
