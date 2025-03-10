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
            $table->string('ic_number')->nullable()->after('email');
            $table->string('phone_number')->nullable()->after('ic_number');
            $table->enum('role', ['admin', 'mosque_admin', 'community_member'])->default('community_member')->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ic_number', 'phone_number', 'role']);
        });
    }
};
