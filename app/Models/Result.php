<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'exam_id',
        'answers',
        'score'
    ];
    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime'

    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
    // Accessor للحصول على النسبة المئوية المنسقة
    public function getPercentageFormattedAttribute()
    {
        return number_format($this->percentage, 2) . '%';
    }
    // Accessor للحصول على التقييم
    public function getEvaluationAttribute()
    {
        $score = $this->percentage;

        if ($score >= 85) {
            return ['text' => 'ممتاز', 'icon' => 'fa-star', 'class' => 'score-excellent'];
        } elseif ($score >= 75) {
            return ['text' => 'جيد جداً', 'icon' => 'fa-star-half-alt', 'class' => 'score-good'];
        } elseif ($score >= 65) {
            return ['text' => 'جيد', 'icon' => 'fa-smile', 'class' => 'score-average'];
        } elseif ($score >= 50) {
            return ['text' => 'مقبول', 'icon' => 'fa-meh', 'class' => 'score-average'];
        } else {
            return ['text' => 'راسب', 'icon' => 'fa-frown', 'class' => 'score-poor'];
        }
    }


}
