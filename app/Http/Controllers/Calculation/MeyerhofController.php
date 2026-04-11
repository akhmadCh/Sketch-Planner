<?php

namespace App\Http\Controllers\Calculation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calculation\MeyerhofRequest;
use App\Services\MeyerhofService;
use App\Http\Resources\FoundationResultResource;
use App\Models\MeyerhofCalculation;
use Illuminate\Http\JsonResponse;

class MeyerhofController extends Controller
{
    protected MeyerhofService $meyerhofService;

    public function __construct(MeyerhofService $meyerhofService)
    {
        $this->meyerhofService = $meyerhofService;
    }

    public function calculate(MeyerhofRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['project_id'] = $id;

        $result = $this->meyerhofService->calculateBearingCapacity($data);

        // Calculate and save the record for Audit Trail
        $calculation = MeyerhofCalculation::create([
            'project_id'        => $id,
            'calc_name'         => 'Meyerhof calc for ' . $data['point_label'],
            'point_label'       => $data['point_label'],
            'total_capacity_qu' => $result->qu_kn,
            'allowable_load_qa' => $result->qa_kn,
            'safety_factor'     => $result->safety_factor,
            'status'            => $result->status,
            'peat_warning'      => $result->peat_warning,
            'calculated_at'     => now(),
            // Assuming required data for other relationships can be null.
            'created_by'        => auth()->id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Perhitungan berhasil.',
            'data'    => new FoundationResultResource($result),
        ], 200);
    }
}
