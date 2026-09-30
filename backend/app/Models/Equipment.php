<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'his_equipments';

    protected $primaryKey = 'eqp_id';

    public $timestamps = false;

    protected $fillable = ['eqp_code', 'eqp_name', 'eqp_vendor', 'eqp_desc', 'eqp_dept', 'eqp_status', 'eqp_qty'];
}
