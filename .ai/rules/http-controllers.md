---
paths:
  - 'app/Services/CertificatIssuer.php,app/Http/Controllers/**/CertificatController.php'
---

# Http Controllers

## Module Certification (patterns)
Délivrance via `Services\CertificatIssuer`:
- `isEligible(etudiant, formation)`: vrai si `FormationEtudiant.progression >= 100`.
- `issueFor(...)`: idempotent (retourne le certificat existant sinon le crée). Score = moyenne /20 des meilleures notes de quiz de la formation (20 si aucun quiz noté). Mention dérivée du score (Très bien ≥16, Bien ≥14, Assez bien ≥12, sinon Passable). `numero` = `CERT-{année}-{6 alea}` unique, `hash_verification` = 40 car. aléatoires uniques, `statut` = VALIDE.

Surfaces:
- Étudiant (`Student\CertificatController`): `store(formation)` vérifie l'éligibilité puis délivre ; `index` liste ses certificats ; `show` = certificat imprimable (vue AUTONOME hors layout dashboard, avec `window.print()` et CSS `@media print`). Ownership: `certificat.etudiant_id === user.etudiant.id`.
- Public (`Public\CertificatController@verify`): route `/verifier/{hash}` (name `certificats.verify`) — affiche authentique/introuvable sans exposer l'id. Le certificat imprimable affiche cette URL de vérification.
- Admin (`Backoffice\CertificatController@index`): liste en lecture seule de tous les certificats.

CTA "Obtenir mon certificat" affiché dans le player étudiant quand `progression >= 100`.
