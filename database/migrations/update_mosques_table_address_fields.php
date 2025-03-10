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
        Schema::table('mosques', function (Blueprint $table) {
            // Rename existing address field to street_address
            $table->renameColumn('address', 'street_address');

            // Add new address fields
            $table->string('address_line_2')->nullable()->after('street_address');
            $table->string('city')->nullable()->after('address_line_2');
            $table->string('district')->nullable()->after('city');
            $table->string('state')->after('district');
            $table->string('postal_code')->nullable()->after('state');
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            // Remove new address fields
            $table->dropColumn([
                'address_line_2',
                'city',
                'district',
                'state',
                'postal_code',
                'latitude',
                'longitude'
            ]);

            // Rename street_address back to address
            $table->renameColumn('street_address', 'address');
        });
    }
};
