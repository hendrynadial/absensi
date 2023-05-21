<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CurriculumYear;
use App\Models\Employee;

class TimeSettingsTeacher extends Model
{
    use HasFactory;
    protected $table = 'time_settings_teacher';
    protected $fillable = [
        'id',
        'teacher_id',
        'curriculum_year_id',
        'day',
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'description',
        'active',
    ];
    
    public function RelasiGuru()
    {
        return $this->belongsTo(Employee::class, 'teacher_id');
    }

    public function RelasiTahunAjaran()
    {
        return $this->belongsTo(CurriculumYear::class, 'curriculum_year_id');
    }
}


