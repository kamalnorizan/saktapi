<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    /** @use HasFactory<\Database\Factories\LotFactory> */
    use HasFactory;

    public $timestamps = true;

    protected $table = 'lots';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $guarded = ['id'];
}
