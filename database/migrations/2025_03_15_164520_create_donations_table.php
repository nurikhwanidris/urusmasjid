<?php

use App\Models\Mosque;
use App\Models\MosqueUser;
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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mosque::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(MosqueUser::class, 'donor_id')->nullable()->constrained('mosque_users')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['duitnow_qr', 'cash', 'bank_transfer'])->default('cash');
            $table->string('reference_number')->nullable(); // For online transactions
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('purpose')->nullable(); // General purpose of the donation
            $table->text('notes')->nullable();
            $table->string('donor_name')->nullable(); // For anonymous/non-registered donors
            $table->string('donor_phone')->nullable(); // For anonymous/non-registered donors
            $table->string('donor_email')->nullable(); // For anonymous/non-registered donors
            $table->string('receipt_number')->unique(); // Auto-generated receipt number
            $table->timestamps();

            // Indexes for common queries
            $table->index(['mosque_id', 'status', 'created_at']);
            $table->index(['mosque_id', 'payment_method']);
            $table->index(['mosque_id', 'donor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
