<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->foreignId('formateur_id')->nullable()->constrained('formateurs')->nullOnDelete();
            $table->string('code', 50)->nullable();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->integer('duree')->nullable();
            $table->string('statut', 20)->default('BROUILLON')->index();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modules_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->integer('duree')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('chapitres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_cours_id')->constrained('modules_cours')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('lecons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapitre_id')->constrained('chapitres')->cascadeOnDelete();
            $table->string('type', 20)->default('VIDEO')->index();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->integer('duree')->nullable();
            $table->boolean('obligatoire')->default(true);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecons');
        Schema::dropIfExists('chapitres');
        Schema::dropIfExists('modules_cours');
        Schema::dropIfExists('cours');
    }
};
