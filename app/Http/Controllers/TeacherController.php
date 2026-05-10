<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    // عرض لوحة التحكم
    public function dashboard()
    {
        $exams = Exam::where('teacher_id', Auth::id())
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.dashboard', compact('exams'));
    }

    // رفع الصور للمقالات
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('exam-images', $filename, 'public');

            return response()->json([
                'url' => asset('storage/' . $path),
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'فشل رفع الصورة'
            ], 500);
        }
    }

    // إنشاء اختبار جديد
    public function examsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required|array|min:4|max:4',
            'questions.*.correct_answer' => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $exam = Exam::create([
                    'teacher_id' => Auth::id(),
                    'title' => $request->title,
                    'scheduled_at' => $request->date,
                    'duration_minutes' => $request->duration,
                    'subject' => Auth::user()->subject ?? 'عام',
                    'is_published' => false,
                ]);

                foreach ($request->questions as $qData) {
                    // حفظ نص السؤال كما هو مع HTML والصور
                    $exam->questions()->create([
                        'question_text' => $qData['text'], // يحتوي على HTML والصور
                        'points' => $qData['points'],
                        'options' => json_encode($qData['options']), // تخزين كـ JSON
                        'correct_answer' => $qData['correct_answer'],
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم إنشاء الاختبار بنجاح',
                    'exam_id' => $exam->id
                ], 201);
            });
        } catch (\Exception $e) {
            \Log::error('Exam creation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }

    // تحديث الاختبار
    public function examUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required|array|min:4|max:4',
            'questions.*.correct_answer' => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $exam = Exam::where('teacher_id', Auth::id())->findOrFail($id);

                $exam->update([
                    'title' => $request->title,
                    'scheduled_at' => $request->date,
                    'duration_minutes' => $request->duration,
                ]);

                // حذف الأسئلة القديمة
                $exam->questions()->delete();

                // إضافة الأسئلة الجديدة
                foreach ($request->questions as $qData) {
                    $exam->questions()->create([
                        'question_text' => $qData['text'],
                        'points' => $qData['points'],
                        'options' => json_encode($qData['options']),
                        'correct_answer' => $qData['correct_answer'],
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم تحديث الاختبار بنجاح',
                    'exam_id' => $exam->id
                ], 200);
            });
        } catch (\Exception $e) {
            \Log::error('Exam update error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء التحديث: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getExam($id)
    {
        try {
            $exam = Exam::with('questions')
                ->where('teacher_id', Auth::id())
                ->findOrFail($id);

            // تنسيق التاريخ ليتوافق مع input type="datetime-local"
            $scheduled_at = $exam->scheduled_at ? \Carbon\Carbon::parse($exam->scheduled_at)->format('Y-m-d\TH:i') : null;

            $examData = [
                'id' => $exam->id,
                'title' => $exam->title,
                'scheduled_at' => $scheduled_at, // استخدام التنسيق الصحيح
                'duration_minutes' => $exam->duration_minutes,
                'is_published' => $exam->is_published,
                'subject' => $exam->subject,
                'questions' => $exam->questions->map(function($question) {
                    $options = $question->options;
                    if (is_string($options)) {
                        $options = json_decode($options, true);
                    }
                    if (!is_array($options)) {
                        $options = ['', '', '', ''];
                    }

                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'points' => $question->points,
                        'options' => $options,
                        'correct_answer' => $question->correct_answer
                    ];
                })
            ];

            return response()->json([
                'status' => 'success',
                'exam' => $examData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب بيانات الاختبار: ' . $e->getMessage()
            ], 500);
        }
    }        // حذف الاختبار
    public function examDelete($id)
    {
        try {
            $exam = Exam::where('teacher_id', Auth::id())->findOrFail($id);
            $exam->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الاختبار بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء الحذف'
            ], 500);
        }
    }

    // تغيير حالة النشر
    public function togglePublish(Request $request, $id)
    {
        try {
            $exam = Exam::where('teacher_id', Auth::id())->findOrFail($id);
            $exam->is_published = $request->is_published;
            $exam->save();

            return response()->json([
                'status' => 'success',
                'message' => $exam->is_published ? 'تم نشر الاختبار' : 'تم إلغاء نشر الاختبار'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ'
            ], 500);
        }
    }

    // جلب بيانات اختبار محدد
    public function examResults($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);

        // التحقق من أن المعلم هو صاحب الاختبار
        if ($exam->teacher_id != Auth::id()) {
            abort(403);
        }

        // جلب نتائج الطلاب
        $results = Result::where('exam_id', $id)
            ->with('student')
            ->orderBy('score', 'desc')
            ->get();

        // حساب المجموع الكلي للدرجات
        $totalPossible = $exam->questions->sum('points');

        // تجهيز البيانات لكل طالب
        $resultsWithData = $results->map(function($result) use ($totalPossible) {
            $percentage = ($result->score / $totalPossible) * 100;
            return [
                'result' => $result,
                'percentage' => $percentage,

            ];
        });

        // إحصائيات عامة
        $statistics = [
            'total_students' => $results->count(),
            'completed_count' => $results->count(),
            'participation_rate' => 100,
            'average_score' => round($results->avg('score') ?? 0, 1),
            'highest_score' => round($results->max('score') ?? 0, 1),
            'lowest_score' => round($results->min('score') ?? 0, 1),
            'total_possible' => $totalPossible,
            'distribution' => $this->calculateDistribution($results, $totalPossible)
        ];

        return view('teacher.results', compact('exam', 'resultsWithData', 'statistics', 'totalPossible','results'));
    }

    // تحديث الملف الشخصي للمعلم
    public function updateProfile(Request $request, $id)
    {
        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'المعلم غير موجود'
                ], 404);
            }

            // التحقق من صحة البيانات
            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'subject_specialization' => 'required|string|max:255',
                'password' => 'nullable|min:6|confirmed',
            ]);

            // تحديث البيانات الأساسية
            $teacher->full_name = $request->full_name;
            $teacher->phone_number = $request->phone_number;
            $teacher->subject_specialization = $request->subject_specialization;

            // تحديث كلمة المرور إذا تم إدخالها
            if ($request->filled('password')) {
                $teacher->password = Hash::make($request->password);
            }


            $teacher->save();

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الملف الشخصي بنجاح',
                'teacher' => $teacher
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    // عرض الملف الشخصي
    public function showProfile(Request $request)
    {
        $teacher = auth()->guard('teacher')->user();

        return response()->json([
            'status' => 'success',
            'teacher' => $teacher
        ]);
    }

    // حساب توزيع الدرجات
    private function calculateDistribution($results, $totalPossible)
    {
        $distribution = [
            'excellent' => 0,
            'good' => 0,
            'average' => 0,
            'pass' => 0,
            'fail' => 0
        ];

        foreach ($results as $result) {
            $percentage = ($result->score / $totalPossible) * 100;

            if ($percentage >= 90) {
                $distribution['excellent']++;
            } elseif ($percentage >= 80) {
                $distribution['good']++;
            } elseif ($percentage >= 70) {
                $distribution['average']++;
            } elseif ($percentage >= 60) {
                $distribution['pass']++;
            } else {
                $distribution['fail']++;
            }
        }

        return $distribution;
    }
    public function studentResult ($examId, $studentId)
    {
        $exam = Exam::with('questions')->findOrFail($examId);

        $result = Result::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $student = Student::findOrFail($studentId);

        // تحويل الإجابات من JSON إلى array
        $answers = $result->answers;
        if (is_string($answers)) {
            $answers = json_decode($answers, true);
        }
        if (!is_array($answers)) {
            $answers = [];
        }

        $score = 0;
        $total = 0;

        // تحويل options لكل سؤال من JSON إلى array
        foreach ($exam->questions as $q) {
            $total += $q->points;

            // تحويل options إذا كانت string
            if (is_string($q->options)) {
                $q->options = json_decode($q->options, true);
            }

            if (isset($answers[$q->id]) && $answers[$q->id] == $q->correct_answer) {
                $score += $q->points;
            }
        }

        return view('teacher.student-result-details', compact('exam', 'result', 'student', 'score', 'total', 'answers'));
    }
    // تسجيل الخروج
    public function teacherLogout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
