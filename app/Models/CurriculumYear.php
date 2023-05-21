<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumYear extends Model
{
    use HasFactory;
    protected $table = 'curriculum_year';
    public $fillable = [
        'id',
        'curriculum_year',
        'start_date',
        'end_date',
        'description',
        'active',
    ];

    public $dates =[
        'start_date',
        'end_date',
    ];

    public function RelasiAbsen()
    {
        return $this->belongsTo(PersonalCalender::class, 'curriculum_year');
    }
}
