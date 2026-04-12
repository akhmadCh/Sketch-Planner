<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoundationResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'point_label'        => $this->point_label,
            'qu_kn'              => $this->qu_kn,
            'qa_kn'              => $this->qa_kn,
            'safety_factor'      => $this->safety_factor,
            'status'             => $this->status,
            'peat_warning'       => $this->peat_warning,
            'calculation_detail' => $this->calculation_detail,
        ];
    }
}
