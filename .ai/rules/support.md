---
paths:
  - 'app/Http/Controllers/SupportController.php,resources/views/support/**'
---

# Support

## Module Support / messagerie
Support = conversations `type=SUPPORT` (modèles Chat: Conversation/Message). `SupportController` unique partagé (routes `support.*` sous middleware `auth`, tous rôles). Un client (non-staff) ouvre un ticket (`store`: crée la Conversation + participant + 1er Message), voit/répond à SES conversations ; le staff voit et répond à TOUTES.

« Staff » = `User::isStaff()` (SUPER_ADMIN/ADMIN_*/SUPPORT) — distinct de `isAdmin()` car le rôle SUPPORT n'est pas admin mais gère le support. `homeRouteName()` route un SUPPORT vers `support.index`. Accès: `abort_unless($user->isStaff() || $conversation->created_by === $user->id, 403)`. Quand le staff répond, notification in-app (`type=support`, email:false) au créateur.

Pas de temps réel (POST + reload). Les vues utilisent `auth()->user()->isStaff()` pour afficher la colonne Client et masquer le bouton « Nouvelle demande ».
