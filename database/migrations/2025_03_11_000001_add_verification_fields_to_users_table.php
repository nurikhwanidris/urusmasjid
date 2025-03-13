<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('phone_verified')->default(false)->after('phone_number');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_verified');
            $table->string('verification_code')->nullable()->after('phone_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'phone_verified_at', 'verification_code']);
        });
    }
};
