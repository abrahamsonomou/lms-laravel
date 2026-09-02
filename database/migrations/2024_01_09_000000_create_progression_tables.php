<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations_etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->dateTime('date_inscription')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->decimal('progression', 5, 2)->default(0);
            $table->string('statut', 20)->default('INSCRIT')->index();
            $table->timestamps();
        });

        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();
            $table->foreignId('cours_id')->nullable()->constrained('cours')->nullOnDelete();
            $table->foreignId('lecon_id')->nullable()->constrained('lecons')->nullOnDelete();
            $table->decimal('progression', 5, 2)->default(0);
            $table->integer('temps_consomme')->default(0);
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_derniere_activite')->nullable();
            $table->boolean('terminee')->default(false)->index();
            $table->dateTime('date_completion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progressions');
        Schema::dropIfExists('formations_etudiants');
    }
};
