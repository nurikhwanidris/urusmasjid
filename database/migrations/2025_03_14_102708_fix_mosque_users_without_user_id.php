<?php

use App\Models\MosqueUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find all MosqueUser records without a user_id
        $mosqueUsers = MosqueUser::whereNull('user_id')->get();

        if ($mosqueUsers->count() > 0) {
            DB::beginTransaction();

            try {
                foreach ($mosqueUsers as $mosqueUser) {
                    // Call the createUser method
                    $mosqueUser->createUser();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed
    }
};
