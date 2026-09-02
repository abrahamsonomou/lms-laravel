---
paths:
  - 'app/Services/PaiementProcessor.php,app/Http/Controllers/Student/CheckoutController.php,app/Http/Controllers/**/FactureController.php'
---

# Student Http Controllers

## Module Paiement/Facturation (patterns)
Tunnel d'achat d'une formation payante. `Formation::estPayante()` = type != GRATUITE && prix > 0.

Aiguillage: `Student\InscriptionController@store` redirige vers `student.checkout.show` si la formation est payante et l'étudiant non inscrit ; sinon inscription directe (gratuit).

Traitement (`Services\PaiementProcessor`, tout en `DB::transaction`):
- `createFacture(client, formation)`: Facture BROUILLON (numero `FAC-{année}-{alea}` unique, total_ttc = prix) + une LigneFacture (formation_id, total = prix).
- `payer(facture, moyen, user)`: crée une TransactionPaiement (reference `TXN-{alea}` unique, statut REUSSI — paiement SIMULÉ, `transaction_externe` = SIM-...), passe la facture à PAYEE, puis inscrit l'étudiant (FormationEtudiant firstOrCreate) pour chaque ligne. Pour brancher un vrai PSP, remplacer la partie « statut REUSSI » par l'appel gateway + webhook de confirmation.

Statuts: Facture BROUILLON/PAYEE/ANNULEE ; Transaction EN_ATTENTE/REUSSI/ECHOUE. Badges: facture BROUILLON=secondary/PAYEE=success/ANNULEE=danger ; transaction REUSSI=success/EN_ATTENTE=warning/ECHOUE=danger.

Surfaces: étudiant `student.checkout.show/store`, `student.factures.index/show` (facture imprimable, ownership client_id==user->id). Admin lecture seule `admin.factures.index` (+ total encaissé) et `admin.transactions.index` (+ total réussi). Moyens de paiement seedés par `PaiementSeeder` (Orange/MTN/Stripe → moyens_paiement).
