<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $role = $request->role;

        // 🔹 Admin
        if ($role === 'admin') {
            if (Auth::guard('admin')->attempt([
                'email' => $request->identifier,
                'password' => $request->password
            ])) {
                return response()->json([
                    'status' => 'success',
                    'redirect' => route('admin.dashboard')
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'بيانات الإدارة غير صحيحة'
            ]);
        }

        // 🔹 Teacher
        if ($role === 'teacher') {

            if (Auth::guard('teacher')->attempt([
                'email' => $request->identifier,
                'password' => $request->password
            ])) {
                return response()->json([
                    'status' => 'success',
                    'redirect' => route('teacher.dashboard')
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'بيانات المعلم غير صحيحة'
            ]);
        }

        // 🔹 Student (بدون password)
        if ($role === 'student') {

            $student = \App\Models\Student::where('seating_number', $request->identifier)->first();

            if ($student) {
                Auth::guard('student')->login($student);

                return response()->json([
                    'status' => 'success',
                    'redirect' => route('student.dashboard')
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'رقم الجلوس غير صحيح'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'نوع المستخدم غير مدعوم'
        ]);
    }
    public function destroy(Request $request)
    {
        Auth::logout();

        // حذف السيشن
        $request->session()->invalidate();

        // إنشاء سيشن جديد
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
