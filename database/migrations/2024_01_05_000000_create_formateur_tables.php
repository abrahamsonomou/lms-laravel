<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('matricule', 50)->nullable();
            $table->text('biographie')->nullable();
            $table->string('specialite')->nullable();
            $table->integer('experience')->nullable();
            $table->decimal('tarif', 15, 2)->nullable();
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('specialites', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->string('description')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('formateur_specialites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('formateurs')->cascadeOnDelete();
            $table->foreignId('specialite_id')->constrained('specialites')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('disponibilites_formateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('formateurs')->cascadeOnDelete();
            $table->string('jour', 15);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilites_formateurs');
        Schema::dropIfExists('formateur_specialites');
        Schema::dropIfExists('specialites');
        Schema::dropIfExists('formateurs');
    }
};
