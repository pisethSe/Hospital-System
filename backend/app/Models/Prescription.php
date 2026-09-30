<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    protected $table = 'his_prescriptions';

    protected $primaryKey = 'pres_id';

    public $timestamps = false;

    protected $fillable = [
        'pres_pat_name',
        'pres_pat_age',
        'pres_pat_number',
        'pres_number',
        'pres_pat_addr',
        'pres_pat_type',
        'pres_pat_ailment',
        'pres_ins',
    ];

    protected $casts = [
        'pres_date' => 'datetime',
    ];

    public function medicines(): HasMany
    {
        return $this->hasMany(PrescriptionMedicine::class, 'pres_id', 'pres_id');
    }
}
