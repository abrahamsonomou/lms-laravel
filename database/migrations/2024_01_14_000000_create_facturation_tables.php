<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('numero', 80)->unique();
            $table->date('date_facture')->nullable();
            $table->date('date_echeance')->nullable();
            $table->decimal('sous_total', 15, 2)->default(0);
            $table->decimal('taxe', 15, 2)->default(0);
            $table->decimal('remise', 15, 2)->default(0);
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->decimal('taux_change', 18, 8)->nullable();
            $table->string('statut', 20)->default('BROUILLON')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lignes_factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained('factures')->cascadeOnDelete();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();
            $table->string('description')->nullable();
            $table->decimal('quantite', 10, 2)->default(1);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
            $table->decimal('remise', 15, 2)->default(0);
            $table->decimal('taxe', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_factures');
        Schema::dropIfExists('factures');
    }
};
