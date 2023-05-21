<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CurriculumYear;

class TimeSettingsEmployee extends Model
{
    use HasFactory;
    protected $table = 'time_settings_employee';
    protected $fillable = [
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'description',
        'saturday_check_in_start',
        'saturday_check_in_end',
        'saturday_check_out_start',
        'saturday_check_out_end',
    ];
}
