<?php

namespace App\Services;

use App\Models\Evaluation\Evaluation;
use App\Models\Evaluation\Tentative;

class QuizGrader
{
    /**
     * Grade a submitted attempt, persist the student's answers and update the attempt.
     *
     * @param  array<int, array<int, int>>  $answers  question_id => [selected reponse_id, ...]
     */
    public function grade(Tentative $tentative, array $answers): Tentative
    {
        $evaluation = $tentative->evaluation()->with('questions.reponses')->first();

        $totalPoints = 0.0;
        $earnedPoints = 0.0;

        foreach ($evaluation->questions as $question) {
            $points = (float) $question->points;
            $totalPoints += $points;

            $correctIds = $question->reponses->where('correcte', true)->pluck('id')->sort()->values()->all();
            $selectedIds = collect($answers[$question->id] ?? [])->map(fn ($id): int => (int) $id)->sort()->values()->all();

            $isCorrect = $correctIds === $selectedIds && $correctIds !== [];
            $earnedPoints += $isCorrect ? $points : 0.0;

            $tentative->reponsesEtudiants()->create([
                'question_id' => $question->id,
                'reponse_id' => $selectedIds[0] ?? null,
                'reponse_texte' => json_encode($selectedIds),
                'points' => $isCorrect ? $points : 0,
                'correcte' => $isCorrect,
            ]);
        }

        $noteMax = (float) ($evaluation->note_max ?? 20);
        $note = $totalPoints > 0 ? round($earnedPoints / $totalPoints * $noteMax, 2) : 0.0;
        $seuil = (float) ($evaluation->note_min ?? ($noteMax / 2));

        $tentative->update([
            'score' => $earnedPoints,
            'note' => $note,
            'date_fin' => now(),
            'statut' => $note >= $seuil ? 'REUSSI' : 'ECHOUE',
        ]);

        return $tentative->refresh();
    }

    /**
     * Total number of gradable points across an evaluation.
     */
    public function totalPoints(Evaluation $evaluation): float
    {
        return (float) $evaluation->questions()->sum('points');
    }
}
