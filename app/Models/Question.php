<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'question_text',
        'points',
        'options',
        'correct_answer'
    ];

    protected $casts = [
        'options' => 'array', // تحويل تلقائي من JSON إلى array
        'points' => 'integer'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
