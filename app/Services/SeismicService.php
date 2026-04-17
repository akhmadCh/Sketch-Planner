<?php

namespace App\Services;

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
}
