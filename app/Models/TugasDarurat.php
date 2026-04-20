<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasDarurat extends Model
{
    use HasFactory;

    protected $table = 'tugas_darurat';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'pemberi_tugas',
        'tgl_mulai',
        'tgl_selesai',
        'mekanik_id',
        'equipment_id',
        'nama_mekanik',
        'equipment',
        'tag_number',
        'eq_class',
        'bom',
        'task_list',
        'lokasi',
        'status',      // pending | dikerjakan | selesai
        'bukti_foto',
    ];

    // Casting tanggal otomatis
    protected $casts = [
        'tgl_mulai'   => 'datetime',
        'tgl_selesai' => 'datetime',
    ];

    /* ==========================
     | RELATIONS
     ========================== */

    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    public function equipmentRelasi()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    /* ==========================
     | STATUS LABEL
     ========================== */

    const STATUS_LABEL = [
        'pending'    => 'release order',
        'dikerjakan' => 'dikerjakan',
        'selesai'    => 'selesai',
    ];

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }

    /* ==========================
     | SCOPES
     ========================== */

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
