<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories_formations')->nullOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('categorie_traductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories_formations')->cascadeOnDelete();
            $table->foreignId('langue_id')->constrained('langues')->cascadeOnDelete();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->foreignId('categorie_id')->nullable()->constrained('categories_formations')->nullOnDelete();
            $table->string('code', 50);
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('objectifs')->nullable();
            $table->string('niveau', 50)->nullable();
            $table->integer('duree')->nullable();
            $table->string('image')->nullable();
            $table->decimal('prix', 15, 2)->default(0);
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->string('type', 20)->default('GRATUITE')->index();
            $table->string('statut', 20)->default('BROUILLON')->index();
            $table->dateTime('date_publication')->nullable();
            $table->dateTime('date_expiration')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('formation_traductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->foreignId('langue_id')->constrained('langues')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->text('objectifs')->nullable();
            $table->text('prerequis')->nullable();
            $table->longText('contenu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formation_traductions');
        Schema::dropIfExists('formations');
        Schema::dropIfExists('categorie_traductions');
        Schema::dropIfExists('categories_formations');
    }
};
