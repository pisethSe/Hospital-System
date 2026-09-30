<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'his_vendor';

    protected $primaryKey = 'v_id';

    public $timestamps = false;

    protected $fillable = ['v_number', 'v_name', 'v_adr', 'v_mobile', 'v_email', 'v_phone', 'v_desc'];
}
