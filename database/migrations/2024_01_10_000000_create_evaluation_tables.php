<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();
            $table->foreignId('cours_id')->nullable()->constrained('cours')->nullOnDelete();
            $table->string('titre');
            $table->string('type', 20)->default('QUIZ')->index();
            $table->integer('duree')->nullable();
            $table->decimal('note_max', 6, 2)->nullable();
            $table->decimal('note_min', 6, 2)->nullable();
            $table->integer('tentatives_max')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->string('type', 20)->default('QCM')->index();
            $table->text('question');
            $table->decimal('points', 6, 2)->default(1);
            $table->integer('ordre')->default(0);
            $table->text('explication')->nullable();
            $table->timestamps();
        });

        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->text('libelle');
            $table->boolean('correcte')->default(false);
            $table->decimal('points', 6, 2)->default(0);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        Schema::create('tentatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->integer('numero')->default(1);
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->decimal('note', 6, 2)->nullable();
            $table->string('statut', 20)->default('EN_COURS')->index();
            $table->timestamps();
        });

        Schema::create('reponses_etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tentative_id')->constrained('tentatives')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('reponse_id')->nullable()->constrained('reponses')->nullOnDelete();
            $table->text('reponse_texte')->nullable();
            $table->decimal('points', 6, 2)->default(0);
            $table->boolean('correcte')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reponses_etudiants');
        Schema::dropIfExists('tentatives');
        Schema::dropIfExists('reponses');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('evaluations');
    }
};
