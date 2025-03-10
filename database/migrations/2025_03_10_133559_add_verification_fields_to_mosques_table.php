<?php

use App\Models\User;
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
        Schema::table('mosques', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->after('type');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('created_by');
            $table->text('verification_notes')->nullable()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verification_notes');
            $table->foreignIdFor(User::class, 'verified_by')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            $table->dropColumn([
                'registration_number',
                'verification_status',
                'verification_notes',
                'verified_at',
            ]);

            $table->dropForeign(['verified_by']);
            $table->dropColumn('verified_by');
        });
    }
};
