---
paths:
  - 'resources/views/**,app/Http/Controllers/**,routes/web.php'
---

# Controllers

## Architecture UI (Geeks) + IAM / rôles
UI basée sur le template Geeks UI (Bootstrap 5). Assets copiés dans `public/geeks/assets`, référencés via `asset('geeks/assets/...')` (PAS de Vite pour ces assets). `@popperjs` dans le chemin réel (pas `%40`).

Layouts Blade partagés dans `resources/views/layouts/`:
- `public.blade.php` (navbar+footer) pour le site public
- `auth.blade.php` (carte centrée) pour login/register/forgot/reset
- `dashboard.blade.php` (sidebar+topbar) pour backoffice + espaces teacher/student. La sidebar (`partials/dashboard-sidebar`) s'adapte au rôle via `auth()->user()->isAdmin()/isFormateur()/isEtudiant()`.
Partials communs: `partials/head`, `partials/scripts` (@stack styles/scripts), `partials/flash`.
Sections dashboard: `title`, `page-title`, `page-subtitle`(opt), `page-actions`(opt), `content`. Sections public/auth: `title`, `content` (+ `auth-title`/`auth-subtitle`).

IAM/Auth (session web, SANS Sanctum/Breeze):
- Contrôleurs dans `App\Http\Controllers\Auth\*` (login, register, password reset via broker natif).
- Middleware `role` (alias dans bootstrap/app.php → `EnsureUserHasRole`), usage `role:SUPER_ADMIN,ADMIN_ORGANISATION,...`.
- Redirection post-login via `User::homeRouteName()` (admin.dashboard / teacher.dashboard / student.dashboard).
- Groupes de routes: `admin/` (name admin., rôles admin), `teacher/` (FORMATEUR), `student/` (ETUDIANT).
- L'inscription publique crée un ETUDIANT + profil Etudiant.
- Pagination Bootstrap 5 activée dans AppServiceProvider (`Paginator::useBootstrapFive()`).
- Comptes démo (DemoUsersSeeder): admin@lms.test / teacher@lms.test / student@lms.test, mot de passe `password`.
Reproduire ce pattern (Controller mince + vues par surface + middleware role) pour les prochains modules.
