<?php

namespace App\Observers;

use App\Models\MeyerhofCalculation;
use Illuminate\Support\Facades\Log;

class MeyerhofCalculationObserver
{
    /**
     * Handle the MeyerhofCalculation "created" event.
     */
    public function created(MeyerhofCalculation $calculation): void
    {
        Log::info("Audit Trail: Meyerhof Calculation Created", [
            'calculation_id' => $calculation->id,
            'project_id'     => $calculation->project_id,
            'status'         => $calculation->status,
            'created_by'     => $calculation->created_by,
        ]);
        
        if ($calculation->peat_warning) {
            Log::warning("Audit Trail: PEAT DETECTED (Lahan Gambut) on project ID {$calculation->project_id}, point {$calculation->point_label}");
        }
    }

    /**
     * Handle the MeyerhofCalculation "updated" event.
     */
    public function updated(MeyerhofCalculation $calculation): void
    {
        // Log changes to critical calculation data
        $changes = $calculation->getDirty();
        
        Log::info("Audit Trail: Meyerhof Calculation Updated", [
            'calculation_id' => $calculation->id,
            'changed_data'   => $changes,
            'updated_by'     => auth()->id(),
        ]);
    }

    /**
     * Handle the MeyerhofCalculation "deleted" event.
     */
    public function deleted(MeyerhofCalculation $calculation): void
    {
        Log::info("Audit Trail: Meyerhof Calculation Deleted", [
            'calculation_id' => $calculation->id,
        ]);
    }
}
