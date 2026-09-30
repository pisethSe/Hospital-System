<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $table = 'his_laboratory';

    protected $primaryKey = 'lab_id';

    public $timestamps = false;

    protected $fillable = [
        'lab_pat_name',
        'lab_pat_ailment',
        'lab_pat_number',
        'lab_pat_tests',
        'lab_pat_results',
        'lab_number',
    ];

    protected $casts = [
        'lab_date_rec' => 'datetime',
    ];
}
