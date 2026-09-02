---
paths:
  - 'app/Http/Controllers/**,app/Models/User.php,resources/views/**'
---

# Views

## IAM v2 : vérification email + uploads (avatars/logos)
Vérification email: `User implements MustVerifyEmail`. Routes `verification.notice/verify/send` (contrôleur `Auth\EmailVerificationController`). Middleware `verified` appliqué aux groupes admin/teacher/student. Comptes créés hors inscription publique doivent être vérifiés via `markEmailAsVerified()` (email_verified_at N'EST PAS mass-assignable — utiliser markEmailAsVerified() ou forceFill). Changer d'email dans le profil réinitialise email_verified_at et renvoie le lien.

Uploads: disque `public` (lien créé via `php artisan storage:link`). Avatars dans `avatars/`, logos d'organisation dans `organisations/`. Validation `['nullable','image','max:2048']`. Toujours supprimer l'ancien fichier (`Storage::disk('public')->delete(...)`) avant d'en stocker un nouveau. Les formulaires avec upload DOIVENT avoir `enctype="multipart/form-data"`.

Avatar UI: composant Blade `<x-user-avatar :user="$user" size="sm|md|lg|xl" />` (photo si `avatarUrl()`, sinon initiales via `User::initials()`). Réutiliser partout où un avatar est affiché.

Tests d'upload: `Storage::fake('public')` + `UploadedFile::fake()->image(...)` + `Storage::disk('public')->assertExists(...)`.
