@extends('admin.master')
@section('content')
    <!-- الإحصائيات - Bootstrap Cards -->
    <div class="row g-4 mb-5 fade-in">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100 shadow-sm border-0 border-bottom border-4 border-indigo card-hover">
                <div class="card-body text-center">
                    <i class="fas fa-users fs-1 text-indigo-600 mb-2" style="color:#4f46e5;"></i>
                    <p class="text-muted small mb-1">إجمالي الطلاب</p>
                    <h3 class="display-6 fw-bold text-indigo-600 mb-0">{{ $studentsCount ?? '0' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100 shadow-sm border-0 border-bottom border-4 border-purple card-hover">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-user fs-1 mb-2" style="color:#a855f7;"></i>
                    <p class="text-muted small mb-1">إجمالي المعلمين</p>
                    <h3 class="display-6 fw-bold mb-0" style="color:#a855f7">{{ $teachersCount ?? '0' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100 shadow-sm border-0 border-bottom border-4 border-success card-hover">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fs-1 text-success mb-2"></i>
                    <p class="text-muted small mb-1">اختبارات اليوم</p>
                    <h3 class="display-6 fw-bold text-success mb-0">{{$todayExamCount}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card h-100 shadow-sm border-0 border-bottom border-4 border-warning card-hover">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fs-1 text-warning mb-2"></i>
                    <p class="text-muted small mb-1">بلاغات تقنية</p>
                    <h3 class="display-6 fw-bold text-warning mb-0">--</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الطلاب -->
    <div class="card shadow-sm border-0 rounded-4 mb-5 fade-in">
        <div class="card-header bg-white border-0 pt-4 pb-2 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-graduation-cap text-indigo-600 me-2"></i>قاعدة بيانات الطلاب
                </h4>
                <p class="text-muted small mt-1">إدارة الطلاب - عرض وإضافة وتعديل بيانات الطلاب</p>
            </div>
            <button onclick="openStudentModal()" class="btn btn-indigo rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>إضافة طالب
            </button>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table id="students-table" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 60px;"><i class="fas fa-hashtag me-1"></i></th>
                        <th><i class="fas fa-user me-1"></i> اسم الطالب</th>
                        <th><i class="fas fa-id-card me-1"></i> رقم الجلوس</th>
                        <th><i class="fas fa-phone me-1"></i> الهاتف</th>
                        <th><i class="fas fa-school me-1"></i> الصف الدراسي</th>
                        <th><i class="fas fa-cog me-1"></i> الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- جدول المعلمين -->
    <div class="card shadow-sm border-0 rounded-4 mb-5 fade-in">
        <div class="card-header bg-white border-0 pt-4 pb-2 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-chalkboard-user text-indigo-600 me-2"></i>قاعدة بيانات المعلمين
                </h4>
                <p class="text-muted small mt-1">إدارة المعلمين - عرض وإضافة وتعديل بيانات المعلمين</p>
            </div>
            <button onclick="openTeacherModal()" class="btn btn-indigo rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>إضافة معلم
            </button>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table id="teachers-table" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-user-tie me-1"></i> اسم المعلم</th>
                        <th><i class="fas fa-book me-1"></i> اسم المادة</th>
                        <th><i class="fas fa-phone me-1"></i> رقم الهاتف</th>
                        <th><i class="fas fa-toggle-on me-1"></i> الحالة</th>
                        <th><i class="fas fa-cog me-1"></i> الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- جدول الاختبارات -->
    <div class="card shadow-sm border-0 rounded-4 mb-5 fade-in">
        <div class="card-header bg-white border-0 pt-4 pb-2 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-chalkboard-user text-indigo-600 me-2"></i>قاعدة بيانات الاختبارت
                </h4>
            </div>
            <button onclick="openExamModal()" class="btn btn-indigo rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>إضافة اختبار
            </button>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table id="exams-table" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-user-tie me-1"></i> اسم المعلم</th>
                        <th><i class="fas fa-book me-1"></i> عنوان الاختبار</th>
                        <th><i class="fas fa-phone me-1"></i>تاريخ الاختبار</th>
                        <th><i class="fas fa-toggle-on me-1"></i> مدة الاختبار </th>
                        <th><i class="fas fa-cog me-1"></i> الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- مراقبة الاختبارات -->
   {{-- <div class="card shadow-sm border-0 rounded-4 fade-in">
        <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-chart-line text-indigo-600 me-2"></i>مراقبة الاختبارات والنتائج
            </h4>
            <span class="badge bg-indigo-600 rounded-pill px-3 py-2">
                <i class="fas fa-sync-alt me-1"></i> تحديث مباشر
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>اسم المادة</th>
                        <th>المعلم المسؤول</th>
                        <th>إحصائية النجاح</th>
                        <th>عرض النتائج</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="fw-bold text-indigo-600">كيمياء - عضوي</td>
                        <td><i class="fas fa-user-check text-success me-1"></i> د. عصام جابر</td>
                        <td style="min-width: 180px;">
                            <div class="d-flex flex-column">
                                <div class="progress progress-sm rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                                <small class="text-muted mt-1">85% من الطلاب (34 من 40)</small>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="examSwitch" checked style="cursor: pointer;">
                                <label class="form-check-label" for="examSwitch"></label>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-indigo rounded-pill px-3" style="color:#4f46e5; border-color:#4f46e5;">
                                <i class="fas fa-chart-bar me-1"></i> تفاصيل
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>--}}

@endsection
