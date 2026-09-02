---
paths:
  - 'app/Services/Notifier.php,app/Http/Controllers/NotificationController.php,resources/views/layouts/partials/dashboard-topbar.blade.php'
---

# Partials

## Notifications in-app (patterns)
Notifications in-app maison (PAS les notifications DB natives de Laravel).

Modèle: `App\Models\Notification\Notification` (table `notifications`: user_id, type, titre, message, data json, lu bool, date_lecture). Relation sur User: `appNotifications()` (hasMany, `->latest()`) — NOMMÉE ainsi pour NE PAS entrer en conflit avec `Notifiable::notifications()` (morphMany). Helper `unreadNotificationsCount()`.

Émission via `Services\Notifier::notify(user, type, titre, message, data=[])`. Types: `inscription`, `paiement`, `certificat` (couleurs/icônes mappées dans la vue). Câblé dans: `InscriptionController` (enroll gratuit, si wasRecentlyCreated), `CheckoutController@store` (après paiement), `CertificatController@store` (si certificat nouvellement créé).

UI: cloche dans `layouts/partials/dashboard-topbar` (badge `indicator indicator-primary` si non lues, 6 dernières). Page `notifications.index` (route sous middleware `auth`, tous rôles) avec marquage lu par item (`notifications.read`, ownership user_id) et `notifications.readAll`.

Pour un nouvel événement: injecter `Notifier` dans le contrôleur et appeler `notify(...)` au point d'action.
