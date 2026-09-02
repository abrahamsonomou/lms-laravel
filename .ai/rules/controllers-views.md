---
paths:
  - 'app/Http/Controllers/**/DashboardController.php,resources/views/*/dashboard.blade.php'
---

# Controllers Views

## Dashboards : indicateurs réels
Les 3 tableaux de bord agrègent des données réelles (plus de placeholders):
- Admin (`Backoffice\DashboardController`): utilisateurs, étudiants, formateurs, formations, inscriptions (FormationEtudiant), certificats, ventes (Facture PAYEE count), CA (Facture PAYEE sum total_ttc) + tables derniers utilisateurs / dernières transactions REUSSI.
- Formateur (`Teacher\DashboardController`): scope `Formation.created_by = user`. formations, étudiants (distinct etudiant_id des FormationEtudiant de ses formations), évaluations, revenus (somme LigneFacture.total des lignes dont la facture est PAYEE) + ses dernières formations.
- Étudiant (`Student\DashboardController`): cours (FormationEtudiant), terminées, progression moyenne (avg), certificats + liste « en cours » (statut != TERMINE) avec barres de progression. Garde des valeurs à 0 si l'utilisateur n'a pas de profil `etudiant`.
Les vues réutilisent le pattern de cartes `icon-shape icon-lg bg-light-{couleur} text-{couleur} rounded-3`.
