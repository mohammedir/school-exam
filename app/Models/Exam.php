<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'scheduled_at',
        'duration_minutes',
        'subject',
        'results_visible',
        'instructions',
        'target_category'
    ];

    /**
     * تحويل الحقول إلى أنواع بيانات محددة تلقائياً
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'results_visible' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    /**
     * علاقة الاختبار بالمعلم
     * كل اختبار ينتمي إلى معلم واحد (مستخدم برتبة معلم)
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * علاقة الاختبار بالأسئلة
     * الاختبار الواحد يحتوي على العديد من الأسئلة
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * علاقة الاختبار بالنتائج
     * الاختبار الواحد له العديد من سجلات نتائج الطلاب
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * "Scope" للتحقق مما إذا كان الاختبار متاحاً حالياً بناءً على الوقت والمدة
     */
    public function scopeActive($query)
    {
        return $query->where('scheduled_at', '<=', now())
            ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE) >= ?', [now()]);
    }


    public function calculateScore($answers)
    {
        $score = 0;
        $total = 0;

        foreach ($this->questions as $question) {
            $total += $question->points;

            // تحويل options إذا كانت string
            $options = is_string($question->options)
                ? json_decode($question->options, true)
                : $question->options;

            // التحقق من صحة الإجابة
            if (isset($answers[$question->id]) &&
                $answers[$question->id] === $question->correct_answer) {
                $score += $question->points;
            }
        }

        return [
            'score' => $score,
            'total' => $total,
            'percentage' => $total > 0 ? ($score / $total) * 100 : 0
        ];
    }

}
