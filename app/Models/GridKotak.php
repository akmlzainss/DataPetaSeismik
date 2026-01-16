<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GridKotak extends Model
{
    use HasFactory;

    protected $table = 'grid_kotak';

    protected $fillable = [
        'nomor_kotak',
        'bounds_sw_lat',
        'bounds_sw_lng',
        'bounds_ne_lat',
        'bounds_ne_lng',
        'center_lat',
        'center_lng',
        'geojson_polygon',
        'status',
        'total_data',
    ];

    protected $casts = [
        'bounds_sw_lat' => 'decimal:6',
        'bounds_sw_lng' => 'decimal:6',
        'bounds_ne_lat' => 'decimal:6',
        'bounds_ne_lng' => 'decimal:6',
        'center_lat' => 'decimal:6',
        'center_lng' => 'decimal:6',
        'total_data' => 'integer',
    ];

    /**
     * Relasi many-to-many dengan DataSurvei
     * Satu grid kotak bisa punya banyak data survei
     */
    public function dataSurvei(): BelongsToMany
    {
        return $this->belongsToMany(
            DataSurvei::class,
            'grid_seismik',
            'grid_kotak_id',
            'data_survei_id'
        )
        ->withPivot(['assigned_by', 'assigned_at']);
    }

    /**
     * Helper: Get all data survei in this grid box
     */
    public function getSurveiListAttribute()
    {
        return $this->dataSurvei()->with('pengunggah')->get();
    }

    /**
     * Helper: Check if grid box is filled (has data)
     */
    public function isFilledAttribute(): bool
    {
        return $this->total_data > 0;
    }

    /**
     * Helper: Get bounds as array untuk Leaflet
     */
    public function getBoundsArrayAttribute(): array
    {
        return [
            [(float) $this->bounds_sw_lat, (float) $this->bounds_sw_lng],
            [(float) $this->bounds_ne_lat, (float) $this->bounds_ne_lng]
        ];
    }

    /**
     * Helper: Get center point as array
     */
    public function getCenterArrayAttribute(): array
    {
        return [(float) $this->center_lat, (float) $this->center_lng];
    }

    /**
     * Scope: Only filled grids
     */
    public function scopeFilled($query)
    {
        return $query->where('status', 'filled')
                     ->orWhere('total_data', '>', 0);
    }

    /**
     * Scope: Only empty grids
     */
    public function scopeEmpty($query)
    {
        return $query->where('status', 'empty')
                     ->where('total_data', 0);
    }
}
