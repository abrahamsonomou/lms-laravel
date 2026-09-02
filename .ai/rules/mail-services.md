---
paths:
  - 'app/Mail/**,app/Services/Notifier.php'
---

# Mail Services

## Emails transactionnels
Les emails transactionnels sont émis PAR le `Services\Notifier` : `notify(...)` crée la notification in-app ET envoie un email (Mailable `App\Mail\EvenementMail`, template markdown `resources/views/emails/evenement.blade.php`) sauf si `email: false`.

Signature: `notify(user, type, titre, message, data=[], email=true, actionUrl=null, actionText=null)`. Le sujet et le titre de l'email = `$titre`, le corps = `$message`, plus un bouton optionnel (actionUrl/actionText). Événements câblés: inscription (lien formation), paiement (lien facture), certificat (lien certificat). Les seeders passent `email: false` pour ne rien envoyer au seeding.

Config: `MAIL_MAILER=log` par défaut en dev (rien n'est réellement envoyé, écrit dans laravel.log) ; en test `phpunit.xml` force `MAIL_MAILER=array` + `QUEUE_CONNECTION=sync`. Tests: `Mail::fake()` puis `Mail::assertSent(EvenementMail::class, fn ($m) => $m->hasTo(...) && $m->titre === ...)` — bien utiliser `::class`, pas la classe nue. EvenementMail n'est PAS `ShouldQueue` (envoi synchrone, pas besoin de worker). Pour un vrai envoi: configurer MAIL_MAILER=smtp/ses/... dans .env.
