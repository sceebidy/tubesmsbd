<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatroliSecurity extends Model
{
    use HasFactory;
    
    protected $table = 'patroli_security';
    
    protected $fillable = [
        'user_id',
        'nama_area',        // Sesuai tabel: nama_area
        'keterangan',
        'foto',
        'waktu_patroli',
    ];
    
    protected $casts = [
        'waktu_patroli' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}