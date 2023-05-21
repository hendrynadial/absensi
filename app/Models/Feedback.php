<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedback';
    protected $fillable =  [
        'user_id',
        'device_type',
        'photo',
        'problem_description',
        'problem_solving_description',
        'status',
    ];   
}
