<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SeismicService
{
    /**
     * Array pemetaan statis sederhana untuk 5 kota besar beserta properti gempa
     */
    protected array $cities = [
        'Jakarta' => [
            'lat' => -6.2088,
            'lng' => 106.8456,
            'data' => ['ss' => 0.75, 's1' => 0.35, 'zone' => 'Medium']
        ],
        'Banjarmasin' => [
            'lat' => -3.3167,
            'lng' => 114.5901,
            'data' => ['ss' => 0.15, 's1' => 0.06, 'zone' => 'Low']
        ],
        'Surabaya' => [
            'lat' => -7.2504,
            'lng' => 112.7688,
            'data' => ['ss' => 0.65, 's1' => 0.28, 'zone' => 'Medium']
        ],
        'Bandung' => [
            'lat' => -6.9175,
            'lng' => 107.6191,
            'data' => ['ss' => 0.95, 's1' => 0.42, 'zone' => 'High']
        ],
        'Denpasar' => [
            'lat' => -8.6500,
            'lng' => 115.2167,
            'data' => ['ss' => 1.20, 's1' => 0.55, 'zone' => 'High']
        ]
    ];

    /**
     * Dapatkan parameter gempa berdasarkan koordinat yang paling mendekati kota
     *
     * @param float $lat
     * @param float $lng
     * @return object
     */
    public function getSeismicData(float $lat, float $lng): object
    {
        $closestData = null;
        $minDistance = INF;

        foreach ($this->cities as $city) {
            $distance = $this->calculateDistance($lat, $lng, $city['lat'], $city['lng']);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestData = $city['data'];
            }
        }

        if (!$closestData) {
            $closestData = ['ss' => 0.0, 's1' => 0.0, 'zone' => 'Unknown'];
        }

        return (object) $closestData;
    }

    /**
     * Hitung jarak Haversine (atau pythagoras simple) antara 2 koordinat.
     */
    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        // Simple euclidean distance approximation
        return sqrt(pow($lat1 - $lat2, 2) + pow($lng1 - $lng2, 2));
    }

    /**
     * Fetch earthquake data from USGS API
     * 
     * @param float $lat
     * @param float $lng
     * @param int $radiusKm
     * @param float $minMagnitude
     * @param int $days Historical data lookback period
     * @return array
     */
    public function getUSGSEarthquakeData(float $lat, float $lng, int $radiusKm = 200, float $minMagnitude = 4.5, int $days = 365): array
    {
        try {
            $startTime = now()->subDays($days)->toDateString();
            $endTime = now()->toDateString();

            $response = Http::timeout(10)->get('https://earthquake.usgs.gov/fdsnws/event/1/query', [
                'format' => 'geojson',
                'latitude' => $lat,
                'longitude' => $lng,
                'maxradiuskm' => $radiusKm,
                'minmagnitude' => $minMagnitude,
                'starttime' => $startTime,
                'endtime' => $endTime,
                'orderby' => 'magnitude',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'success',
                    'count' => $data['metadata']['count'] ?? 0,
                    'features' => $data['features'] ?? [],
                    'metadata' => $data['metadata'] ?? [],
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to fetch data from USGS API',
                'count' => 0,
                'features' => [],
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'count' => 0,
                'features' => [],
            ];
        }
    }

    /**
     * Get earthquake risk level based on earthquake data
     * 
     * @param array $earthquakeData
     * @return string
     */
    public function calculateRiskLevel(array $earthquakeData): string
    {
        if (empty($earthquakeData['features'])) {
            return 'Low';
        }

        $maxMagnitude = 0;
        foreach ($earthquakeData['features'] as $feature) {
            $mag = $feature['properties']['mag'] ?? 0;
            if ($mag > $maxMagnitude) {
                $maxMagnitude = $mag;
            }
        }

        if ($maxMagnitude >= 6.5) {
            return 'Very High';
        } elseif ($maxMagnitude >= 6.0) {
            return 'High';
        } elseif ($maxMagnitude >= 5.0) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }
}
