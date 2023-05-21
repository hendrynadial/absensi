<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimeSettingsTeacher;
use App\Models\PersonalCalender;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';
    public $fillable = [
        'id',
        'jenis_pegawai',
        'nip',
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_bergabung',
        'alamat',
        'email',
        'no_hp',
        'jabatan',
        'agama',
        'foto',
        'status_pegawai',
        'id_time_settings_employee',
        'unit',
    ];

    public $dates =[
        'tanggal_lahir',
        'tanggal_bergabung',
    ];

    public function RelasiPengaturanWaktuGuru()
    {
        return $this->belongsTo(TimeSettingsTeacher::class, 'id', 'teacher_id');
    }

    public function RelasiPersonalCalender()
    {
        return $this->belongsTo(PersonalCalender::class, 'id', 'employee_id');
    }

    public function scopeEmployee($query,$jenis=null,$jk=null)
    {
        return $query->where(function($x)use($jenis){
            if($jenis != null)
            {
                $x->where('jenis_pegawai',$jenis);
            }
        })
        ->where(function($x)use($jk){
            if($jk != null)
            {
                $x->where('jenis_kelamin',$jk);
            }
        });
    }


}
