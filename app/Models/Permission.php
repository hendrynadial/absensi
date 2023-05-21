<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;


class Permission extends Model
{
    use HasFactory;
    protected $table = 'permission';
    public $fillable = [
        'id',
        'employee_id',
        'start_date',
        'end_date',
        'file',
        'remark',
        'status',
        'reason',
    ];

    public function RelasiPegawai()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    

}

