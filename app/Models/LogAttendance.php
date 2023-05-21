<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAttendance extends Model
{
    use HasFactory;
    protected $table = 'log_attendance';
    public $fillable = [
        'id_user',
        'username',
        'date',
        'before',
        'after',
        'reason',
    ];
}
