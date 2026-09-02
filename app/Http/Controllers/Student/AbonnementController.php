<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Abonnement\Abonnement;
use App\Models\Abonnement\Plan;
use App\Models\Paiement\MoyenPaiement;
use App\Services\AbonnementService;
use App\Services\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbonnementController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $historique = Abonnement::query()
            ->whereHas('utilisateurs', fn ($query) => $query->whereKey($user->id))
            ->with('plan')
            ->latest('date_debut')
            ->get();

        return view('student.abonnements.index', [
            'actif' => $user->abonnementActif()?->load('plan'),
            'historique' => $historique,
        ]);
    }

    public function checkout(Request $request, Plan $plan): View|RedirectResponse
    {
        abort_unless($plan->active, 404);

        if ($request->user()->hasAbonnementActif()) {
            return redirect()->route('student.abonnements.index')->with('info', 'Vous avez déjà un abonnement actif.');
        }

        return view('student.abonnements.checkout', [
            'plan' => $plan->load('devise'),
            'moyens' => MoyenPaiement::query()->where('active', true)->with('fournisseur')->get(),
        ]);
    }

    public function subscribe(Request $request, Plan $plan, AbonnementService $service, Notifier $notifier): RedirectResponse
    {
        abort_unless($plan->active, 404);

        if ($request->user()->hasAbonnementActif()) {
            return redirect()->route('student.abonnements.index')->with('info', 'Vous avez déjà un abonnement actif.');
        }

        $validated = $request->validate([
            'moyen_paiement_id' => ['required', 'integer', 'exists:moyens_paiement,id'],
        ]);

        $moyen = MoyenPaiement::query()->where('active', true)->findOrFail($validated['moyen_paiement_id']);

        $abonnement = $service->subscribe($request->user(), $plan, $moyen);

        $notifier->notify(
            $request->user(),
            'abonnement',
            'Abonnement activé',
            "Votre abonnement « {$plan->nom} » est actif jusqu'au ".$abonnement->date_fin?->format('d/m/Y').'.',
            ['abonnement_id' => $abonnement->id],
            actionUrl: route('student.abonnements.index'),
            actionText: 'Voir mon abonnement',
        );

        return redirect()->route('student.abonnements.index')->with('success', 'Abonnement activé ! Vous avez désormais accès à toutes les formations.');
    }

    public function toggleRenew(Request $request, Abonnement $abonnement): RedirectResponse
    {
        abort_unless($abonnement->utilisateurs()->whereKey($request->user()->id)->exists(), 403);

        $abonnement->update(['auto_renew' => ! $abonnement->auto_renew]);

        return back()->with('success', $abonnement->auto_renew
            ? 'Renouvellement automatique activé.'
            : 'Renouvellement automatique désactivé.');
    }
}
