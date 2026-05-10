<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function dashboard(Request $request){
        $studentsCount = Student::count();
        $teachersCount = Teacher::count();
// جلب عدد الأسئلة التي تاريخها اليوم
        $todayExamCount = Exam::whereDate('created_at', today())->count();

        $students = Student::all();
        return view('admin.dashboard',compact('studentsCount','teachersCount','students','todayExamCount' ));
    }
    public function addStudent(Request $request)
    {

        $request->validate([
            'full_name' => 'required',
            'seating_number' => 'required|unique:students',
            'phone_number' => 'nullable',
            'class_room' => 'nullable',
        ]);
        $student = Student::create([
            'full_name' => $request->full_name,
            'seating_number' => $request->seating_number,
            'phone_number' => $request->phone_number,
            'class_room' => $request->class_room,
            'student_branch' => $request->student_branch,
        ]);

        return response()->json([
            'message' => 'تم إضافة الطالب بنجاح',
            'student' => $student
        ]);
    }

    public function showStudent($id)
    {
        $student = Student::find($id);
        if ($student) {
            return response()->json([
                'success' => true,
                'student' => $student
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'الطالب غير موجود'
        ]);
    }

    public function updateStudent(Request $request)
    {
        $student = Student::find($request->id);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'الطالب غير موجود'
            ]);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'seating_number' => 'required|string|unique:students,seating_number,' . $request->id,
            'phone_number' => 'nullable|string|max:20',
            'class_room' => 'nullable|string|max:100'
        ]);

        $student->update($request->all());

        return response()->json([
            'success' => true,
            'student' => $student,
            'message' => 'تم تحديث بيانات الطالب بنجاح'
        ]);
    }

    public function addTeacher(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'subject_specialization' => 'required',
            'phone_number' => 'required|unique:teachers',
            'password' => 'required',
        ]);
        $teacher = Teacher::create([
            'full_name' => $request->full_name,
            'subject_specialization' => $request->subject_specialization,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),

        ]);

        return response()->json([
            'message' => 'تم إضافة المعلم بنجاح',
            'teacher' => $teacher
        ]);
    }

    public function showTeacher($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            return response()->json(['success' => true, 'teacher' => $teacher]);
        }
        return response()->json(['success' => false, 'message' => 'المعلم غير موجود']);

    }
    public function updateTeacher(Request $request)
    {
        try {
            $teacher = Teacher::find($request->id);
            if (!$teacher) {
                return response()->json(['success' => false, 'message' => 'المعلم غير موجود']);
            }

            $teacher->full_name = $request->full_name;
            $teacher->subject_specialization = $request->subject_specialization;
            $teacher->phone_number = $request->phone_number;

            if ($request->password) {
                $teacher->password = Hash::make($request->password);
            }

            $teacher->save();

            return response()->json(['success' => true, 'message' => 'تم تحديث بيانات المعلم بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function destroyTeacher(Request $request)
    {
        try {
            $teacher = Teacher::find($request->id);
            if ($teacher) {
                $teacher->delete();
                return response()->json(['success' => true, 'message' => 'تم حذف المعلم بنجاح']);
            }
            return response()->json(['success' => false, 'message' => 'المعلم غير موجود']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }
    public function destroyStudent(Request $request)
    {
        try {
            $student = Student::find($request->id);
            if ($student) {
                $student->delete();
                return response()->json(['success' => true, 'message' => 'تم حذف الطالب بنجاح']);
            }
            return response()->json(['success' => false, 'message' => 'الطالب غير موجود']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }
    public function dataStudent(Request $request)
    {
        $query = Student::query();

        // البحث
        if ($request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('seating_number', 'like', "%$search%")
                    ->orWhere('class_room', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%");
            });
        }

        $total = $query->count();

        // الترتيب
        $columns = ['full_name', 'seating_number', 'phone_number', 'class_room'];
        $orderColumnIndex = $request->order[0]['column'] ?? 0;
        // نبدأ من 1 لأن العمود 0 هو عمود الترقيم
        $orderColumn = $columns[$orderColumnIndex - 1] ?? 'full_name';
        $orderDir = $request->order[0]['dir'] ?? 'asc';

        $students = $query->orderBy($orderColumn, $orderDir)
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = [];
        $startNumber = $request->start + 1; // بدء الترقيم من رقم الصفحة الحالية

        foreach ($students as $index => $student) {
            $data[] = [
                'serial' => $startNumber + $index, // الترقيم التسلسلي
                'full_name' => $student->full_name,
                'seating_number' => $student->seating_number,
                'phone_number' => $student->phone_number ?? '-',
                'class_room' => '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">' . e($student->class_room) . '</span>',
                'action' => '
                <div class="action-buttons">
                    <button class="btn btn-sm btn-outline-warning rounded-pill px-2" onclick="editStudent(' . $student->id . ')" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-2" onclick="deleteStudent(' . $student->id . ', \'' . addslashes($student->full_name) . '\')" title="حذف">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            '
            ];
        }

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => Student::count(),
            "recordsFiltered" => $total,
            "data" => $data
        ]);
    }
    public function dataTeacher(Request $request)
    {
        $query = Teacher::query();

        // البحث - تحسين البحث ليشمل جميع الحقول المطلوبة
        if ($request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('subject_specialization', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%");
            });
        }

        $total = $query->count();

        // الترتيب - تحديد الأعمدة بشكل صحيح مع مراعاة عدم وجود عمود الترقيم
        $columns = ['full_name', 'subject_specialization', 'phone_number', 'status', 'action'];
        $orderColumnIndex = $request->order[0]['column'] ?? 0;
        // نبدأ من 0 لأن الجدول لا يحتوي على عمود ترقيم منفصل
        $orderColumn = $columns[$orderColumnIndex] ?? 'full_name';
        $orderDir = $request->order[0]['dir'] ?? 'asc';

        $teachers = $query->orderBy($orderColumn, $orderDir)
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = [];

        foreach ($teachers as $teacher) {
            $data[] = [
                'full_name' => $teacher->full_name,
                'subject_specialization' => $teacher->subject_specialization,
                'phone_number' => $teacher->phone_number ?? '-',
                'status' => '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> نشط</span>',
                'action' => '
            <div class="action-buttons">
                <button class="btn btn-sm btn-outline-warning rounded-pill px-2 mx-1" onclick="editTeacher(' . $teacher->id . ')" title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger rounded-pill px-2 mx-1" onclick="deleteTeacher(' . $teacher->id . ', \'' . addslashes($teacher->full_name) . '\')" title="حذف">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        '
            ];
        }

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => Teacher::count(), // تصحيح: كان Student::count()
            "recordsFiltered" => $total,
            "data" => $data
        ]);
    }


    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }
}
