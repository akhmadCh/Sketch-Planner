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
        Schema::create('meyerhof_calculations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('pile_config_id')->nullable();
            $table->unsignedBigInteger('soil_profile_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->string('calc_name')->nullable();
            $table->string('point_label')->nullable();
            $table->float('embedment_ratio')->nullable();
            $table->float('nq_factor')->nullable();
            $table->float('nc_factor')->nullable();
            $table->float('tip_resistance_qp')->nullable();
            $table->float('skin_friction_qs')->nullable();
            $table->float('total_capacity_qu')->nullable();
            $table->float('safety_factor')->nullable();
            $table->float('allowable_load_qa')->nullable();
            $table->string('pile_condition')->nullable();
            $table->text('notes')->nullable();
            
            $table->string('status')->nullable();
            $table->boolean('peat_warning')->default(false);
            $table->json('sondir_data')->nullable();
            $table->timestamp('calculated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meyerhof_calculations');
    }
};
