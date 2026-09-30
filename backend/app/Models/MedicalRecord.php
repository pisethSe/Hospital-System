<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $table = 'his_medical_records';

    protected $primaryKey = 'mdr_id';

    public $timestamps = false;

    protected $fillable = [
        'mdr_number',
        'mdr_pat_name',
        'mdr_pat_adr',
        'mdr_pat_age',
        'mdr_pat_ailment',
        'mdr_pat_number',
        'mdr_pat_prescr',
    ];

    protected $casts = [
        'mdr_date_rec' => 'datetime',
    ];
}
