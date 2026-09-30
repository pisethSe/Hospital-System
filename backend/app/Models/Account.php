<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'his_accounts';

    protected $primaryKey = 'acc_id';

    public $timestamps = false;

    protected $fillable = ['acc_name', 'acc_desc', 'acc_type', 'acc_number', 'acc_amount'];
}
