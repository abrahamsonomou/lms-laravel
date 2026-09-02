---
paths:
  - 'app/Http/Controllers/Teacher/**,app/Http/Controllers/Student/**,app/Services/**'
  - 'app/Http/Controllers/Teacher/Evaluation*,app/Http/Controllers/Student/EvaluationController.php,app/Services/QuizGrader.php'
---

# Services

## Contenu pédagogique : builder & progression
Hiérarchie Formation → Cours → ModuleCours → Chapitre → Lecon → Contenu.

Builder formateur (`Teacher\CoursController` + `Teacher\CoursBuilderController`):
- Propriété vérifiée via le trait `Concerns\ResolvesCourseOwnership` qui remonte la chaîne jusqu'à `formation.created_by` (assertOwnsCours/Module/Chapitre/Lecon). TOUJOURS `->load(...)` la chaîne avant de vérifier.
- Le builder gère modules/chapitres/leçons par des formulaires POST/DELETE classiques (pas de JS), chaque action `return back()`. `ordre` auto-incrémenté via `->max('ordre') + 1`.
- Ajout de leçon: crée aussi un `Contenu` si `url` ou `fichier` fourni (fichier sur disque public, dossier `contenus/`). Types de leçon: `Lecon::TYPES`.

Espace étudiant:
- Inscription: `Student\InscriptionController` crée `FormationEtudiant` (firstOrCreate) depuis la fiche catalogue publique (partial `public.catalogue._enroll` : étudiant→inscription réelle, invité→register).
- Lecteur (`Student\FormationController@show`): exige une inscription (`abort_if` sinon 403), charge l'arbre, `?lecon=` sélectionne la leçon courante.
- Progression: `Student\ProgressionController@complete` upsert un `Progression` (terminee=true) puis `Services\ProgressionCalculator::recalculate()` recalcule le % sur `FormationEtudiant` (100% → statut TERMINE). `ProgressionCalculator` compte les leçons via `whereHas('chapitre.moduleCours.cours', formation_id)`.

## Module Évaluations/Quiz (patterns)
Quiz : Evaluation → Question → Reponse ; passage : Tentative → ReponseEtudiant.

Builder formateur (`Teacher\EvaluationController` + `Teacher\EvaluationBuilderController`): mêmes patterns que le builder de cours. Propriété via `Concerns\ResolvesCourseOwnership::assertOwnsEvaluation/assertOwnsQuestion` (remonte à `evaluation.formation.created_by`). Questions/réponses ajoutées par formulaires POST/DELETE, `return back()`. Types: `Evaluation::TYPES` (QUIZ/EXAMEN), `Question::TYPES` (QCM/MULTIPLE/VRAI_FAUX). Une réponse a `libelle` + `correcte` (bool).

Passage étudiant (`Student\EvaluationController`): exige l'inscription (`FormationEtudiant`). `start` crée une `Tentative` EN_COURS (respecte `tentatives_max`). Le formulaire de passage envoie `answers[question_id]` = id (radio QCM/VRAI_FAUX) ou `answers[question_id][]` = ids (checkbox MULTIPLE) ; le contrôleur normalise tout en tableau d'int.

Correction (`Services\QuizGrader::grade`): une question est correcte si l'ensemble trié des réponses sélectionnées == ensemble trié des réponses correctes. note = points_obtenus / points_totaux * note_max ; statut REUSSI si note >= note_min (défaut note_max/2), sinon ECHOUE. Chaque réponse d'étudiant est persistée dans `reponsesEtudiants` (reponse_texte = JSON des ids sélectionnés).
