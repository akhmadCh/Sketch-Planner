<?php

namespace App\Services;

class StabilityService
{
    public function checkStability(array $data): object
    {
        $qu_kn        = $data['qu_kn'];
        $qa_kn        = $data['qa_kn'];
        $pileDepth    = $data['pile_depth'];
        $pileDiameter = $data['pile_diameter'];
        $lateralLoad  = $data['lateral_load'] ?? 0;
        $moment       = $data['moment'] ?? 0;

        // Shear stability
        $shearCapacity    = 0.3 * $qu_kn;
        $shearSafetyRatio = $lateralLoad > 0
            ? round($shearCapacity / $lateralLoad, 3)
            : null;
        $shearStatus = $lateralLoad > 0
            ? ($shearSafetyRatio >= 1.5 ? 'AMAN' : 'BAHAYA')
            : 'TIDAK DIPERIKSA';

        // Moment stability
        $momentCapacity    = $qu_kn * $pileDepth / 6;
        $momentSafetyRatio = $moment > 0
            ? round($momentCapacity / $moment, 3)
            : null;
        $momentStatus = $moment > 0
            ? ($momentSafetyRatio >= 1.5 ? 'AMAN' : 'BAHAYA')
            : 'TIDAK DIPERIKSA';

        // Overall status
        $overallStatus = 'AMAN';
        if ($shearStatus === 'BAHAYA' || $momentStatus === 'BAHAYA') {
            $overallStatus = 'BAHAYA';
        }

        return (object) [
            'qu_kn'               => $qu_kn,
            'qa_kn'               => $qa_kn,
            'shear_capacity_kn'   => round($shearCapacity, 2),
            'shear_safety_ratio'  => $shearSafetyRatio,
            'shear_status'        => $shearStatus,
            'moment_capacity_knm' => round($momentCapacity, 2),
            'moment_safety_ratio' => $momentSafetyRatio,
            'moment_status'       => $momentStatus,
            'overall_status'      => $overallStatus,
            'detail' => (object) [
                'pile_depth'     => $pileDepth,
                'pile_diameter'  => $pileDiameter,
                'lateral_load'   => $lateralLoad,
                'applied_moment' => $moment,
            ]
        ];
    }
}