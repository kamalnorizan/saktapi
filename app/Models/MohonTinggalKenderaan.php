<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MohonTinggalKenderaan extends Model
{
    /** @use HasFactory<\Database\Factories\MohonTinggalKenderaanFactory> */
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $table = 'mohon_tinggal_kenderaan';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $guarded = ['id'];

    protected $dates = [
        'tarikh_mula',
        'tarikh_tamat',
        'tarikh_mohon',
        'deleted_at'
    ];

    protected $casts = [
        'tarikh_mula' => 'datetime',
        'tarikh_tamat' => 'datetime',
        'tarikh_mohon' => 'datetime',
        'status_permohonan' => 'integer',
    ];

    /**
     * Get the user that owns the permohonan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the lot associated with the permohonan
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class, 'id_lot');
    }

}
