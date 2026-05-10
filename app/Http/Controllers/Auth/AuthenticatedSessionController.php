<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }
    public function createAdminLogin()
    {
        return view('admin.login');

    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAdminLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');

        }

        // رسالة خطأ أكثر وضوحاً
        return back()->withErrors([
            'email' => '❌ البريد الإلكتروني أو كلمة المرور غير صحيحة. يرجى المحاولة مرة أخرى.',
        ])->withInput($request->only('email'));
    }
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        if ($request->role === 'student') {
            // التحقق من وجود رقم الجلوس
            if (empty($request->identifier)) {
                return back()->withErrors([
                    'identifier' => '⚠️ يرجى إدخال رقم الجلوس',
                ])->withInput();
            }

            $student = \App\Models\Student::where('seating_number', $request->identifier)->first();

            if ($student) {
                // التحقق من حالة الطالب (إذا كان نشطاً)
                if ($student->status === 'inactive') {
                    return back()->withErrors([
                        'identifier' => '⛔ حساب الطالب غير نشط. يرجى التواصل مع الإدارة.',
                    ])->withInput();
                }

                Auth::guard('student')->login($student);
                $request->session()->regenerate();
                return redirect()->route('student.dashboard');
            }

            return back()->withErrors([
                'identifier' => '❌ رقم الجلوس غير صحيح. يرجى التأكد من الرقم والمحاولة مرة أخرى.',
            ])->withInput();
        }

        if ($request->role === 'teacher') {
            // التحقق من وجود البريد/الهاتف وكلمة المرور
            if (empty($request->identifier)) {
                return back()->withErrors([
                    'identifier' => '⚠️ يرجى إدخال رقم الهاتف',
                ])->withInput();
            }

            if (empty($request->password)) {
                return back()->withErrors([
                    'password' => '⚠️ يرجى إدخال كلمة المرور',
                ])->withInput();
            }

            $credentials = [
                'phone_number' => $request->identifier,
                'password' => $request->password
            ];

            if (Auth::guard('teacher')->attempt($credentials)) {
                $teacher = Auth::guard('teacher')->user();

                // التحقق من حالة المعلم
                if ($teacher->status === 'inactive') {
                    Auth::guard('teacher')->logout();
                    return back()->withErrors([
                        'identifier' => '⛔ حساب المعلم غير نشط. يرجى التواصل مع الإدارة.',
                    ])->withInput();
                }

                $request->session()->regenerate();
                return redirect()->intended('/teacher/dashboard');
            }

            return back()->withErrors([
                'identifier' => '❌ بيانات الدخول غير صحيحة. يرجى التأكد من رقم الهاتف وكلمة المرور.',
            ])->withInput();
        }

        return back()->withErrors([
            'role' => '⚠️ يرجى اختيار نوع الحساب المناسب',
        ])->withInput();
    }
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
