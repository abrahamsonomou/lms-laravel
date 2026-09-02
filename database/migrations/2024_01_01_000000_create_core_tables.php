<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langues', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('nom');
            $table->string('locale', 10);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('devises', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('symbole', 10);
            $table->string('nom');
            $table->tinyInteger('nombre_decimales')->default(2);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('iso2', 2);
            $table->string('iso3', 3);
            $table->string('nom');
            $table->string('indicatif_telephone', 10)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('villes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pays_id')->constrained('pays')->cascadeOnDelete();
            $table->string('nom');
            $table->string('code', 20)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('taux_change', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devise_source_id')->constrained('devises');
            $table->foreignId('devise_cible_id')->constrained('devises');
            $table->decimal('taux', 18, 8);
            $table->date('date_effet');
            $table->date('date_fin')->nullable();
            $table->string('source')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taux_change');
        Schema::dropIfExists('villes');
        Schema::dropIfExists('pays');
        Schema::dropIfExists('devises');
        Schema::dropIfExists('langues');
    }
};
