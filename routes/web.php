<?php

use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Backoffice\BiController;
use App\Http\Controllers\Backoffice\CategorieController;
use App\Http\Controllers\Backoffice\CertificatController as AdminCertificatController;
use App\Http\Controllers\Backoffice\CouponController;
use App\Http\Controllers\Backoffice\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backoffice\EtablissementController;
use App\Http\Controllers\Backoffice\ExportController;
use App\Http\Controllers\Backoffice\FactureController as AdminFactureController;
use App\Http\Controllers\Backoffice\FormationController as AdminFormationController;
use App\Http\Controllers\Backoffice\OrganisationController;
use App\Http\Controllers\Backoffice\PermissionController;
use App\Http\Controllers\Backoffice\PlanController;
use App\Http\Controllers\Backoffice\RapportController;
use App\Http\Controllers\Backoffice\RemboursementController;
use App\Http\Controllers\Backoffice\RoleController;
use App\Http\Controllers\Backoffice\StudioPageController;
use App\Http\Controllers\Backoffice\StudioProjectController;
use App\Http\Controllers\Backoffice\TransactionController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Public\CatalogueController;
use App\Http\Controllers\Public\CertificatController as PublicCertificatController;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PricingController;
use App\Http\Controllers\Student\AbonnementController;
use App\Http\Controllers\Student\CertificatController as StudentCertificatController;
use App\Http\Controllers\Student\CheckoutController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\EvaluationController as StudentEvaluationController;
use App\Http\Controllers\Student\FactureController as StudentFactureController;
use App\Http\Controllers\Student\FormationController as StudentFormationController;
use App\Http\Controllers\Student\InscriptionController;
use App\Http\Controllers\Student\ProgressionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Teacher\CoursBuilderController;
use App\Http\Controllers\Teacher\CoursController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\EvaluationBuilderController;
use App\Http\Controllers\Teacher\EvaluationController as TeacherEvaluationController;
use App\Http\Controllers\Teacher\FormationController as TeacherFormationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/formations', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/formations/{formation:slug}', [CatalogueController::class, 'show'])->name('catalogue.show');
Route::get('/verifier/{hash}', [PublicCertificatController::class, 'verify'])->name('certificats.verify');
Route::get('/tarifs', [PricingController::class, 'index'])->name('pricing');
Route::get('/p/{page:slug}', [LandingController::class, 'show'])->name('landing.show');

/*
|--------------------------------------------------------------------------
| Authentification (invités)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Notifications (tous les utilisateurs connectés)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/lire', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/lire-tout', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Support / messagerie
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/nouveau', [SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{conversation}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{conversation}/repondre', [SupportController::class, 'reply'])->name('support.reply');
});

/*
|--------------------------------------------------------------------------
| Vérification de l'adresse email
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Backoffice (administration)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:SUPER_ADMIN,ADMIN_ORGANISATION,ADMIN_ETABLISSEMENT'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('exports/factures', [ExportController::class, 'factures'])->name('exports.factures');
        Route::get('exports/inscriptions', [ExportController::class, 'inscriptions'])->name('exports.inscriptions');
        Route::get('bi', [BiController::class, 'index'])->name('bi.index');
        Route::post('bi/rebuild', [BiController::class, 'rebuild'])->name('bi.rebuild');

        // Studio no-code
        Route::get('studio', [StudioProjectController::class, 'index'])->name('studio.index');
        Route::get('studio/create', [StudioProjectController::class, 'create'])->name('studio.create');
        Route::post('studio', [StudioProjectController::class, 'store'])->name('studio.store');
        Route::get('studio/{studio}/edit', [StudioProjectController::class, 'edit'])->name('studio.edit');
        Route::put('studio/{studio}', [StudioProjectController::class, 'update'])->name('studio.update');
        Route::delete('studio/{studio}', [StudioProjectController::class, 'destroy'])->name('studio.destroy');
        Route::post('studio/{studio}/pages', [StudioPageController::class, 'store'])->name('studio.pages.store');
        Route::get('studio/pages/{page}/builder', [StudioPageController::class, 'builder'])->name('studio.pages.builder');
        Route::put('studio/pages/{page}', [StudioPageController::class, 'update'])->name('studio.pages.update');
        Route::delete('studio/pages/{page}', [StudioPageController::class, 'destroy'])->name('studio.pages.destroy');
        Route::post('studio/pages/{page}/blocs', [StudioPageController::class, 'addBlock'])->name('studio.pages.addBlock');
        Route::post('studio/pages/{page}/blocs/{index}/supprimer', [StudioPageController::class, 'removeBlock'])->name('studio.pages.removeBlock');
        Route::resource('users', UserController::class)->except('show');
        Route::resource('roles', RoleController::class)->except('show');
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('certificats', [AdminCertificatController::class, 'index'])->name('certificats.index');
        Route::get('factures', [AdminFactureController::class, 'index'])->name('factures.index');
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('remboursements', [RemboursementController::class, 'index'])->name('remboursements.index');
        Route::post('factures/{facture}/rembourser', [RemboursementController::class, 'store'])->name('factures.refund');
        Route::resource('coupons', CouponController::class)->except('show');
        Route::resource('plans', PlanController::class)->except('show');
        Route::resource('organisations', OrganisationController::class)->except('show');
        Route::resource('etablissements', EtablissementController::class)->except('show');
        Route::resource('categories', CategorieController::class)->except('show');
        Route::resource('formations', AdminFormationController::class)->except('show');
    });

/*
|--------------------------------------------------------------------------
| Espace formateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:FORMATEUR'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function (): void {
        Route::get('/', TeacherDashboardController::class)->name('dashboard');
        Route::resource('formations', TeacherFormationController::class)->except('show');

        // Structure d'une formation : cours
        Route::get('/formations/{formation}/cours', [CoursController::class, 'index'])->name('formations.cours.index');
        Route::get('/formations/{formation}/cours/create', [CoursController::class, 'create'])->name('formations.cours.create');
        Route::post('/formations/{formation}/cours', [CoursController::class, 'store'])->name('formations.cours.store');
        Route::get('/cours/{cours}/edit', [CoursController::class, 'edit'])->name('cours.edit');
        Route::put('/cours/{cours}', [CoursController::class, 'update'])->name('cours.update');
        Route::delete('/cours/{cours}', [CoursController::class, 'destroy'])->name('cours.destroy');

        // Builder : modules / chapitres / leçons
        Route::get('/cours/{cours}/builder', [CoursBuilderController::class, 'show'])->name('cours.builder');
        Route::post('/cours/{cours}/modules', [CoursBuilderController::class, 'storeModule'])->name('cours.modules.store');
        Route::delete('/modules/{module}', [CoursBuilderController::class, 'destroyModule'])->name('modules.destroy');
        Route::post('/modules/{module}/chapitres', [CoursBuilderController::class, 'storeChapitre'])->name('modules.chapitres.store');
        Route::delete('/chapitres/{chapitre}', [CoursBuilderController::class, 'destroyChapitre'])->name('chapitres.destroy');
        Route::post('/chapitres/{chapitre}/lecons', [CoursBuilderController::class, 'storeLecon'])->name('chapitres.lecons.store');
        Route::delete('/lecons/{lecon}', [CoursBuilderController::class, 'destroyLecon'])->name('lecons.destroy');

        // Évaluations d'une formation
        Route::get('/formations/{formation}/evaluations', [TeacherEvaluationController::class, 'index'])->name('formations.evaluations.index');
        Route::get('/formations/{formation}/evaluations/create', [TeacherEvaluationController::class, 'create'])->name('formations.evaluations.create');
        Route::post('/formations/{formation}/evaluations', [TeacherEvaluationController::class, 'store'])->name('formations.evaluations.store');
        Route::get('/evaluations/{evaluation}/edit', [TeacherEvaluationController::class, 'edit'])->name('evaluations.edit');
        Route::put('/evaluations/{evaluation}', [TeacherEvaluationController::class, 'update'])->name('evaluations.update');
        Route::delete('/evaluations/{evaluation}', [TeacherEvaluationController::class, 'destroy'])->name('evaluations.destroy');

        // Builder de quiz : questions / réponses
        Route::get('/evaluations/{evaluation}/builder', [EvaluationBuilderController::class, 'show'])->name('evaluations.builder');
        Route::post('/evaluations/{evaluation}/questions', [EvaluationBuilderController::class, 'storeQuestion'])->name('evaluations.questions.store');
        Route::delete('/questions/{question}', [EvaluationBuilderController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::post('/questions/{question}/reponses', [EvaluationBuilderController::class, 'storeReponse'])->name('questions.reponses.store');
        Route::delete('/reponses/{reponse}', [EvaluationBuilderController::class, 'destroyReponse'])->name('reponses.destroy');

        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Espace étudiant
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:ETUDIANT'])
    ->prefix('student')
    ->name('student.')
    ->group(function (): void {
        Route::get('/', StudentDashboardController::class)->name('dashboard');

        // Formations suivies + lecteur
        Route::get('/formations', [StudentFormationController::class, 'index'])->name('formations.index');
        Route::get('/formations/{formation}', [StudentFormationController::class, 'show'])->name('formations.show');
        Route::post('/formations/{formation}/inscription', [InscriptionController::class, 'store'])->name('formations.enroll');
        Route::post('/lecons/{lecon}/complete', [ProgressionController::class, 'complete'])->name('lecons.complete');

        // Évaluations : passage & résultats
        Route::get('/formations/{formation}/evaluations', [StudentEvaluationController::class, 'index'])->name('formations.evaluations.index');
        Route::post('/evaluations/{evaluation}/demarrer', [StudentEvaluationController::class, 'start'])->name('evaluations.start');
        Route::get('/tentatives/{tentative}', [StudentEvaluationController::class, 'take'])->name('tentatives.take');
        Route::post('/tentatives/{tentative}/soumettre', [StudentEvaluationController::class, 'submit'])->name('tentatives.submit');
        Route::get('/tentatives/{tentative}/resultat', [StudentEvaluationController::class, 'result'])->name('tentatives.result');

        // Certificats
        Route::get('/certificats', [StudentCertificatController::class, 'index'])->name('certificats.index');
        Route::post('/formations/{formation}/certificat', [StudentCertificatController::class, 'store'])->name('certificats.store');
        Route::get('/certificats/{certificat}', [StudentCertificatController::class, 'show'])->name('certificats.show');

        // Paiement & factures
        Route::get('/formations/{formation}/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/formations/{formation}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/factures', [StudentFactureController::class, 'index'])->name('factures.index');
        Route::get('/factures/{facture}', [StudentFactureController::class, 'show'])->name('factures.show');

        // Abonnements
        Route::get('/abonnements', [AbonnementController::class, 'index'])->name('abonnements.index');
        Route::get('/abonnements/{plan}/souscrire', [AbonnementController::class, 'checkout'])->name('abonnements.checkout');
        Route::post('/abonnements/{plan}/souscrire', [AbonnementController::class, 'subscribe'])->name('abonnements.subscribe');
        Route::post('/abonnements/{abonnement}/auto-renew', [AbonnementController::class, 'toggleRenew'])->name('abonnements.toggleRenew');

        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    });
