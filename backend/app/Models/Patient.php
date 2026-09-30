<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'his_patients';

    protected $primaryKey = 'pat_id';

    public $timestamps = false;

    protected $fillable = [
        'pat_fname',
        'pat_lname',
        'pat_dob',
        'pat_age',
        'pat_number',
        'pat_addr',
        'pat_phone',
        'pat_type',
        'pat_ailment',
        'pat_room_number',
        'pat_discharge_status',
        'pat_walk_out_date',
    ];

    protected $casts = [
        'pat_date_joined' => 'datetime',
        'pat_walk_out_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->pat_fname} {$this->pat_lname}");
    }

    /**
     * Active = registered but not yet walked out.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('pat_walk_out_date');
    }
}
