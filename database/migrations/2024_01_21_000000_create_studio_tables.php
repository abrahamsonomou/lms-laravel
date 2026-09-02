<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studio_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->string('type', 30);
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('statut', 20)->default('BROUILLON')->index();
            $table->timestamps();
        });

        Schema::create('studio_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('studio_projects')->cascadeOnDelete();
            $table->string('nom');
            $table->string('slug')->nullable();
            $table->json('contenu_json')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('studio_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('studio_pages')->cascadeOnDelete();
            $table->string('type', 30);
            $table->json('configuration_json')->nullable();
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studio_components');
        Schema::dropIfExists('studio_pages');
        Schema::dropIfExists('studio_projects');
    }
};
