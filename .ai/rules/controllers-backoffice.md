---
paths:
  - 'app/Services/RemboursementService.php,app/Http/Controllers/Backoffice/RemboursementController.php'
---

# Controllers Backoffice

## Module Remboursements (patterns)
Remboursement d'une facture par l'admin (`Backoffice\RemboursementController@store(Facture)` avec `motif` requis ; garde: seule une facture `PAYEE` est remboursable).

`Services\RemboursementService::refundFacture(facture, motif)` (transaction): crée un `Remboursement` (reference `REMB-...` unique, montant = transaction, statut REUSSI) sur la dernière TransactionPaiement REUSSI de la facture, passe cette transaction à `REMBOURSE` et la facture à `REMBOURSEE`. Puis RÉVOQUE l'accès : supprime les `FormationEtudiant` du client pour les formations des lignes de facture ; et ANNULE tout `Abonnement` lié (`abonnements.facture_id`, ajouté par migration 2024_02_01) → statut `ANNULE`, date_fin now. Notifie le client (type `remboursement`).

Lien facture↔abonnement: colonne `abonnements.facture_id` (nullable FK) renseignée par `AbonnementService::subscribe` — c'est ce qui permet d'annuler l'abonnement au remboursement de sa facture.

UI: bouton « Rembourser » (form + `prompt()` JS pour le motif) sur les factures PAYEE de `admin.factures.index` ; liste `admin.remboursements.index` (+ total remboursé). Statuts facture: BROUILLON/PAYEE/REMBOURSEE(warning)/ANNULEE.
