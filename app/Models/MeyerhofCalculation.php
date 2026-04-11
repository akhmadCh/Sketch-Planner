<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\FoundationStatus;

class MeyerhofCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'pile_config_id',
        'soil_profile_id',
        'created_by',
        'calc_name',
        'point_label',
        'embedment_ratio',
        'nq_factor',
        'nc_factor',
        'tip_resistance_qp',
        'skin_friction_qs',
        'total_capacity_qu',
        'safety_factor',
        'allowable_load_qa',
        'pile_condition',
        'notes',
        'status',
        'peat_warning',
        'sondir_data',
        'calculated_at'
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'sondir_data'   => 'array',
        'status'        => FoundationStatus::class,
        'peat_warning'  => 'boolean',
    ];
}
