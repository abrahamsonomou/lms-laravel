<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organisation_id')->nullable()->after('id')->constrained('organisations')->nullOnDelete();
            $table->string('nom')->nullable()->after('name');
            $table->string('prenom')->nullable()->after('nom');
            $table->string('telephone', 30)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('telephone');
            $table->foreignId('langue_id')->nullable()->after('avatar')->constrained('langues')->nullOnDelete();
            $table->foreignId('pays_id')->nullable()->after('langue_id')->constrained('pays')->nullOnDelete();
            $table->string('timezone', 60)->nullable()->after('pays_id');
            $table->timestamp('telephone_verified_at')->nullable()->after('email_verified_at');
            $table->boolean('active')->default(true)->after('timezone');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organisation_id');
            $table->dropConstrainedForeignId('langue_id');
            $table->dropConstrainedForeignId('pays_id');
            $table->dropColumn(['nom', 'prenom', 'telephone', 'avatar', 'timezone', 'telephone_verified_at', 'active', 'deleted_at']);
        });
    }
};
