<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dim_temps', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->tinyInteger('jour');
            $table->tinyInteger('mois');
            $table->tinyInteger('trimestre');
            $table->smallInteger('annee');
            $table->tinyInteger('jour_semaine');
            $table->tinyInteger('semaine');
            $table->string('nom_mois', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('dim_pays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pays_id')->index();
            $table->string('code', 10)->nullable();
            $table->string('nom')->nullable();
            $table->timestamps();
        });

        Schema::create('dim_organisation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisation_id')->index();
            $table->string('code', 50)->nullable();
            $table->string('nom')->nullable();
            $table->timestamps();
        });

        Schema::create('dim_formation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formation_id')->index();
            $table->string('titre')->nullable();
            $table->string('categorie')->nullable();
            $table->timestamps();
        });

        Schema::create('dim_etudiant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etudiant_id')->index();
            $table->string('matricule', 50)->nullable();
            $table->string('pays')->nullable();
            $table->timestamps();
        });

        Schema::create('fact_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id')->nullable()->index();
            $table->unsignedBigInteger('formation_id')->nullable()->index();
            $table->unsignedBigInteger('organisation_id')->nullable()->index();
            $table->unsignedBigInteger('pays_id')->nullable()->index();
            $table->integer('nombre_inscriptions')->default(0);
            $table->integer('nombre_termines')->default(0);
            $table->integer('nombre_abandons')->default(0);
            $table->timestamps();
        });

        Schema::create('fact_ventes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id')->nullable()->index();
            $table->unsignedBigInteger('formation_id')->nullable()->index();
            $table->unsignedBigInteger('organisation_id')->nullable()->index();
            $table->unsignedBigInteger('devise_id')->nullable()->index();
            $table->integer('nombre_ventes')->default(0);
            $table->decimal('revenu', 18, 2)->default(0);
            $table->decimal('panier_moyen', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('fact_progressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id')->nullable()->index();
            $table->unsignedBigInteger('formation_id')->nullable()->index();
            $table->unsignedBigInteger('etudiant_id')->nullable()->index();
            $table->decimal('progression_moyenne', 5, 2)->default(0);
            $table->integer('temps_total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fact_progressions');
        Schema::dropIfExists('fact_ventes');
        Schema::dropIfExists('fact_inscriptions');
        Schema::dropIfExists('dim_etudiant');
        Schema::dropIfExists('dim_formation');
        Schema::dropIfExists('dim_organisation');
        Schema::dropIfExists('dim_pays');
        Schema::dropIfExists('dim_temps');
    }
};
