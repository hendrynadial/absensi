<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalenderHoliday extends Model
{
    use HasFactory;
    protected $table = 'calender_holiday';
    public $fillable = [
        'id',
        'day',
        'date',
        'month',
        'year',
        'reason',
    ];

    public $dates =[
        'date',
    ];
}
