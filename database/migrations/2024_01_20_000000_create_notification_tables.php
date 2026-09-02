<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canaux_notification', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('nom');
            $table->foreignId('canal_id')->nullable()->constrained('canaux_notification')->nullOnDelete();
            $table->foreignId('langue_id')->nullable()->constrained('langues')->nullOnDelete();
            $table->string('sujet')->nullable();
            $table->longText('contenu')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 50)->nullable()->index();
            $table->string('titre')->nullable();
            $table->text('message')->nullable();
            $table->json('data')->nullable();
            $table->boolean('lu')->default(false)->index();
            $table->dateTime('date_lecture')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('canal_id')->constrained('canaux_notification')->cascadeOnDelete();
            $table->string('type', 50)->nullable()->index();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('canaux_notification');
    }
};
