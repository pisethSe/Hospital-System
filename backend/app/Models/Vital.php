<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $table = 'his_vitals';

    protected $primaryKey = 'vit_id';

    public $timestamps = false;

    protected $fillable = [
        'vit_number',
        'vit_pat_number',
        'vit_bodytemp',
        'vit_heartpulse',
        'vit_resprate',
        'vit_bloodpress',
    ];

    protected $casts = [
        'vit_daterec' => 'datetime',
    ];
}
