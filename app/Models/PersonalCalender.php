<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class PersonalCalender extends Model
{
    use HasFactory;
    protected $table = 'personal_calender';
    protected $fillable = [
        'employee_id',
        'curriculum_year_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'status_check_in',
        'status_check_out',
        'reason',
        'latitude_check_in',
        'longitude_check_in',
        'latitude_check_out',
        'longitude_check_out',
        'photo_check_in',
        'photo_check_out',
    ];
    
    public function RelasiPegawai()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function scopeActive($query,$employeeID,$status,$bulan,$tahun)
    {
        return $query->where('employee_id',$employeeID)->where('status', $status)->whereMonth('date',$bulan)->whereYear('date',$tahun);
    }

    public function scopeCountAbsenPegawai($query,$employeeID,$bulan,$tahun,$tanggal_bergabung,$today)
    {
        return $query->where('employee_id',$employeeID)
        ->whereMonth('date',$bulan)
        ->where('year',$tahun)
        ->where('date','>=',$tanggal_bergabung)
        ->where('date','<=',$today)
        ->where('status','Absen');
    }

    public function scopeCountAbsenGuru($query,$employeeID,$bulan,$tahunAjaran,$tanggal_bergabung,$today)
    {
        return $query->where('employee_id',$employeeID)
        ->whereMonth('date',$bulan)
        ->where('curriculum_year_id',$tahunAjaran)
        ->where('date','>=',$tanggal_bergabung)
        ->where('date','<=',$today)
        ->where('status','Absen');
    }

    public function scopeAmountStatusAttendance($query,$employeeID,$status,$month,$year)
    {
        return $query->where('employee_id',$employeeID)->where('status', $status)->whereMonth('date',$month)->whereYear('date',$year);
    }    
}
