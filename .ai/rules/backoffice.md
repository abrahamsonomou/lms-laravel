---
paths:
  - 'app/Services/CouponValidator.php,app/Http/Controllers/Student/CheckoutController.php,app/Http/Controllers/Backoffice/CouponController.php'
  - 'app/Services/AbonnementService.php,app/Http/Controllers/Student/AbonnementController.php,app/Http/Controllers/Backoffice/PlanController.php'
---

# Backoffice

## Module Coupons (patterns)
Coupons de réduction appliqués au checkout.

`Services\CouponValidator::validate(code, formation, montant)` retourne `['valid'=>bool,'coupon'=>?Coupon,'remise'=>float,'message'=>?string]`. Vérifie: coupon actif, fenêtre date_debut/date_fin, quota (utilisations < nombre_utilisations), montant_minimum, et restriction par formations (si le coupon a des `formations()` liées, la formation doit y figurer ; sinon coupon global). Remise: POURCENTAGE = montant*valeur/100, MONTANT = valeur, plafonnée au montant. `consume(coupon)` incrémente `utilisations` (appelé UNE fois après paiement réussi).

Checkout (`Student\CheckoutController`): `show` lit `?coupon=` et affiche remise/total + message ; `store` REVALIDE le `coupon_code` (sécurité — ne jamais se fier au montant client), calcule la remise, passe `remise` à `PaiementProcessor::createFacture(client, formation, remise)` (met à jour `remise`/`total_ht`/`total_ttc` de la facture et de la ligne), puis `consume()` le coupon. Si le code est invalide au moment de payer → redirection vers checkout avec l'erreur.

Admin: `Backoffice\CouponController` CRUD complet, restriction optionnelle via multiselect `formations[]` (pivot coupon_formations). type_remise ∈ {POURCENTAGE, MONTANT}. Seed: coupon démo `BIENVENUE10` (10%).

## Module Abonnements (patterns)
Abonnements récurrents donnant accès illimité au catalogue.

Modèles: Plan (prix, type MENSUEL/ANNUEL, `duree` en jours ; helper `Plan::dureeEnJours()` = duree ou 30/365 selon type, const `Plan::TYPES`), Abonnement (statut ACTIF, date_debut/date_fin, belongsToMany User via `utilisateurs()` / table abonnement_utilisateurs).

Souscription (`Services\AbonnementService::subscribe(user, plan, moyen)`, en transaction): réutilise `PaiementProcessor::createFactureLibre(...)` (facture avec ligne sans formation_id) + `payer(...)`, puis crée l'Abonnement (date_fin = now + dureeEnJours) et attache l'utilisateur. `Student\AbonnementController` : `checkout` (choix moyen) → `subscribe` (garde: pas de double abonnement actif) + notification 'abonnement'.

Accès illimité: helpers sur User `abonnementActif()` / `hasAbonnementActif()` (Abonnement ACTIF + date_fin >= now + lié via utilisateurs). `InscriptionController` inscrit DIRECTEMENT (sans checkout) une formation payante si `hasAbonnementActif()`. `CheckoutController@show` renvoie un abonné au catalogue.

Surfaces: admin `Backoffice\PlanController` (CRUD plans), public `Public\PricingController` (route `pricing`, /tarifs), étudiant `student.abonnements.*`. Seed: `AbonnementSeeder` (plans Mensuel 19.99 / Annuel 199).
