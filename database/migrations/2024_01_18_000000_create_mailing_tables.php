<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs_email', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('provider', 30)->nullable();
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->string('sujet')->nullable();
            $table->longText('contenu_html')->nullable();
            $table->longText('contenu_text')->nullable();
            $table->foreignId('langue_id')->nullable()->constrained('langues')->nullOnDelete();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('campagnes_email', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('objet')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->dateTime('date_planification')->nullable();
            $table->dateTime('date_envoi')->nullable();
            $table->string('statut', 20)->default('BROUILLON')->index();
            $table->timestamps();
        });

        Schema::create('email_envois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->nullable()->constrained('campagnes_email')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email');
            $table->string('statut', 20)->default('EN_ATTENTE')->index();
            $table->dateTime('date_envoi')->nullable();
            $table->dateTime('date_lecture')->nullable();
            $table->dateTime('date_clic')->nullable();
            $table->text('erreur')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_envois');
        Schema::dropIfExists('campagnes_email');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('fournisseurs_email');
    }
};
