<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modeles_certificats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->string('nom');
            $table->string('design')->nullable();
            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('certificats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();
            $table->string('numero', 80)->unique();
            $table->date('date_emission')->nullable();
            $table->date('date_expiration')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->string('mention', 30)->nullable();
            $table->string('fichier')->nullable();
            $table->string('hash_verification')->nullable()->unique();
            $table->string('statut', 20)->default('VALIDE')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('conditions')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('etudiant_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
            $table->dateTime('date_obtention')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiant_badges');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('certificats');
        Schema::dropIfExists('modeles_certificats');
    }
};
