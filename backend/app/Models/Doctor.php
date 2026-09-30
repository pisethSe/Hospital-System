<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'his_docs';

    protected $primaryKey = 'doc_id';

    public $timestamps = false;

    protected $fillable = ['doc_fname', 'doc_lname', 'doc_email', 'doc_pwd', 'doc_dept', 'doc_number', 'doc_dpic'];

    protected $hidden = ['doc_pwd'];

    /**
     * Legacy password column, used with HisPassword::verify().
     */
    public function getAuthPassword(): string
    {
        return (string) $this->doc_pwd;
    }

    public function getEmailAttribute(): string
    {
        return (string) $this->doc_email;
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->doc_fname} {$this->doc_lname}");
    }
}
