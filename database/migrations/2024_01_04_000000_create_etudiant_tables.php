<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('matricule', 50)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('sexe', 10)->nullable();
            $table->foreignId('pays_naissance_id')->nullable()->constrained('pays')->nullOnDelete();
            $table->foreignId('ville_naissance_id')->nullable()->constrained('villes')->nullOnDelete();
            $table->string('adresse')->nullable();
            $table->string('niveau', 50)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('annee', 20)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('groupe_etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupes')->cascadeOnDelete();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->date('date_entree')->nullable();
            $table->date('date_sortie')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groupe_etudiants');
        Schema::dropIfExists('groupes');
        Schema::dropIfExists('etudiants');
    }
};
