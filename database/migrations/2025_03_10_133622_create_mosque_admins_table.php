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
        Schema::create('mosque_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mosque::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('role')->default('admin'); // admin, manager, etc.
            $table->boolean('is_primary')->default(false);
            $table->string('ic_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();

            // Ensure a user can only be an admin of a mosque once
            $table->unique(['mosque_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mosque_admins');
    }
};
