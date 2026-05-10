<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $table = 'students';

    use HasFactory;
    protected $fillable = ['full_name', 'seating_number','phone_number','class_room','student_branch'];


    // علاقة الطالب بالنتائج التي حصل عليها
    public function results() {
        return $this->hasMany(Result::class);
    }
    // العلاقة مع الاختبارات المنجزة (من خلال النتائج)
    public function completedExams()
    {
        return $this->belongsToMany(Exam::class, 'results')
            ->withPivot('score', 'answers')
            ->withTimestamps();
    }
}
