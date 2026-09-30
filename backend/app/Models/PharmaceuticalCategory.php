<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmaceuticalCategory extends Model
{
    protected $table = 'his_pharmaceuticals_categories';

    protected $primaryKey = 'pharm_cat_id';

    public $timestamps = false;

    protected $fillable = ['pharm_cat_name', 'pharm_cat_vendor', 'pharm_cat_desc'];
}
