<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmaceutical extends Model
{
    protected $table = 'his_pharmaceuticals';

    protected $primaryKey = 'phar_id';

    public $timestamps = false;

    protected $fillable = ['phar_name', 'phar_bcode', 'phar_desc', 'phar_qty', 'phar_cat', 'phar_vendor'];
}
