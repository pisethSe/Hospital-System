<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    protected $table = 'his_surgery';

    protected $primaryKey = 's_id';

    public $timestamps = false;

    protected $fillable = [
        's_number',
        's_doc',
        's_pat_number',
        's_pat_name',
        's_pat_ailment',
        's_pat_date',
        's_pat_status',
    ];

    protected $casts = [
        's_pat_date' => 'datetime',
    ];
}
