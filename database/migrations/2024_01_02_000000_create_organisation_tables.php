<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->string('email')->nullable();
            $table->string('telephone', 30)->nullable();
            $table->foreignId('pays_id')->nullable()->constrained('pays')->nullOnDelete();
            $table->foreignId('ville_id')->nullable()->constrained('villes')->nullOnDelete();
            $table->foreignId('devise_id')->nullable()->constrained('devises')->nullOnDelete();
            $table->foreignId('langue_id')->nullable()->constrained('langues')->nullOnDelete();
            $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->foreignId('pays_id')->nullable()->constrained('pays')->nullOnDelete();
            $table->foreignId('ville_id')->nullable()->constrained('villes')->nullOnDelete();
            $table->string('adresse')->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departements');
        Schema::dropIfExists('etablissements');
        Schema::dropIfExists('organisations');
    }
};
