<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs_paiement', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->string('type', 30)->nullable();
            $table->foreignId('pays_id')->nullable()->constrained('pays')->nullOnDelete();
            $table->string('api_url')->nullable();
            $table->string('public_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('moyens_paiement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs_paiement')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->string('type', 30)->nullable();
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('transactions_paiement', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 80)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('facture_id')->nullable()->constrained('factures')->nullOnDelete();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs_paiement')->nullOnDelete();
            $table->foreignId('moyen_paiement_id')->nullable()->constrained('moyens_paiement')->nullOnDelete();
            $table->decimal('montant', 15, 2)->default(0);
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->decimal('montant_converti', 15, 2)->nullable();
            $table->foreignId('devise_base_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->decimal('taux_change', 18, 8)->nullable();
            $table->string('statut', 20)->default('EN_ATTENTE')->index();
            $table->string('transaction_externe')->nullable();
            $table->dateTime('date_transaction')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('remboursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions_paiement')->cascadeOnDelete();
            $table->string('reference', 80)->unique();
            $table->decimal('montant', 15, 2)->default(0);
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->string('motif')->nullable();
            $table->string('statut', 20)->default('EN_ATTENTE')->index();
            $table->dateTime('date_remboursement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remboursements');
        Schema::dropIfExists('transactions_paiement');
        Schema::dropIfExists('moyens_paiement');
        Schema::dropIfExists('fournisseurs_paiement');
    }
};
