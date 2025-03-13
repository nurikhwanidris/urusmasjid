<?php

use App\Models\Mosque;
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
        Schema::create('mosque_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mosque::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['admin', 'committee']); // Type of mosque user
            $table->string('role'); // Admin role or committee position
            $table->boolean('is_primary')->default(false); // For admins only
            $table->string('name')->nullable(); // For committee members
            $table->string('ic_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable(); // For committee members
            $table->text('address')->nullable(); // For committee members
            $table->date('start_date')->nullable(); // For committee members
            $table->date('end_date')->nullable(); // For committee members
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active'); // For committee members
            $table->text('notes')->nullable(); // For committee members
            $table->timestamps();

            // Ensure a user can only be an admin of a mosque once
            $table->unique(['mosque_id', 'user_id', 'type'], 'unique_mosque_user');

            // Ensure a person can only hold one position at a time in a mosque
            $table->index(['mosque_id', 'type', 'role', 'status'], 'mosque_role_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mosque_users');
    }
};
