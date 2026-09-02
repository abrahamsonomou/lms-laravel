<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Cours\Chapitre;
use App\Models\Cours\Cours;
use App\Models\Cours\Lecon;
use App\Models\Cours\ModuleCours;
use App\Models\Evaluation\Evaluation;
use App\Models\Evaluation\Question;

trait ResolvesCourseOwnership
{
    protected function assertOwnsEvaluation(Evaluation $evaluation, int $userId): void
    {
        abort_unless($evaluation->formation?->created_by === $userId, 403);
    }

    protected function assertOwnsQuestion(Question $question, int $userId): void
    {
        abort_unless($question->evaluation?->formation?->created_by === $userId, 403);
    }

    protected function assertOwnsCours(Cours $cours, int $userId): void
    {
        abort_unless($cours->formation?->created_by === $userId, 403);
    }

    protected function assertOwnsModule(ModuleCours $module, int $userId): void
    {
        abort_unless($module->cours?->formation?->created_by === $userId, 403);
    }

    protected function assertOwnsChapitre(Chapitre $chapitre, int $userId): void
    {
        abort_unless($chapitre->moduleCours?->cours?->formation?->created_by === $userId, 403);
    }

    protected function assertOwnsLecon(Lecon $lecon, int $userId): void
    {
        abort_unless($lecon->chapitre?->moduleCours?->cours?->formation?->created_by === $userId, 403);
    }
}
