<?php

namespace App\Services;

use App\Enums\FoundationStatus;
use Illuminate\Support\Facades\Log;
use Exception;

class MeyerhofService
{
    /**
     * Calculate Meyerhof bearing capacity based on CPT (sondir) data
     *
     * @param array $soilData
     * @return object
     * @throws Exception
     */
    public function calculateBearingCapacity(array $soilData): object
    {
        try {
            $requiredLoad = $soilData['required_load'] ?? 0;
            $qcValues = $soilData['qc_values'] ?? [];
            $fsValues = $soilData['fs_values'] ?? [];
            $pileDiameter = $soilData['pile_diameter'];
            $pileDepth = $soilData['pile_depth'];
            $depthInterval = $soilData['depth_interval'];
            $safetyFactor = $soilData['safety_factor'] ?? 2.5;

            // Geometry calculations
            $Ap = (pi() / 4) * pow($pileDiameter, 2); // Tip area (m2)
            $As = pi() * $pileDiameter * $pileDepth;  // Skin friction area (m2)

            // Find index corresponding to $pileDepth directly
            $tipIndex = (int) round($pileDepth / $depthInterval) - 1;
            
            // Calculate qc_avg: average of last 8 qc readings at pile tip
            $qcTipReadings = [];
            for ($i = max(0, $tipIndex - 7); $i <= $tipIndex; $i++) {
                if (isset($qcValues[$i])) {
                    $qcTipReadings[] = $qcValues[$i];
                }
            }
            $qc_avg_kgf = count($qcTipReadings) > 0 ? array_sum($qcTipReadings) / count($qcTipReadings) : 0;

            // Calculate fs_avg: average of all fs readings along pile shaft
            $fsShaftReadings = [];
            for ($i = 0; $i <= $tipIndex; $i++) {
                if (isset($fsValues[$i])) {
                    $fsShaftReadings[] = $fsValues[$i];
                }
            }
            $fs_avg_kgf = count($fsShaftReadings) > 0 ? array_sum($fsShaftReadings) / count($fsShaftReadings) : 0;

            // Convert kgf/cm2 to kPa for the final result (1 kgf/cm2 = 98.0665 kPa)
            $qc_avg_kpa = $qc_avg_kgf * 98.0665;
            $fs_avg_kpa = $fs_avg_kgf * 98.0665;

            // Ultimate bearing capacity in kN
            $qu_kn = ($qc_avg_kpa * $Ap) + ($fs_avg_kpa * $As);
            
            // Allowable bearing capacity in kN
            $qa_kn = ($safetyFactor > 0) ? $qu_kn / $safetyFactor : 0;

            // Peat detection logic (qc < 5 && Rf > 5%)
            $peatDetected = false;
            for ($i = 0; $i <= $tipIndex; $i++) {
                $qc = $qcValues[$i] ?? 0;
                $fs = $fsValues[$i] ?? 0;
                if ($qc > 0) {
                    $rf = ($fs / $qc) * 100;
                    if ($qc < 5 && $rf > 5) {
                        $peatDetected = true;
                        break;
                    }
                }
            }

            // Determine status based on QA, required load and peat presence
            if ($peatDetected) {
                $status = FoundationStatus::DANGER;
            } else if ($requiredLoad > 0) {
                if ($qa_kn < $requiredLoad) {
                    $status = FoundationStatus::DANGER;
                } else if ($qa_kn >= $requiredLoad && $qa_kn <= 1.1 * $requiredLoad) {
                    $status = FoundationStatus::WARNING;
                } else {
                    $status = FoundationStatus::SAFE;
                }
            } else {
                $status = FoundationStatus::SAFE; // Default if no required load specified
            }

            return (object) [
                'point_label'        => $soilData['point_label'] ?? 'Unknown',
                'qu_kn'              => round($qu_kn, 2),
                'qa_kn'              => round($qa_kn, 2),
                'safety_factor'      => $safetyFactor,
                'status'             => $status->value,
                'peat_warning'       => $peatDetected,
                'calculation_detail' => (object) [
                    'qc_avg' => round($qc_avg_kgf, 2),
                    'fs_avg' => round($fs_avg_kgf, 2),
                    'Ap'     => round($Ap, 4),
                    'As'     => round($As, 4)
                ]
            ];
        } catch (Exception $e) {
            Log::error("Meyerhof Calculation Error: " . $e->getMessage());
            throw $e;
        }
    }
}
