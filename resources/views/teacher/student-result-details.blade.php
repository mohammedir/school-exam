<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة الاختبار - {{ $exam->title }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-icons.css')}}">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Tajawal', 'Segoe UI', system-ui, sans-serif;
        }

        /* تخصيص إضافي لتحسين المظهر */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }

        .option-correct {
            background-color: #d1fae5;
            border-right: 4px solid #10b981;
        }

        .option-wrong {
            background-color: #fee2e2;
            border-right: 4px solid #ef4444;
        }

        .option-neutral {
            background-color: #f9fafb;
            border-right: 4px solid #e5e7eb;
        }

        .badge-correct {
            background-color: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-wrong {
            background-color: #ef4444;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .option-item {
                font-size: 0.9rem;
                padding: 0.75rem !important;
            }
        }

        .question-text {
            line-height: 1.6;
        }

        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
</head>

<body class="bg-light p-3 p-md-4">

<div class="container" style="max-width: 900px;">

    <!-- بطاقة العنوان والنتيجة -->
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h2 class="card-title h3 fw-bold mb-2 text-dark">
                        {{ $exam->title }}
                    </h2>
                    <p class="text-muted mb-0">نتيجة الاختبار النهائية</p>
                </div>
                <div class="bg-success bg-opacity-10 px-4 py-2 rounded-3 text-center">
                    <span class="text-muted small d-block">النتيجة</span>
                    <span class="text-success fw-bold fs-2">
                        {{ $score }} / {{ $total }}
                    </span>
                </div>
            </div>

            <!-- نسبة النجاح -->
            @php
                $percentage = ($score / $total) * 100;
                $gradeClass = $percentage >= 60 ? 'success' : ($percentage >= 40 ? 'warning' : 'danger');
            @endphp
            <div class="mt-3 pt-2">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted">نسبة الإنجاز</small>
                    <small class="text-{{ $gradeClass }} fw-bold">{{ round($percentage, 1) }}%</small>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-{{ $gradeClass }}"
                         role="progressbar"
                         style="width: {{ $percentage }}%;"
                         aria-valuenow="{{ $percentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الأسئلة -->
    @foreach($exam->questions as $index => $q)
        @php
            // تحويل options من JSON string إلى array إذا لزم الأمر
            $options = $q->options;
            if (is_string($options)) {
                $options = json_decode($options, true);
            }
            if (!is_array($options)) {
                $options = [];
            }

            $studentAnswer = $answers[$q->id] ?? null;
            $isCorrect = $studentAnswer == $q->correct_answer;

            // تنظيف النص من HTML إذا أردت، أو تركه للعرض
            $questionText = $q->question_text;
        @endphp

        <div class="card shadow-sm mb-4 border-0 rounded-3 card-hover">
            <div class="card-body p-4">

                <!-- رأس السؤال -->
                <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
                    <h3 class="h5 fw-bold mb-0 text-dark">
                        <span class="badge bg-secondary bg-opacity-25 text-dark me-2 px-3 py-2 rounded-pill">
                            {{ $index + 1 }}
                        </span>
                        <span class="question-text">{!! $questionText !!}</span>
                    </h3>

                    <!-- حالة السؤال -->
                    <div>
                        @if($isCorrect)
                            <span class="badge-correct">
                                <i class="bi bi-check-circle"></i> إجابة صحيحة
                            </span>
                        @else
                            <span class="badge-wrong">
                                <i class="bi bi-x-circle"></i> إجابة خاطئة
                            </span>
                        @endif
                    </div>
                </div>

                <!-- الخيارات -->
                <div class="mt-3">
                    @foreach($options as $opt)
                        @php
                            $isCorrectOption = ($opt == $q->correct_answer);
                            $isStudentWrongOption = ($opt == $studentAnswer && !$isCorrect);
                            $optionClass = '';

                            if ($isCorrectOption) {
                                $optionClass = 'option-correct';
                            } elseif ($isStudentWrongOption) {
                                $optionClass = 'option-wrong';
                            } else {
                                $optionClass = 'option-neutral';
                            }
                        @endphp

                        <div class="option-item {{ $optionClass }} p-3 rounded-2 mb-2 d-flex justify-content-between align-items-center">
                            <span class="flex-grow-1">{{ $opt }}</span>
                            <div class="d-flex gap-2">
                                @if($isCorrectOption)
                                    <span class="text-success fw-bold">
                                        <i class="bi bi-check-circle-fill"></i> الإجابة الصحيحة
                                    </span>
                                @endif

                                @if($isStudentWrongOption)
                                    <span class="text-danger fw-bold">
                                        <i class="bi bi-x-circle-fill"></i> إجابتك
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- شرح إضافي (اختياري) -->
                @if(isset($q->explanation) && $q->explanation)
                    <div class="mt-3 pt-2 border-top">
                        <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-3 mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>شرح:</strong> {{ $q->explanation }}
                        </div>
                    </div>
                @endif

                <!-- عرض درجة السؤال -->
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="bi bi-star-fill text-warning"></i>
                            درجة السؤال: {{ $q->points }} نقاط
                        </small>
                        @if(!$isCorrect && $studentAnswer)
                            <small class="text-danger">
                                <i class="bi bi-x-circle"></i>
                                إجابتك: {{ $studentAnswer }}
                            </small>
                        @elseif($isCorrect)
                            <small class="text-success">
                                <i class="bi bi-check-circle"></i>
                                حصلت على {{ $q->points }} نقاط
                            </small>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endforeach

    <!-- أزرار التنقل -->
    <div class="d-flex justify-content-between gap-3 mt-4">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary rounded-3 px-4">
            <i class="bi bi-arrow-right"></i> العودة للرئيسية
        </a>

        @if(isset($nextExam))
            <a href="{{ route('exams.show', $nextExam->id) }}" class="btn btn-primary rounded-3 px-4">
                الاختبار التالي <i class="bi bi-arrow-left"></i>
            </a>
        @endif

        <button onclick="window.print()" class="btn btn-outline-primary rounded-3 px-4">
            <i class="bi bi-printer"></i> طباعة النتيجة
        </button>
    </div>

    <!-- إحصائيات إضافية -->
    <div class="card shadow-sm mt-4 border-0 rounded-3 bg-gradient">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold mb-3">
                <i class="bi bi-graph-up"></i> ملخص الأداء
            </h5>
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3">
                        <div class="text-muted small">إجابات صحيحة</div>
                        <div class="fs-3 fw-bold text-success">{{ $score }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3">
                        <div class="text-muted small">إجابات خاطئة</div>
                        <div class="fs-3 fw-bold text-danger">{{ $total - $score }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3">
                        <div class="text-muted small">نسبة النجاح</div>
                        <div class="fs-3 fw-bold text-{{ $gradeClass }}">{{ round($percentage, 1) }}%</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3">
                        <div class="text-muted small">عدد الأسئلة</div>
                        <div class="fs-3 fw-bold text-primary">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- رسالة تشجيعية -->
    <div class="card shadow-sm mt-4 border-0 rounded-3 bg-{{ $gradeClass }} bg-opacity-10">
        <div class="card-body p-4 text-center">
            @if($percentage >= 80)
                <i class="bi bi-trophy-fill fs-1 text-warning"></i>
                <h5 class="mt-2 fw-bold text-dark">ممتاز! أداء رائع 🎉</h5>
                <p class="text-muted mb-0">نتيجة ممتازة، استمر بهذا المستوى المتميز</p>
            @elseif($percentage >= 60)
                <i class="bi bi-star-fill fs-1 text-primary"></i>
                <h5 class="mt-2 fw-bold text-dark">جيد جداً! 👍</h5>
                <p class="text-muted mb-0">نتيجة جيدة، يمكنك تحسين أدائك في المرة القادمة</p>
            @elseif($percentage >= 40)
                <i class="bi bi-book-fill fs-1 text-info"></i>
                <h5 class="mt-2 fw-bold text-dark">تحتاج إلى مذاكرة أكثر 📚</h5>
                <p class="text-muted mb-0">لا تيأس، راجع المواد وحاول مرة أخرى</p>
            @else
                <i class="bi bi-emoji-frown-fill fs-1 text-danger"></i>
                <h5 class="mt-2 fw-bold text-dark">تحتاج إلى بذل المزيد من الجهد 💪</h5>
                <p class="text-muted mb-0">لا تستسلم، النجاح يأتي بالمثابرة والتدريب المستمر</p>
            @endif
        </div>
    </div>

</div>

<!-- Bootstrap JS Bundle (مع Popper) -->
<script src="{{asset('assets/css/bootstrap.bundle.min.js')}}"></script>

<!-- تأثيرات إضافية -->
<script>
    // إضافة تأثير عند تمرير الماوس على البطاقات
    document.querySelectorAll('.card-hover').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });

    // عرض رسالة في console
    document.addEventListener('DOMContentLoaded', () => {
        const percentage = {{ $percentage }};
        if (percentage >= 80) {
            console.log('🎉 ممتاز! أداء رائع');
        } else if (percentage >= 60) {
            console.log('👍 جيد جداً، يمكنك تحسين أدائك');
        } else if (percentage >= 40) {
            console.log('📚 تحتاج إلى مذاكرة أكثر');
        } else {
            console.log('💪 لا تيأس، حاول مرة أخرى');
        }
    });

    // إضافة وظيفة للطباعة بشكل أفضل
    window.onbeforeprint = function() {
        document.querySelectorAll('.card-hover').forEach(card => {
            card.style.transform = 'none';
        });
    };
</script>

</body>
</html>
