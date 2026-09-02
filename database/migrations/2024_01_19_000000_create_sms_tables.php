<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs_sms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->string('provider', 30)->nullable();
            $table->string('api_url')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('api_key')->nullable();
            $table->string('sender_id', 30)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('nom');
            $table->text('contenu');
            $table->foreignId('langue_id')->nullable()->constrained('langues')->nullOnDelete();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs_sms')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('telephone', 30);
            $table->text('contenu');
            $table->string('reference')->nullable();
            $table->string('statut', 20)->default('EN_ATTENTE')->index();
            $table->dateTime('date_envoi')->nullable();
            $table->dateTime('date_livraison')->nullable();
            $table->text('erreur')->nullable();
            $table->timestamps();
        });

        Schema::create('sms_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs_sms')->nullOnDelete();
            $table->integer('quota')->default(0);
            $table->integer('consomme')->default(0);
            $table->integer('reste')->default(0);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_quotas');
        Schema::dropIfExists('sms_messages');
        Schema::dropIfExists('sms_templates');
        Schema::dropIfExists('fournisseurs_sms');
    }
};
