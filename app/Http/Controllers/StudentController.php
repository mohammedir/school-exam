<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //
    public function dashboard(){
        $student = Auth::guard('student')->user();
        $exam = Exam::query()->orderBy('id', 'desc')->get();
        return view('student.dashboard',compact('student','exam'));
    }
    public function start($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);

        $result = \App\Models\Result::where('exam_id', $id)
            ->where('student_id', auth('student')->id())
            ->first();

        if ($result != null) {
            return redirect()->route('student.dashboard');
        }

        // عشوائية الأسئلة باستخدام orderBy RAW
        $questions = $exam->questions()
            ->inRandomOrder() // Laravel's built-in random ordering
            ->get();

        // خلط الخيارات لكل سؤال
        $questions->each(function ($question) {
            if ($question->options) {
                $options = is_string($question->options)
                    ? json_decode($question->options, true)
                    : $question->options;
                $question->shuffled_options = collect($options)->shuffle()->values()->toArray();
            }
        });

        return view('student.exam', compact('exam', 'questions'));

    }
    public function result($examId)
    {
        $studentId = Auth::guard('student')->id();

        $exam = Exam::with('questions')->findOrFail($examId);

        $result = Result::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        // الإجابات المخزنة JSON
        $answers = $result->answers;

        $score = 0;
        $total = 0;

        foreach ($exam->questions as $q) {
            $total += $q->points;

            if (isset($answers[$q->id]) && $answers[$q->id] == $q->correct_answer) {
                $score += $q->points;
            }
        }

        return view('student.exam-result', compact('exam', 'answers', 'score', 'total'));
    }
    // حفظ الإجابات
    public function submit(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'answers' => 'required|array'
        ]);

        $studentId = Auth::guard('student')->id();

        // جلب الاختبار مع الأسئلة
        $exam = Exam::with('questions')->findOrFail($request->exam_id);

        // حساب النتيجة
        $score = 0;
        $totalScore = 0;

        foreach ($exam->questions as $question) {
            $totalScore += $question->points;

            // التحقق من صحة الإجابة
            if (isset($request->answers[$question->id]) &&
                $request->answers[$question->id] === $question->correct_answer) {
                $score += $question->points;
            }
        }
        // تخزين النتيجة مع البيانات المحسوبة
        Result::create([
            'student_id' => $studentId,
            'exam_id' => $request->exam_id,
            'answers' => $request->answers, // تخزين JSON كامل
            'score' => $score,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تسليم الامتحان بنجاح',
            'data' => [
                'score' => $score,
                'total_score' => $totalScore,
            ]
        ]);
    }
    public function studentLogout(Request $request){
        Auth::guard('student')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
