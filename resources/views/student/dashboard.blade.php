<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الاختبارات الثانوية المتكاملة - لوحة الطالب</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/bootstrap.rtl.min.css')}}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-icons.min.css')}}">

    <!-- Font Awesome (للتوافق مع الأيقونات الحالية) -->
    <link href="{{asset('assets/css/all.min.css')}}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <link href="{{ asset('assets/font/Tajawal.css') }}" rel="stylesheet">

    <style>

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* تخصيص الكروت */
        .custom-card {
            border: none;
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15) !important;
        }

        /* تخصيص الشريط العلوي */
        .navbar-custom {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* تخصيص الأزرار */
        .btn-exam {
            padding: 0.75rem 2rem;
            border-radius: 1rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-exam:hover {
            transform: translateY(-2px);
        }

        /* تخصيص بطاقة الملف الشخصي */
        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        /* تخصيص بطاقات الاختبارات */
        .exam-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .exam-card:hover {
            border-color: #c7d2fe;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* تخصيص الإحصائيات */
        .stat-item {
            background: #f9fafb;
            border-radius: 1rem;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Banner مخصص */
        .exam-banner {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border-radius: 1.5rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .banner-icon {
            position: absolute;
            left: -1rem;
            bottom: -1rem;
            font-size: 6rem;
            opacity: 0.1;
        }

        /* تأثيرات إضافية */
        .hover-lift {
            transition: transform 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<!-- شريط التنقل العلوي -->
<nav class="navbar navbar-custom shadow-lg mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center w-100 py-2">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-university fs-2 text-white"></i>
                <span class="text-white fw-bold fs-5">بوابة التميز التعليمية</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small border-start border-light ps-3">
                    <i class="fas fa-user me-2"></i>مرحباً بك في النظام
                </span>

                <form method="POST" action="{{ route('student.logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> خروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- محتوى الصفحة الرئيسية -->
<section class="container fade-in py-4">
    <div class="row g-4">

        <!-- العمود الأيمن: الملف الشخصي والإحصائيات -->
        <div class="col-lg-4">
            <div class="card profile-card custom-card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <!-- الصورة الرمزية -->
                    <div class="avatar-circle rounded-circle shadow-lg mx-auto mb-3">
                        <i class="fas fa-user-graduate text-white fs-1"></i>
                    </div>

                    <h3 class="fw-bold text-gray-800 mb-2">{{$student->full_name}}</h3>
                    <p class="text-muted small mb-4">
                        <i class="fas fa-id-card me-1"></i>رقم الجلوس: {{$student->seating_number }}
                    </p>

                    <div class="mt-4">
                        <div class="stat-item d-flex justify-content-between align-items-center">
                            <span class="text-secondary">
                                <i class="fas fa-check-circle text-success me-2"></i>الاختبارات المنجزة
                            </span>
                            <span class="fw-bold text-success fs-5">{{$student->results()->count()}}</span>
                        </div>
                        {{--<div class="stat-item d-flex justify-content-between align-items-center">
                            <span class="text-secondary">
                                <i class="fas fa-chart-line text-primary me-2"></i>المعدل الحالي
                            </span>
                            <span class="fw-bold text-primary fs-5">{{$student->results()->avg('score') ?? 0}}%</span>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- العمود الأيسر: الاختبارات -->
        <div class="col-lg-8">
            <!-- Banner الترحيبي -->
            <div class="exam-banner shadow-sm mb-4">
                <h3 class="text-white fw-bold mb-2">
                    <i class="fas fa-calendar-alt me-2"></i>الاختبارت الخاصة بك
                </h3>
                <p class="text-white-50 mb-0 small">
                    <i class="fas fa-info-circle me-1"></i>يرجى الالتزام بالوقت المحدد لكل مادة
                </p>
                <i class="fas fa-clock banner-icon"></i>
            </div>

            <!-- قائمة الاختبارات -->
            @foreach($exam as $exams)
                @php
                    $result = \App\Models\Result::where('exam_id', $exams->id)
                                ->where('student_id', auth('student')->id())
                                ->first();
                @endphp

                <div class="exam-card hover-lift">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold text-gray-800 mb-2">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                اختبار {{$exams->title}}
                            </h5>
                            <p class="text-muted small mb-0">
                                <i class="far fa-clock me-1"></i>
                                مدة الاختبار: {{$exams->duration_minutes}} دقيقة
                                <span class="mx-2">•</span>
                                <i class="fas fa-question-circle me-1"></i>
                                تاريخ الاختبار
                                {{$exams->scheduled_at}}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-question-circle me-1"></i>
                                منشئ الاختبار أ:
                                {{ $exams->teacher->full_name }}
                            </p>
                        </div>

                        @if($result)
                            @if($exams->is_published == 1)
                                <!-- عرض النتيجة -->
                                <a href="{{ route('student.exam.result', $exams->id) }}"
                                   class="btn btn-success btn-exam">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    عرض النتيجة
                                </a>
                            @else
                                <!-- عرض النتيجة -->
                                <a href="#"
                                   class="btn" style="background-color: #373b3e ;color: white">
                                    <i class="fas fa-chart-bar me-2"></i>
                                        بانتظار السماح لعرض النتيجة
                                </a>
                            @endif

                        @else
                            <!-- بدء الاختبار -->
                            @if($exams->scheduled_at == today())
                                <a href="{{ route('student.exam.start', $exams->id) }}"
                                   class="btn btn-primary btn-exam"
                                   style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                                    <i class="fas fa-play me-2"></i>
                                    ابدأ الآن
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- رسالة إذا لم تكن هناك اختبارات -->
            @if($exam->isEmpty())
                <div class="exam-card text-center">
                    <i class="fas fa-inbox text-muted fs-1 mb-3 d-block"></i>
                    <h5 class="text-muted">لا توجد اختبارات متاحة حالياً</h5>
                    <p class="text-muted small">يرجى التحقق لاحقاً من توفر اختبارات جديدة</p>
                </div>
            @endif
        </div>

    </div>
</section>

<!-- Scripts -->
<script defer src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<script defer src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script defer src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<script>
    // إضافة تأثيرات إضافية
    $(document).ready(function() {
        // تأثير التلاشي عند تحميل الصفحة
        $('.fade-in').css('opacity', 0).animate({ opacity: 1 }, 500);

        // تأثير hover إضافي للكروت
        $('.exam-card').hover(
            function() {
                $(this).css('box-shadow', '0 20px 25px -5px rgba(0, 0, 0, 0.1)');
            },
            function() {
                $(this).css('box-shadow', 'none');
            }
        );

        // إضافة أداة تعريف للعناصر
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

</body>
</html>
