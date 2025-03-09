<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrayerTimesController extends Controller
{
    /**
     * Get prayer times for a specific zone.
     *
     * @param string $zone The JAKIM zone code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByZone($zone)
    {
        try {
            Log::info("Fetching prayer times for zone: {$zone}");

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0',
                'Accept' => '*/*',
                'Referer' => 'https://www.e-solat.gov.my/',
            ])->get('https://www.e-solat.gov.my/index.php', [
                'r' => 'esolatApi/TakwimSolat',
                'period' => 'today',
                'zone' => $zone
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Successfully fetched prayer times for zone: {$zone}");
                return response()->json($data);
            }

            Log::error("Failed to fetch prayer times for zone: {$zone}. Status: {$response->status()}");
            return response()->json([
                'error' => 'Failed to fetch prayer times',
                'status' => $response->status(),
                'message' => $response->body()
            ], 500);
        } catch (\Exception $e) {
            Log::error("Exception when fetching prayer times for zone: {$zone}. Error: {$e->getMessage()}");
            return response()->json([
                'error' => 'Failed to fetch prayer times',
                'message' => $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
