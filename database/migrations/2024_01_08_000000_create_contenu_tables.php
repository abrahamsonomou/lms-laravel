<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecon_id')->constrained('lecons')->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('titre')->nullable();
            $table->string('url')->nullable();
            $table->string('fichier')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('taille')->nullable();
            $table->integer('duree')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contenu_id')->constrained('contenus')->cascadeOnDelete();
            $table->string('provider', 30)->nullable();
            $table->string('video_id')->nullable();
            $table->string('url')->nullable();
            $table->integer('duree')->nullable();
            $table->string('resolution', 20)->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
        Schema::dropIfExists('contenus');
    }
};
