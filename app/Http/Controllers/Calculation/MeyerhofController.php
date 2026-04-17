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
            'sap2000_vertical_load' => $data['sap2000_load'] ?? null,
            'pile_group_n'      => $data['n_pile'] ?? 1,
            'pile_group_m'      => $data['m_pile'] ?? 1,
            'latitude'          => $data['latitude'] ?? null,
            'longitude'         => $data['longitude'] ?? null,
            'calculated_at'     => now(),
            // Assuming required data for other relationships can be null.
            'created_by'        => auth()->id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Perhitungan berhasil.',
            'data'    => new FoundationResultResource($result),
            'calc_id' => $calculation->id,
        ], 200);
    }

    public function importSap(\Illuminate\Http\Request $request, \App\Services\SapImportService $service): JsonResponse
    {
        $request->validate(['file' => 'required|file']);
        $loads = $service->parseCsv($request->file('file')->path());
        
        $maxLoad = 0;
        foreach($loads as $l) {
            if (isset($l->vertical_load) && $l->vertical_load > $maxLoad) {
                $maxLoad = $l->vertical_load;
            }
        }
        return response()->json(['data' => ['max_load' => $maxLoad]]);
    }

    public function getSeismic(\Illuminate\Http\Request $request, \App\Services\SeismicService $service): JsonResponse
    {
        $request->validate(['lat' => 'required|numeric', 'lng' => 'required|numeric']);
        $data = $service->getSeismicData((float)$request->lat, (float)$request->lng);
        return response()->json(['data' => $data]);
    }

    public function downloadPdf(int $id)
    {
        $calc = \App\Models\MeyerhofCalculation::findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('calculation.pdf', ['calc' => $calc]);
        return $pdf->download('meyerhof_report_' . $calc->id . '.pdf');
    }
}
