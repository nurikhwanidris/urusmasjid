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
        Schema::create('community_members', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mosque::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('ic_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->enum('membership_status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            // Ensure a person can only be a member of a mosque once (based on IC number)
            $table->unique(['mosque_id', 'ic_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_members');
    }
};
