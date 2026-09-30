<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionMedicine extends Model
{
    protected $table = 'his_prescription_medicines';

    public $timestamps = false;

    protected $fillable = ['pres_id', 'pres_number', 'medicine_name', 'medicine_qty', 'medicine_time'];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class, 'pres_id', 'pres_id');
    }
}
