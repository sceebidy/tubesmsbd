<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPanen extends Model
{
    protected $table = 'laporan_panen';

    protected $fillable = [

        'mandor_id',
        'pekerja_id',
        'tanggal',

        'brondolan_kg',
        'janjang',

        'total_tandan',
        'total_berat_kg',

        'catatan',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | AUTO TOTAL TANDAN
    |--------------------------------------------------------------------------
    |
    | otomatis mengisi total_tandan dari jumlah janjang
    |
    */

    protected static function booted()
    {
        static::saving(function ($laporan) {

            // total_tandan otomatis mengikuti janjang
            $laporan->total_tandan = $laporan->janjang;

        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function pekerja()
    {
        return $this->belongsTo(User::class, 'pekerja_id');
    }

    public function mandor()
    {
        return $this->belongsTo(User::class, 'mandor_id');
    }
}