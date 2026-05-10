@extends('teacher.master')

@section('content')
    <div class="container">
        <div class="row">
            @forelse($exams as $exam)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="exam-card shadow-sm p-0 overflow-hidden transition-all hover-shadow-lg">
                        <!-- شريط جانبي ملون -->
                        <div class="position-absolute top-0 start-0 bottom-0" style="width: 5px; background: {{ $exam->is_published ? '#10b981' : '#f59e0b' }};"></div>

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $exam->title }}</h5>
                                    <div class="d-flex gap-2 mt-1">
                        <span class="badge {{ $exam->is_published ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-dark px-2 py-1">
                            <i class="fas {{ $exam->is_published ? 'fa-eye' : 'fa-eye-slash' }} me-1"></i>
                            {{ $exam->is_published ? 'منشور' : 'مسودة' }}
                        </span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($exam->created_at)->format('Y-m-d') }}
                        </span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" onclick="openEditModal({{ $exam->id }})">
                                                <i class="fas fa-edit text-primary me-2"></i> تعديل
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" onclick="deleteExam({{ $exam->id }})">
                                                <i class="fas fa-trash text-danger me-2"></i> حذف
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" onclick="togglePublish({{ $exam->id }}, {{ !$exam->is_published }})">
                                                <i class="fas {{ $exam->is_published ? 'fa-eye-slash' : 'fa-eye' }} text-info me-2"></i>
                                                {{ $exam->is_published ? 'إخفاء' : 'نشر' }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- إحصائيات على شكل أقراص -->
                            <div class="row g-3 mb-4">
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-question-circle text-primary fs-4"></i>
                                        </div>
                                        <div class="fw-bold">{{ $exam->questions_count }}</div>
                                        <small class="text-muted">سؤال</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-clock text-success fs-4"></i>
                                        </div>
                                        <div class="fw-bold">{{ $exam->duration_minutes }}</div>
                                        <small class="text-muted">دقيقة</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="rounded-circle bg-info bg-opacity-10 p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                            <i class="fas fa-users text-info fs-4"></i>
                                        </div>
                                        <div class="fw-bold">{{ \App\Models\Result::where('exam_id', $exam->id)->count() }}</div>
                                        <small class="text-muted">مختبر</small>
                                    </div>
                                </div>
                            </div>

                            <!-- زر عرض النتائج المحسن مع تأثير -->
                            <a href="{{ route('teacher.exam.results', $exam->id) }}"
                               class="btn btn-gradient w-100 rounded-pill py-2 position-relative overflow-hidden">
                <span class="position-relative">
                    <i class="fas fa-chart-line me-2"></i>
                    عرض النتائج والإحصائيات
                    <i class="fas fa-arrow-left ms-2"></i>
                </span>
                                @php
                                    $resultsCount = \App\Models\Result::where('exam_id', $exam->id)->count();
                                @endphp
                                @if($resultsCount > 0)
                                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                        {{ $resultsCount }}
                        <span class="visually-hidden">نتائج جديدة</span>
                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        لا توجد اختبارات حالياً. قم بإضافة اختبار جديد!
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
