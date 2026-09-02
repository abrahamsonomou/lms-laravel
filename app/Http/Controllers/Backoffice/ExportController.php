<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Facturation\Facture;
use App\Models\Progression\FormationEtudiant;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function factures(): StreamedResponse
    {
        $entetes = ['Numero', 'Client', 'Date', 'Total TTC', 'Devise', 'Statut'];

        return $this->streamCsv('factures.csv', $entetes, function ($handle): void {
            Facture::query()->with(['client', 'devise'])->orderBy('date_facture')->chunk(200, function ($factures) use ($handle): void {
                foreach ($factures as $facture) {
                    fputcsv($handle, [
                        $facture->numero,
                        $facture->client?->name ?? '',
                        $facture->date_facture?->format('Y-m-d') ?? '',
                        number_format((float) $facture->total_ttc, 2, '.', ''),
                        $facture->devise?->code ?? '',
                        $facture->statut,
                    ]);
                }
            });
        });
    }

    public function inscriptions(): StreamedResponse
    {
        $entetes = ['Etudiant', 'Formation', 'Date inscription', 'Progression', 'Statut'];

        return $this->streamCsv('inscriptions.csv', $entetes, function ($handle): void {
            FormationEtudiant::query()->with(['etudiant.user', 'formation'])->chunk(200, function ($inscriptions) use ($handle): void {
                foreach ($inscriptions as $inscription) {
                    fputcsv($handle, [
                        $inscription->etudiant?->user?->name ?? '',
                        $inscription->formation?->titre ?? '',
                        $inscription->date_inscription?->format('Y-m-d') ?? '',
                        number_format((float) $inscription->progression, 2, '.', ''),
                        $inscription->statut,
                    ]);
                }
            });
        });
    }

    /**
     * @param  array<int, string>  $entetes
     */
    private function streamCsv(string $nom, array $entetes, callable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($entetes, $rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $entetes);
            $rows($handle);
            fclose($handle);
        }, $nom, ['Content-Type' => 'text/csv']);
    }
}
