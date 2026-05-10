<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف العلامات - {{ $exam->title }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/bootstrap.rtl.min.css')}}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-icons.min.css')}}">

    <!-- Font Awesome -->
    <link href="{{asset('assets/css/all.min.css')}}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 1.5rem;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* بطاقة العنوان */
        .title-card {
            background: white;
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .title-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
        }

        /* بطاقة الإحصائيات */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }

        /* الجدول */
        .table-container {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dataTables_wrapper {
            padding: 1rem;
        }

        table.dataTable {
            border-collapse: collapse !important;
        }

        table.dataTable thead th {
            background: #f8f9fa;
            color: #4a5568;
            font-weight: bold;
            font-size: 0.875rem;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        table.dataTable tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        table.dataTable tbody tr:hover {
            background-color: #f7fafc;
            transition: all 0.3s ease;
        }

        /* تخصيص الأزرار */
        .btn-custom {
            border-radius: 0.75rem;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }

        /* شارة الدرجة */
        .score-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-weight: bold;
            font-size: 0.875rem;
            display: inline-block;
        }

        /* تصنيف الدرجات */
        .score-excellent {
            color: #10b981;
            font-weight: bold;
        }

        .score-good {
            color: #3b82f6;
            font-weight: bold;
        }

        .score-average {
            color: #f59e0b;
            font-weight: bold;
        }

        .score-poor {
            color: #ef4444;
            font-weight: bold;
        }

        /* أزرار التصدير المخصصة */
        .export-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* تنسيقات الطباعة */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .table-container {
                box-shadow: none;
            }
        }

        /* تحسينات Bootstrap إضافية */
        .text-gray-800 {
            color: #2d3748;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .rounded-3 {
            border-radius: 1rem;
        }

        .rounded-pill-custom {
            border-radius: 50px;
        }
    </style>
</head>
<body class="fade-in">

<div class="container-lg">

    <!-- العناوين والإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card title-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h2 class="fw-bold text-gray-800 mb-2">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                كشف علامات اختبار: {{ $exam->title }}
                            </h2>
                            <p class="text-secondary mb-0">
                                <i class="fas fa-calendar-alt me-1"></i>
                                تاريخ الاختبار: {{ \Carbon\Carbon::parse($exam->date)->format('Y-m-d') }} |
                                <i class="fas fa-clock me-1"></i>
                                المدة: {{ $exam->duration_minutes }} دقيقة
                            </p>
                        </div>
                        <div class="export-buttons no-print">
                            <button onclick="exportToExcel()" class="btn btn-success btn-custom">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير إلى Excel
                            </button>
                            <button onclick="window.print()" class="btn btn-info btn-custom text-white">
                                <i class="fas fa-print me-2"></i>
                                طباعة
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-custom">
                                <i class="fas fa-arrow-right me-2"></i>
                                رجوع
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقات الإحصائيات -->
    @php
        $totalStudents = $results->count();
        $totalScore = $results->sum('score');
        $averageScore = $totalStudents > 0 ? round($totalScore / $totalStudents, 2) : 0;
        $passedStudents = $results->filter(function($r) use ($exam) {
            $passingScore = $exam->passing_score ?? 50;
            return $r->score >= $passingScore;
        })->count();
        $passRate = $totalStudents > 0 ? round(($passedStudents / $totalStudents) * 100, 2) : 0;
        $highestScore = $results->max('score');
        $lowestScore = $results->min('score');
    @endphp

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="stat-icon bg-primary bg-opacity-10">
                        <i class="fas fa-users text-primary fs-3"></i>
                    </div>
                    <h6 class="text-secondary mb-1">عدد الطلاب</h6>
                    <h3 class="fw-bold mb-0 text-primary">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="stat-icon bg-success bg-opacity-10">
                        <i class="fas fa-chart-line text-success fs-3"></i>
                    </div>
                    <h6 class="text-secondary mb-1">المعدل العام</h6>
                    <h3 class="fw-bold mb-0 text-success">{{ $averageScore }}%</h3>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="stat-icon bg-warning bg-opacity-10">
                        <i class="fas fa-trophy text-warning fs-3"></i>
                    </div>
                    <h6 class="text-secondary mb-1">أعلى درجة</h6>
                    <h3 class="fw-bold mb-0 text-warning">{{ $highestScore ?? 0 }}%</h3>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="stat-icon bg-info bg-opacity-10">
                        <i class="fas fa-check-circle text-info fs-3"></i>
                    </div>
                    <h6 class="text-secondary mb-1">نسبة النجاح</h6>
                    <h3 class="fw-bold mb-0 text-info">{{ $passRate }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- الجدول -->
    <div class="table-container shadow-sm">
        <div class="p-3 bg-light border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-table me-2"></i>
                قائمة نتائج الطلاب
            </h6>
        </div>

        <table id="resultsTable" class="table table-hover mb-0">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-end">الطالب</th>
                <th class="text-center">رقم الجلوس</th>
                <th class="text-center">رقم الجوال</th>
                <th class="text-center">الدرجة</th>
                <th class="text-center">التقييم</th>
                <th class="text-center no-print">التفاصيل</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $index => $result)
                @php
                    $score = $result->score ?? 0;
                    $scoreClass = '';
                    $evaluation = '';
                    $evaluationIcon = '';

                    if ($score >= 85) {
                        $scoreClass = 'score-excellent';
                        $evaluation = 'ممتاز';
                        $evaluationIcon = 'fa-star';
                    } elseif ($score >= 75) {
                        $scoreClass = 'score-good';
                        $evaluation = 'جيد جدًا';
                        $evaluationIcon = 'fa-star-half-alt';
                    } elseif ($score >= 65) {
                        $scoreClass = 'score-average';
                        $evaluation = 'جيد';
                        $evaluationIcon = 'fa-smile';
                    } elseif ($score >= 50) {
                        $scoreClass = 'score-average';
                        $evaluation = 'مقبول';
                        $evaluationIcon = 'fa-meh';
                    } else {
                        $scoreClass = 'score-poor';
                        $evaluation = 'راسب';
                        $evaluationIcon = 'fa-frown';
                    }
                @endphp
                <tr>
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    <td class="fw-semibold text-end">{{ $result->student->full_name }}</td>
                    <td class="text-center">{{ $result->student->seating_number }}</td>
                    <td class="text-center">{{ $result->student->phone_number ?? '-' }}</td>
                    <td class="text-center">
                        <span class="score-badge">
                            {{ $result->score }}%
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="{{ $scoreClass }}">
                            <i class="fas {{ $evaluationIcon }} me-1"></i>
                            {{ $evaluation }}
                        </span>
                    </td>
                    <td class="text-center no-print">
                        <a href="{{ route('teacher.exam.student.result', [$exam->id, $result->student_id]) }}"
                           class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="fas fa-eye me-1"></i>
                            عرض التفاصيل
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- ملخص إضافي -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm no-print" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>ملاحظة:</strong> يمكنك تصدير هذه البيانات إلى ملف Excel أو طباعتها باستخدام الأزرار أعلاه.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // تهيئة DataTable مع تحسينات Bootstrap 5
        $('#resultsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            },
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [
                { orderable: true, targets: [0, 1, 2, 3, 4] },
                { orderable: false, targets: [5, 6] },
                { className: "text-center", targets: [0, 2, 3, 4, 5, 6] },
                { className: "text-end", targets: [1] }
            ],
            order: [[4, 'desc']],
            drawCallback: function() {
                // إضافة كلاس Bootstrap للأزرار
                $('.dataTables_paginate .paginate_button').addClass('page-item');
                $('.dataTables_paginate .paginate_button a').addClass('page-link');
                $('.dataTables_length select').addClass('form-select form-select-sm');
                $('.dataTables_filter input').addClass('form-control form-control-sm');
            }
        });
    });

    // دالة تصدير إلى Excel باستخدام SheetJS
    function exportToExcel() {
        // جمع البيانات من الجدول
        const table = document.getElementById('resultsTable');
        const rows = table.querySelectorAll('tbody tr');
        const data = [];

        // إضافة عنوان التقرير
        data.push(['كشف علامات اختبار: {{ $exam->title }}']);
        data.push(['تاريخ التصدير:', new Date().toLocaleDateString('ar-EG')]);
        data.push([]);

        // إضافة رؤوس الأعمدة
        const headers = ['#', 'الطالب', 'رقم الجلوس', 'رقم الجوال', 'الدرجة (%)', 'التقييم'];
        data.push(headers);

        // إضافة بيانات الطلاب
        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            const scoreText = cells[4]?.innerText.trim() || '';
            const score = scoreText.replace('%', '');

            const rowData = [
                index + 1,
                cells[1]?.innerText.trim() || '',
                cells[2]?.innerText.trim() || '',
                cells[3]?.innerText.trim() || '',
                score,
                cells[5]?.innerText.trim().replace(/[^\w\s]/g, '').replace('ممتاز', 'ممتاز').replace('جيد جدًا', 'جيد جدًا').replace('جيد', 'جيد').replace('مقبول', 'مقبول').replace('راسب', 'راسب') || ''
            ];
            data.push(rowData);
        });

        // إضافة سطر فارغ
        data.push([]);

        // إضافة الإحصائيات
        data.push(['الإحصائيات', '', '', '', '', '']);
        data.push(['عدد الطلاب:', '{{ $totalStudents }}', '', '', '', '']);
        data.push(['المعدل العام:', '{{ $averageScore }}%', '', '', '', '']);
        data.push(['أعلى درجة:', '{{ $highestScore }}%', '', '', '', '']);
        data.push(['أدنى درجة:', '{{ $lowestScore }}%', '', '', '', '']);
        data.push(['نسبة النجاح:', '{{ $passRate }}%', '', '', '', '']);

        // إنشاء ورقة العمل
        const ws = XLSX.utils.aoa_to_sheet(data);

        // تعيين عرض الأعمدة
        ws['!cols'] = [
            {wch:5},   // #
            {wch:25},  // الطالب
            {wch:15},  // رقم الجلوس
            {wch:15},  // رقم الجوال
            {wch:12},  // الدرجة
            {wch:15}   // التقييم
        ];

        // تنسيق الخلايا
        const range = XLSX.utils.decode_range(ws['!ref'] || 'A1:F1');
        for (let row = range.s.r; row <= range.e.r; row++) {
            for (let col = range.s.c; col <= range.e.c; col++) {
                const cellAddress = XLSX.utils.encode_cell({r: row, c: col});
                if (!ws[cellAddress]) continue;

                // تنسيق الخلايا
                ws[cellAddress].s = {
                    alignment: { horizontal: 'center', vertical: 'center' },
                    font: row === 0 || row === 3 ? { bold: true, sz: 14 } : { sz: 11 }
                };
            }
        }

        // إنشاء المصنف
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'نتائج الاختبار');

        // تصدير الملف
        const fileName = `نتائج_اختبار_{{ $exam->title }}_{{ date('Y-m-d') }}.xlsx`;
        XLSX.writeFile(wb, fileName);

        // إظهار رسالة نجاح باستخدام Bootstrap Toast
        showBootstrapNotification('تم تصدير البيانات بنجاح إلى Excel', 'success');
    }

    // دالة عرض الإشعارات باستخدام Bootstrap 5
    function showBootstrapNotification(message, type = 'success') {
        // إنشاء عنصر الإشعار
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.role = 'alert';
        toast.ariaLive = 'assertive';
        toast.ariaAtomic = 'true';

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        document.body.appendChild(toastContainer);

        // تهيئة وعرض التوست
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();

        // إزالة العنصر بعد الإخفاء
        toast.addEventListener('hidden.bs.toast', function() {
            toastContainer.remove();
        });
    }
</script>

</body>
</html>
