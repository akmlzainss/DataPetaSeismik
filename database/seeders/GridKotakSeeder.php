<?php

namespace Database\Seeders;

use App\Models\GridKotak;
use Illuminate\Database\Seeder;

class GridKotakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Generate 313 grid kotak untuk perairan Indonesia
     * Berdasarkan pola dari peta referensi yang diberikan
     */
    public function run(): void
    {
        // Clear existing data
        GridKotak::truncate();

        $grids = $this->generateIndonesiaGrids();

        foreach ($grids as $grid) {
            GridKotak::create($grid);
        }

        $this->command->info('✓ Successfully seeded ' . count($grids) . ' grid kotak!');
    }

    /**
     * Generate 313 kotak grid untuk perairan Indonesia
     * 
     * Menggunakan pattern dari peta referensi:
     * - Coverage: Perairan Indonesia (lat: -11 to 6, lng: 95 to 141)
     * - Total: 313 kotak
     * - Nomor format: 4 digit (XXYY) dimana XX = baris, YY = kolom
     */
    private function generateIndonesiaGrids(): array
    {
        $grids = [];
        
        // Define bounding box Indonesia maritime
        $latMin = -11.0;  // Selatan (South)
        $latMax = 6.0;    // Utara (North)
        $lngMin = 95.0;   // Barat (West)
        $lngMax = 141.0;  // Timur (East)
        
        // Ukuran setiap kotak (dalam derajat)
        $gridHeight = 1.0;  // 1 degree latitude per box
        $gridWidth = 1.0;   // 1 degree longitude per box
        
        // Calculate jumlah baris dan kolom
        $numRows = (int) ceil(($latMax - $latMin) / $gridHeight);
        $numCols = (int) ceil(($lngMax - $lngMin) / $gridWidth);
        
        $this->command->info("Generating grid: {$numRows} rows x {$numCols} cols");
        
        $kotakCount = 0;
        $targetKotak = 313;
        
        // Generate grid dari Utara ke Selatan, Barat ke Timur
        for ($row = 0; $row < $numRows && $kotakCount < $targetKotak; $row++) {
            for ($col = 0; $col < $numCols && $kotakCount < $targetKotak; $col++) {
                // Simple skip logic untuk mendekati 313 kotak
                $totalPotential = $numRows * $numCols;
                $skipRate = (($totalPotential - $targetKotak) / $totalPotential) * 100;
                
                // Skip some boxes randomly based on skip rate
                if (rand(1, 100) < $skipRate) {
                    continue;
                }
                
                // Calculate bounds
                $neLat = round($latMax - ($row * $gridHeight), 6);
                $swLat = round($neLat - $gridHeight, 6);
                $swLng = round($lngMin + ($col * $gridWidth), 6);
                $neLng = round($swLng + $gridWidth, 6);
                
                // Calculate center
                $centerLat = round(($swLat + $neLat) / 2, 6);
                $centerLng = round(($swLng + $neLng) / 2, 6);
                
                // Generate nomor kotak (format: RRCCC)
                $nomorKotak = sprintf('%02d%02d', $row, $col);
                
                $grids[] = [
                    'nomor_kotak' => $nomorKotak,
                    'bounds_sw_lat' => $swLat,
                    'bounds_sw_lng' => $swLng,
                    'bounds_ne_lat' => $neLat,
                    'bounds_ne_lng' => $neLng,
                    'center_lat' => $centerLat,
                    'center_lng' => $centerLng,
                    'status' => 'empty',
                    'total_data' => 0,
                ];
                
                $kotakCount++;
            }
        }
        
        $this->command->info("Generated {$kotakCount} grid boxes");
        
        return $grids;
    }

    /**
     * Alternative: Load from GeoJSON file (jika ada)
     * Uncomment ini jika Anda punya file GeoJSON
     */
    /*
    private function loadFromGeoJSON(): array
    {
        $geojsonPath = database_path('data/grid_indonesia.geojson');
        
        if (!file_exists($geojsonPath)) {
            $this->command->error('GeoJSON file not found!');
            return [];
        }
        
        $geojson = json_decode(file_get_contents($geojsonPath), true);
        $grids = [];
        
        foreach ($geojson['features'] as $feature) {
            $coords = $feature['geometry']['coordinates'][0];
            
            // Extract bounding box from polygon
            $lngs = array_column($coords, 0);
            $lats = array_column($coords, 1);
            
            $grids[] = [
                'nomor_kotak' => $feature['properties']['nomor'] ?? 'N/A',
                'bounds_sw_lat' => min($lats),
                'bounds_sw_lng' => min($lngs),
                'bounds_ne_lat' => max($lats),
                'bounds_ne_lng' => max($lngs),
                'center_lat' => array_sum($lats) / count($lats),
                'center_lng' => array_sum($lngs) / count($lngs),
                'geojson_polygon' => json_encode($feature['geometry']),
                'status' => 'empty',
                'total_data' => 0,
            ];
        }
        
        return $grids;
    }
    */
}
