<?php

namespace App\Http\Requests\Calculation;

use Illuminate\Foundation\Http\FormRequest;

class MeyerhofRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Typically, this would check if the user can perform this action.
        return true; 
    }

    public function rules(): array
    {
        return [
            'point_label'    => 'required|string',
            'qc_values'      => 'required|array',
            'qc_values.*'    => 'numeric|min:0|max:500',
            'fs_values'      => 'required|array',
            'fs_values.*'    => 'numeric|min:0|max:10',
            'water_content'  => 'sometimes|numeric|min:0|max:2000',
            'water_content_values'   => 'sometimes|array',
            'water_content_values.*' => 'numeric|min:0|max:2000',
            'depth_interval' => 'required|numeric|min:0.2|max:2', // Typically sondir intervals are 0.2m
            'pile_diameter'  => 'required|numeric|min:0.1',
            'pile_depth'     => 'required|numeric|min:0.2|max:60',
            'safety_factor'  => 'sometimes|numeric|min:1',
            'required_load'  => 'sometimes|numeric|min:0',
            'sap2000_load'   => 'sometimes|numeric|min:0',
            'n_pile'         => 'sometimes|integer|min:1',
            'm_pile'         => 'sometimes|integer|min:1',
            's_spacing'      => 'sometimes|numeric|min:0',
            'latitude'       => 'sometimes|numeric',
            'longitude'      => 'sometimes|numeric',
        ];
    }
}
