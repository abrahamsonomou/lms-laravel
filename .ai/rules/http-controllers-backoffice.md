---
paths:
  - 'app/Console/Commands/**,routes/console.php,app/Http/Controllers/Backoffice/Bi*,app/Http/Controllers/Backoffice/Rapport*,app/Http/Controllers/Backoffice/Export*'
---

# Http Controllers Backoffice

## Automations planifiées, Rapports & BI
Commandes planifiées (dans `routes/console.php` via `Schedule::command(...)`, nécessite un cron `php artisan schedule:run`):
- `abonnements:expirer` (quotidien 02:00): pour chaque Abonnement ACTIF dont date_fin < now → si `auto_renew` → `AbonnementService::renew()` (re-paiement via le dernier moyen de la facture, extension date_fin), sinon statut `EXPIRE`. Notifie l'utilisateur.
- `bi:rebuild` (quotidien 03:00 + appelé en fin de `DatabaseSeeder`): ETL qui vide et recharge `dim_formation` + `fact_ventes`/`fact_inscriptions`/`fact_progressions` par agrégats SQL depuis lignes_factures (factures PAYEE) et formations_etudiants. Faits agrégés par formation (pas de dimension temps ici).

Rapports (`Backoffice\RapportController`, route `admin.rapports.index`): CA/ventes/panier moyen/inscriptions/remboursements sur une période (from/to). Exports CSV via `Backoffice\ExportController` (`response()->streamDownload` + `fputcsv`, `chunk(200)`) — routes `admin.exports.factures` / `admin.exports.inscriptions`.

BI (`Backoffice\BiController`, `admin.bi.index`): lit les faits matérialisés (top formations par revenu/inscriptions), bouton « Reconstruire » = `Artisan::call('bi:rebuild')`. Tests d'ETL: `$this->artisan('bi:rebuild')->assertSuccessful()` puis vérifier FactVente/FactInscription. Tests CSV: `$response->streamedContent()`.
