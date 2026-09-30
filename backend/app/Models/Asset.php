<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'his_assets';

    protected $primaryKey = 'asst_id';

    public $timestamps = false;

    protected $fillable = ['asst_name', 'asst_desc', 'asst_vendor', 'asst_status', 'asst_dept'];
}
