<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'his_payrolls';

    protected $primaryKey = 'pay_id';

    public $timestamps = false;

    protected $fillable = [
        'pay_number',
        'pay_doc_name',
        'pay_doc_number',
        'pay_doc_email',
        'pay_emp_salary',
        'pay_status',
        'pay_descr',
    ];

    protected $casts = [
        'pay_date_generated' => 'datetime',
    ];
}
