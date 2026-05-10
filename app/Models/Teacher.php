<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    protected $table = 'teachers';

    use HasFactory;

    protected $fillable = ['full_name', 'subject_specialization','phone_number','password'];

    // علاقة المعلم بالاختبارات التي ينشئها
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

}
