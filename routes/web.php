<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    Route::post('/addStudent', [AdminController::class, 'addStudent'])->name('students.store');
    Route::get('/showStudent/{id}', [AdminController::class, 'showStudent'])->name('students.show');
    Route::post('/updateStudent', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::get('/destroyStudent', [AdminController::class, 'destroyStudent'])->name('students.destroy');

    Route::post('/addTeacher', [AdminController::class, 'addTeacher'])->name('teacher.store');
    Route::get('/admin/teacher/show/{id}', [AdminController::class, 'showTeacher'])->name('teacher.show');
    Route::post('/admin/teacher.update', [AdminController::class, 'updateTeacher'])->name('teacher.update');
    Route::post('/admin/destroyTeacher', [AdminController::class, 'destroyTeacher'])->name('teacher.delete');
    Route::post('/admin/students/delete', [AdminController::class, 'destroyStudent'])->name('students.delete');

    Route::get('/students/data', [AdminController::class, 'dataStudent'])->name('students.data');
    Route::get('/teacher/data', [AdminController::class, 'dataTeacher'])->name('teacher.data');
    Route::get('/exams/data', [AdminController::class, 'dataExams'])->name('exams.data');
    Route::post('/{id}/toggle-publish', [AdminController::class, 'togglePublish'])->name('exams.toggle-publish');
    Route::get('/admin/exam-results/get/{id}', [TeacherController::class, 'examResults'])
        ->name('admin.exams.results');
    Route::get('/admin/exam/{exam}/student/{student}/result',
        [TeacherController::class, 'studentResult'])
        ->name('admin.exam.student.result');

    Route::post('/admin/logout', [AdminController::class, 'adminLogout'])
        ->name('admin.logout');

});
// في ملف routes/web.php

Route::middleware('auth:teacher')->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])
        ->name('teacher.dashboard');

    // مسارات الاختبارات
    Route::post('/teacher/exams/store', [TeacherController::class, 'examsStore'])
        ->name('teacher.exams.store');
    Route::put('/teacher/exams/update/{id}', [TeacherController::class, 'examUpdate'])
        ->name('teacher.exams.update');
    Route::delete('/teacher/exams/delete/{id}', [TeacherController::class, 'examDelete'])
        ->name('teacher.exams.delete');
    Route::post('/teacher/exams/toggle-publish/{id}', [TeacherController::class, 'togglePublish'])
        ->name('teacher.exams.toggle-publish');
    Route::get('/teacher/exams/get/{id}', [TeacherController::class, 'getExam'])
        ->name('teacher.exams.get');

    // مسار رفع الصور
    Route::post('/teacher/upload-image', [TeacherController::class, 'upload'])
        ->name('teacher.upload');

    // مسارات النتائج
    Route::get('/teacher/exams/{id}/results', [TeacherController::class, 'examResults'])
        ->name('teacher.exam.results');

    Route::get('/teacher/exam/{exam}/student/{student}/result',
        [TeacherController::class, 'studentResult'])
        ->name('teacher.exam.student.result');

    Route::put('/teacher/profile/update/{id}', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');
    Route::get('/teacher/profile/show', [TeacherController::class, 'showProfile'])->name('teacher.profile.show');
    // تسجيل الخروج
    Route::post('/teacher/logout', [TeacherController::class, 'teacherLogout'])
        ->name('teacher.logout');
});
Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');
    Route::get('/student/exam/{id}', [StudentController::class, 'start'])
        ->name('student.exam.start');
    Route::post('/student/exam/submit', [StudentController::class, 'submit'])
        ->name('student.exam.submit');
    Route::get('/student/exam/{exam}/result', [StudentController::class, 'result'])
        ->name('student.exam.result');
    Route::post('/student/logout', [StudentController::class, 'studentLogout'])
        ->name('student.logout');

});

require __DIR__.'/auth.php';
