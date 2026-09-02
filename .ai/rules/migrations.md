---
paths:
  - 'app/Models/**,database/migrations/**'
---

# Migrations

## Architecture LMS modulaire (modèles + migrations)
Le schéma LMS est organisé en 24 modules. Modèles rangés par namespace `App\Models\<Module>` (Core, Organisation, Rbac, Etudiant, Formateur, Catalogue, Cours, Contenu, Progression, Evaluation, Certification, Abonnement, Coupon, Facturation, Paiement, Chat, Chatbot, Mailing, Sms, Notification, Studio, Bi, Audit, Api). Migrations: une par module, préfixe date `2024_01_NN_` qui fixe l'ordre de dépendance des FK (parents avant enfants).

Conventions obligatoires:
- Modèles: attributs Laravel 13 `#[Table('...')]` (pluriels non standard: pays, devises, cours...) + `#[Fillable([...])]`; méthode `casts()` (jamais la propriété $casts); relations typées; refs cross-module en FQN.
- Migrations: FK via `foreignId('x_id')->constrained('table_explicite')` — TOUJOURS nommer la table (pluriels FR non conventionnels). `nullOnDelete()` pour FK optionnelle, `cascadeOnDelete()` pour dépendance forte. Modifieurs avant `constrained`.
- "Enums" = colonnes `string(...)->index()` + default (portabilité), pas de `->enum()`. Montants `decimal(15,2)`, taux de change `decimal(18,8)`, JSON `json()`.
- Tables BI (dim_*/fact_*): pas de FK contraintes ni softDeletes, clés en `unsignedBigInteger()->nullable()->index()`.
- SoftDeletes uniquement sur entités durables (users, organisations, formations, cours, etudiants, formateurs, factures, transactions, messages, etc.), pas sur pivots/logs/BI.
