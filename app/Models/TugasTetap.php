<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasTetap extends Model
{
    use HasFactory;

    protected $table = 'tugas_tetap';

    protected $fillable = [
        'pemberi_tugas',
        'mekanik_id',
        'nama_mekanik',
        'equipment_id',
        'jenis_tugas',      // mingguan | bulanan | tahunan
        'hari_mingguan',
        'tanggal_bulanan',
        'tanggal_tahunan',
        'tanggal_mulai',
        'equipment',
        'tag_number',
        'eq_class',
        'bom',
        'task_list',
        'lokasi',
        'status',           // pending | dikerjakan | selesai
        'bukti_foto',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_tahunan' => 'date',
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

    public function scopeJenisTugas($query, $jenis)
    {
        return $query->where('jenis_tugas', $jenis);
    }
}
