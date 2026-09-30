<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientTransfer extends Model
{
    protected $table = 'his_patient_transfers';

    protected $primaryKey = 't_id';

    public $timestamps = false;

    protected $fillable = ['t_hospital', 't_date', 't_pat_name', 't_pat_number', 't_status'];
}
