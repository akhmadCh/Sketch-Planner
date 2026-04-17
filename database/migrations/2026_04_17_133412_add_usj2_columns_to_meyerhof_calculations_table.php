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
        Schema::table('meyerhof_calculations', function (Blueprint $table) {
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('seismic_zone')->nullable();
            $table->float('ss_value')->nullable();
            $table->float('s1_value')->nullable();
            $table->json('seismic_data')->nullable();
            $table->string('sap2000_joint_id')->nullable();
            $table->float('sap2000_vertical_load')->nullable();
            $table->integer('pile_group_n')->nullable();
            $table->integer('pile_group_m')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meyerhof_calculations', function (Blueprint $table) {
            $table->dropColumn([
                'latitude', 'longitude', 'seismic_zone', 'ss_value', 's1_value', 
                'seismic_data', 'sap2000_joint_id', 'sap2000_vertical_load', 
                'pile_group_n', 'pile_group_m'
            ]);
        });
    }
};
