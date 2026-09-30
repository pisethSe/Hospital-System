<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'his_admin';

    protected $primaryKey = 'ad_id';

    public $timestamps = false;

    protected $fillable = ['ad_fname', 'ad_lname', 'ad_email', 'ad_pwd', 'ad_dpic'];

    protected $hidden = ['ad_pwd'];

    /**
     * Legacy password column, used with HisPassword::verify().
     */
    public function getAuthPassword(): string
    {
        return (string) $this->ad_pwd;
    }

    public function getEmailAttribute(): string
    {
        return (string) $this->ad_email;
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->ad_fname} {$this->ad_lname}");
    }
}
