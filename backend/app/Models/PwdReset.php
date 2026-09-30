<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PwdReset extends Model
{
    protected $table = 'his_pwdresets';

    public $timestamps = false;

    protected $fillable = ['email', 'token', 'status', 'pwd'];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
