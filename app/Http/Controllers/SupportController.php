<?php

namespace App\Http\Controllers;

use App\Models\Chat\Conversation;
use App\Services\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = Conversation::query()
            ->where('type', 'SUPPORT')
            ->when(! $user->isStaff(), fn ($query) => $query->where('created_by', $user->id))
            ->with('creator')
            ->withCount('messages')
            ->latest()
            ->paginate(15);

        return view('support.index', compact('conversations'));
    }

    public function create(): View
    {
        return view('support.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $conversation = Conversation::query()->create([
            'type' => 'SUPPORT',
            'titre' => $validated['titre'],
            'created_by' => $request->user()->id,
            'active' => true,
        ]);

        $conversation->participants()->attach($request->user()->id, ['role' => 'CLIENT', 'date_entree' => now()]);

        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'type' => 'TEXTE',
            'contenu' => $validated['message'],
            'date_envoi' => now(),
        ]);

        return redirect()->route('support.show', $conversation)->with('success', 'Votre demande a été envoyée au support.');
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeAccess($request, $conversation);

        $conversation->load(['messages' => fn ($query) => $query->orderBy('date_envoi'), 'messages.user', 'creator']);

        return view('support.show', compact('conversation'));
    }

    public function reply(Request $request, Conversation $conversation, Notifier $notifier): RedirectResponse
    {
        $this->authorizeAccess($request, $conversation);

        $validated = $request->validate(['contenu' => ['required', 'string']]);

        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'type' => 'TEXTE',
            'contenu' => $validated['contenu'],
            'date_envoi' => now(),
        ]);

        // Notifier l'autre partie (le client si c'est le support qui répond, sinon le support n'a pas de destinataire unique).
        if ($request->user()->isStaff() && $conversation->creator !== null && $conversation->created_by !== $request->user()->id) {
            $notifier->notify(
                $conversation->creator,
                'support',
                'Réponse du support',
                "Le support a répondu à votre demande « {$conversation->titre} ».",
                ['conversation_id' => $conversation->id],
                email: false,
                actionUrl: route('support.show', $conversation),
                actionText: 'Voir la conversation',
            );
        }

        return redirect()->route('support.show', $conversation)->with('success', 'Message envoyé.');
    }

    private function authorizeAccess(Request $request, Conversation $conversation): void
    {
        $user = $request->user();
        abort_unless($user->isStaff() || $conversation->created_by === $user->id, 403);
    }
}
