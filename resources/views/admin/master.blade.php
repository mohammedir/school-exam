
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الاختبارات الثانوية المتكاملة - Bootstrap 5</title>

    <!-- Bootstrap 5 CSS (RTL Support) -->
    <link href="{{asset('assets/css/bootstrap.rtl.min.css')}}" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 Theme -->
    <link href="{{asset('assets/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">

    <link href="{{ asset('assets/font/Tajawal.css') }}" rel="stylesheet">

    <style>

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f0f2f5;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bg-indigo-800 {
            background-color: #1e1b4b;
        }

        .bg-indigo-600 {
            background-color: #4f46e5;
        }

        .bg-indigo-700 {
            background-color: #4338ca;
        }

        .btn-indigo {
            background-color: #4f46e5;
            color: white;
        }

        .btn-indigo:hover {
            background-color: #4338ca;
            color: white;
        }

        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important;
        }

        .border-indigo {
            border-color: #4f46e5 !important;
        }

        .border-purple {
            border-color: #a855f7 !important;
        }

        .border-success {
            border-color: #22c55e !important;
        }

        .border-warning {
            border-color: #f97316 !important;
        }

        .stat-card {
            border-radius: 1.5rem;
            transition: all 0.3s;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: right;
        }

        .modal-content {
            border-radius: 1.5rem;
            border: none;
        }

        .form-control, .form-select {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        footer {
            border-top: 1px solid #e2e8f0;
        }

        .progress-sm {
            height: 0.5rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            .stat-card .display-6 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">

<!-- شريط التنقل العلوي - Bootstrap Navbar -->
<nav class="navbar navbar-dark bg-indigo-800 shadow-lg">
    <div class="container">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-university fs-3 text-white"></i>
            <span class="navbar-brand me-2">بوابة التميز التعليمية</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50 small d-none d-md-inline border-start ps-3 border-white-50">
                <i class="fas fa-user me-1"></i> مرحباً بك في النظام
            </span>
            <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                    <i class="fas fa-sign-out-alt me-1"></i> خروج
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-4">
    @yield('content')

</div>

<!-- Modal إضافة طالب - Bootstrap 5 -->
{{--
<div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-indigo-700 text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-plus me-2"></i>إضافة طالب جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">اسم الطالب بالكامل <span class="text-danger">*</span></label>
                    <input type="text" id="student-name" class="form-control" placeholder="مثال: يوسف خالد محمد">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">رقم الجلوس <span class="text-danger">*</span></label>
                        <input type="text" id="student-seat" class="form-control" placeholder="مثال: 100123">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الصف الدراسي</label>
                        <input type="text" id="student-classroom" class="form-control" placeholder="مثال: ثالث ثانوي">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">رقم الجوال</label>
                    <input type="tel" id="student-phone" class="form-control" placeholder="05XXXXXXXX">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <button type="button" class="btn btn-indigo rounded-pill px-5" onclick="saveStudent()">
                    <i class="fas fa-save me-1"></i> حفظ الطالب
                </button>
            </div>
        </div>
    </div>
</div>
--}}

<!-- Modal إضافة معلم - Bootstrap 5 -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-indigo-700 text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-chalkboard-user me-2"></i>إضافة معلم جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">اسم المعلم <span class="text-danger">*</span></label>
                    <input type="text" id="teacher-fullname" class="form-control" placeholder="مثال: أحمد محمد السيد">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">المادة الدراسية <span class="text-danger">*</span></label>
                    <input type="text" id="teacher-subject" class="form-control" placeholder="مثال: الرياضيات">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">رقم الجوال</label>
                    <input type="tel" id="teacher-phone" class="form-control" placeholder="05XXXXXXXX">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">كلمة المرور</label>
                    <input type="password" id="teacher-password" class="form-control" placeholder="********">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <button type="button" class="btn btn-indigo rounded-pill px-5" onclick="saveTeacher()">
                    <i class="fas fa-save me-1"></i> حفظ المعلم
                </button>
            </div>
        </div>
    </div>
</div>

<!-- مودال موحد للإضافة والتعديل -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-indigo-700 text-white border-0">
                <h5 class="modal-title fw-bold" id="studentModalTitle">
                    <i class="fas fa-user-plus me-2"></i>إضافة طالب جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- حقل مخفي لتخزين ID الطالب عند التعديل -->
                <input type="hidden" id="student_id" value="">

                <div class="mb-3">
                    <label class="form-label fw-semibold">اسم الطالب بالكامل <span class="text-danger">*</span></label>
                    <input type="text" id="student-name" class="form-control" placeholder="مثال: يوسف خالد محمد">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">رقم الجلوس <span class="text-danger">*</span></label>
                        <input type="text" id="student-seat" class="form-control" placeholder="مثال: 100123">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الصف الدراسي</label>
                        <input type="text" id="student-classroom" class="form-control" placeholder="مثال: ثالث ثانوي">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">رقم الجوال</label>
                    <input type="tel" id="student-phone" class="form-control" placeholder="05XXXXXXXX">
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">الفرع</label>
                    <select id="student-branch" class="form-control" name="student_branch">
                        <option value="" disabled selected>-- اختر الفرع --</option>
                        <option value="1">علمي</option>
                        <option value="2">أدبي</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <button type="button" id="btn_save_studentModal" class="btn btn-indigo rounded-pill px-5" onclick="saveStudent()">
                    <i class="fas fa-save me-1"></i> حفظ
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal تعديل المعلم -->
<!-- Modal تعديل المعلم -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-indigo-700 text-white border-0">
                <h5 class="modal-title fw-bold" id="editTeacherModalTitle">
                    <i class="fas fa-edit me-2"></i>تعديل بيانات المعلم
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="edit_teacher_id">

                <div class="mb-3">
                    <label class="form-label fw-semibold">اسم المعلم <span class="text-danger">*</span></label>
                    <input type="text" id="edit_teacher_fullname" class="form-control" placeholder="مثال: أحمد محمد السيد">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">المادة الدراسية <span class="text-danger">*</span></label>
                    <input type="text" id="edit_teacher_subject" class="form-control" placeholder="مثال: الرياضيات">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">رقم الجوال</label>
                    <input type="tel" id="edit_teacher_phone" class="form-control" placeholder="05XXXXXXXX">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">كلمة المرور الجديدة</label>
                    <input type="password" id="edit_teacher_password" class="form-control" placeholder="اتركها فارغة إذا لا تريد التغيير">
                    <small class="text-muted">اترك الحقل فارغاً إذا لا تريد تغيير كلمة المرور</small>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <button type="button" class="btn btn-indigo rounded-pill px-5" onclick="updateTeacherData()">
                    <i class="fas fa-save me-1"></i> تحديث البيانات
                </button>
            </div>
        </div>
    </div>
</div>

<!-- تذييل الصفحة -->
<footer class="mt-5 py-4 text-center text-muted small bg-white">
    <div class="container">
        <p class="mb-0">
            <i class="fas fa-copyright me-1"></i> 2024 نظام إدارة الاختبارات المدرسية المطوّر - جميع الحقوق محفوظة
        </p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let studentsDataTable, teachersDataTable;
    let studentBsModal, teacherBsModal,editTeacherBsModal;
    let currentMode = 'add'; // 'add' أو 'edit'
    const arabicDataTable = {
        "sEmptyTable": "لا توجد بيانات متاحة",
        "sInfo": "عرض _START_ إلى _END_ من _TOTAL_ عنصر",
        "sInfoEmpty": "عرض 0 إلى 0 من 0 عنصر",
        "sInfoFiltered": "(تمت التصفية من _MAX_ عناصر كلية)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "عرض _MENU_ عناصر",
        "sLoadingRecords": "جارٍ التحميل...",
        "sProcessing": "جارٍ المعالجة...",
        "sSearch": "بحث:",
        "sZeroRecords": "لم يتم العثور على نتائج",
        "oPaginate": {
            "sFirst": "الأول",
            "sLast": "الأخير",
            "sNext": "التالي",
            "sPrevious": "السابق"
        },
        "oAria": {
            "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
            "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
        }
    };
    $(document).ready(function () {
        // تهيئة DataTable للطلاب
        studentsDataTable = $('#students-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('students.data') }}",
                type: "GET",
                dataSrc: function(json) {
                    $('#totalStudentsCount').text(json.recordsTotal || '0');
                    return json.data;
                }
            },
            columns: [
                {
                    data: 'serial',
                    name: 'serial',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '60px'
                },
                {
                    data: 'full_name',
                    name: 'full_name',
                    title: '<i class="fas fa-user me-1"></i> اسم الطالب',
                    className: 'text-center'
                },
                {
                    data: 'seating_number',
                    name: 'seating_number',
                    title: '<i class="fas fa-id-card me-1"></i> رقم الجلوس',
                    className: 'text-center'

                },
                {
                    data: 'phone_number',
                    name: 'phone_number',
                    title: '<i class="fas fa-phone me-1"></i> الهاتف',
                    className: 'text-center'

                },
                {
                    data: 'class_room',
                    name: 'class_room',
                    title: '<i class="fas fa-school me-1"></i> الصف الدراسي',
                    className: 'text-center',
                    orderable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    title: '<i class="fas fa-cog me-1"></i> الإجراءات',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
            },
            responsive: true,
            order: [[1, 'asc']] // ترتيب حسب اسم الطالب (العمود الثاني)
        });
        // تهيئة DataTable للمعلمين
        // تهيئة DataTable للمعلمين مع الأعمدة الصحيحة
        teachersDataTable = $('#teachers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('teacher.data') }}",
                type: "GET",
                dataSrc: function(json) {
                    if (json.recordsTotal) {
                        $('#totalTeachersCount').text(json.recordsTotal);
                    }
                    return json.data;
                },
                error: function(xhr, error, code) {
                    console.log("خطأ في التحميل:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ في تحميل بيانات المعلمين'
                    });
                }
            },
            columns: [
                { data: 'full_name', name: 'full_name', className: 'text-center' },
                { data: 'subject_specialization', name: 'subject_specialization', className: 'text-center' },
                { data: 'phone_number', name: 'phone_number', className: 'text-center' },
                { data: 'status', name: 'status', className: 'text-center', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: arabicDataTable, // استخدام الترجمة المضمنة
            responsive: true,
            order: [[0, 'asc']], // ترتيب حسب اسم المعلم (العمود الأول)
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "الكل"]],
            drawCallback: function() {
                // تحديث العدد الإجمالي بعد كل تحديث
                let info = this.api().page.info();
                if ($('#totalTeachersCount').length) {
                    $('#totalTeachersCount').text(info.recordsTotal);
                }
            }
        });

        // تهيئة Bootstrap Modals
        const studentModalEl = document.getElementById('studentModal');
        const teacherModalEl = document.getElementById('teacherModal');
        if (studentModalEl) studentBsModal = new bootstrap.Modal(studentModalEl);
        if (teacherModalEl) teacherBsModal = new bootstrap.Modal(teacherModalEl);
    });

    // فتح نافذة إضافة طالب
    function openStudentModal() {
        currentMode = 'add'; // تعيين الوضع إلى إضافة
        // تغيير عنوان المودال
        document.getElementById('studentModalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i>إضافة طالب جديد';
        // تفريغ الحقول
        document.getElementById('student_id').value = '';
        document.getElementById('student-name').value = '';
        document.getElementById('student-seat').value = '';
        document.getElementById('student-phone').value = '';
        document.getElementById('student-classroom').value = '';


        if (studentBsModal) studentBsModal.show();
    }
    function editStudent(id) {
        currentMode = 'edit'; // تعيين الوضع إلى تعديل
        // جلب بيانات الطالب من السيرفر
        fetch("{{ route('students.show', '') }}/" + id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تغيير عنوان المودال
                    document.getElementById('studentModalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>تعديل بيانات الطالب';

                    // ملء الحقول بالبيانات الحالية
                    document.getElementById('student_id').value = data.student.id;
                    document.getElementById('student-name').value = data.student.full_name;
                    document.getElementById('student-seat').value = data.student.seating_number;
                    document.getElementById('student-phone').value = data.student.phone_number || '';
                    document.getElementById('student-classroom').value = data.student.class_room || '';
                    document.getElementById('student-branch').value = data.student.student_branch || '';
                    // فتح المودال
                    if (studentBsModal) studentBsModal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'لم يتم العثور على بيانات الطالب'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء جلب بيانات الطالب'
                });
            });
    }
    function editTeacher(id) {
        currentMode = 'edit'; // تعيين الوضع إلى تعديل

        // جلب بيانات المعلم من السيرفر
        fetch("{{ route('teacher.show', '') }}/" + id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تغيير عنوان المودال ليتناسب مع المعلم
                    const modalTitle = document.getElementById('editTeacherModalTitle');
                    if (modalTitle) {
                        modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>تعديل بيانات المعلم';
                    }

                    // ملء الحقول بالبيانات الحالية للمعلم
                    document.getElementById('edit_teacher_id').value = data.teacher.id;
                    document.getElementById('edit_teacher_fullname').value = data.teacher.full_name;
                    document.getElementById('edit_teacher_subject').value = data.teacher.subject_specialization;
                    document.getElementById('edit_teacher_phone').value = data.teacher.phone_number || '';
                    document.getElementById('edit_teacher_password').value = ''; // حقل كلمة المرور يترك فارغاً

                    // فتح مودال تعديل المعلم
                    if (editTeacherBsModal) {
                        editTeacherBsModal.show();
                    } else {
                        // إذا لم يتم تهيئة المودال، قم بتهيئته
                        const modalElement = document.getElementById('editTeacherModal');
                        if (modalElement) {
                            editTeacherBsModal = new bootstrap.Modal(modalElement);
                            editTeacherBsModal.show();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'لم يتم العثور على نافذة التعديل'
                            });
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: data.message || 'لم يتم العثور على بيانات المعلم'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء جلب بيانات المعلم: ' + error.message
                });
            });
    }
    // دالة تحديث بيانات المعلم
    function updateTeacherData() {
        const id = document.getElementById('edit_teacher_id')?.value;
        const full_name = document.getElementById('edit_teacher_fullname')?.value.trim();
        const subject = document.getElementById('edit_teacher_subject')?.value.trim();
        const phone = document.getElementById('edit_teacher_phone')?.value.trim();
        const password = document.getElementById('edit_teacher_password')?.value;

        if (!id || !full_name || !subject) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال جميع البيانات المطلوبة',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        Swal.fire({
            title: 'جاري التحديث...',
            text: 'يرجى الانتظار',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('teacher.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                id: id,
                full_name: full_name,
                subject_specialization: subject,
                phone_number: phone,
                password: password
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحديث',
                        text: 'تم تحديث بيانات المعلم بنجاح',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    if (editTeacherBsModal) {
                        editTeacherBsModal.hide();
                    }

                    if (teachersDataTable) {
                        teachersDataTable.ajax.reload(null, false);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: data.message || 'حدث خطأ أثناء تحديث البيانات'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحديث البيانات: ' + error.message
                });
            });
    }
    // دالة حذف المعلم
    function deleteTeacher(id, name) {
        Swal.fire({
            title: 'تأكيد الحذف',
            html: `هل أنت متأكد من حذف المعلم <strong>"${name}"</strong>؟`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'جاري الحذف...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch("{{ route('teacher.delete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({id: id})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف',
                                text: 'تم حذف المعلم بنجاح',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            if (teachersDataTable) {
                                teachersDataTable.ajax.reload(null, false);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: data.message || 'حدث خطأ أثناء حذف المعلم'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء حذف المعلم: ' + error.message
                        });
                    });
            }
        });
    }
    // فتح نافذة إضافة معلم
    function openTeacherModal() {
        if (teacherBsModal) teacherBsModal.show();
    }

    // حفظ الطالب
    function saveStudent() {

        if (currentMode === 'edit') {
            updateStudent()
        } else {

        let name = document.getElementById('student-name').value.trim();
        let seat = document.getElementById('student-seat').value.trim();
        let phone = document.getElementById('student-phone').value.trim();
        let student_branch = document.getElementById('student-branch').value.trim();
        let classroom = document.getElementById('student-classroom').value.trim();

        if (!name) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال اسم الطالب',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        if (!seat) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال رقم الجلوس',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        fetch("{{ route('students.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                full_name: name,
                seating_number: seat,
                phone_number: phone,
                class_room: classroom,
                student_branch: student_branch
            })
        })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم إضافة الطالب',
                    text: 'تم حفظ بيانات الطالب بنجاح',
                    timer: 1500,
                    showConfirmButton: false
                });

                if (studentBsModal) studentBsModal.hide();

                // إعادة تحميل الجدول
                if (studentsDataTable) studentsDataTable.ajax.reload(null, false);

                // تنظيف الحقول
                document.getElementById('student-name').value = '';
                document.getElementById('student-seat').value = '';
                document.getElementById('student-phone').value = '';
                document.getElementById('student-classroom').value = '';
                document.getElementById('student-branch').value = '';
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء حفظ البيانات، يرجى المحاولة مرة أخرى',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    }
    function updateStudent() {
        let student_id = document.getElementById('student_id').value.trim();
        let name = document.getElementById('student-name').value.trim();
        let seat = document.getElementById('student-seat').value.trim();
        let phone = document.getElementById('student-phone').value.trim();
        let classroom = document.getElementById('student-classroom').value.trim();
        let student_branch = document.getElementById('student-branch').value.trim();

        if (!name) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال اسم الطالب',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        if (!seat) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال رقم الجلوس',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        fetch("{{ route('students.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id: student_id,
                full_name: name,
                seating_number: seat,
                phone_number: phone,
                class_room: classroom,
                student_branch: student_branch
            })
        })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم إضافة الطالب',
                    text: 'تم حفظ بيانات الطالب بنجاح',
                    timer: 1500,
                    showConfirmButton: false
                });

                if (studentBsModal) studentBsModal.hide();

                // إعادة تحميل الجدول
                if (studentsDataTable) studentsDataTable.ajax.reload(null, false);

                // تنظيف الحقول
                document.getElementById('student-name').value = '';
                document.getElementById('student-seat').value = '';
                document.getElementById('student-phone').value = '';
                document.getElementById('student-classroom').value = '';
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء حفظ البيانات، يرجى المحاولة مرة أخرى',
                    confirmButtonColor: '#dc3545'
                });
            });
    }

    // حفظ المعلم
    function saveTeacher() {
        let fullname = document.getElementById('teacher-fullname').value.trim();
        let subject = document.getElementById('teacher-subject').value.trim();
        let phone = document.getElementById('teacher-phone').value.trim();
        let password = document.getElementById('teacher-password').value;

        if (!fullname) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال اسم المعلم',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        if (!subject) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال المادة الدراسية',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        fetch("{{ route('teacher.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                full_name: fullname,
                subject_specialization: subject,
                phone_number: phone,
                password: password
            })
        })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم إضافة المعلم',
                    text: 'تم حفظ بيانات المعلم بنجاح',
                    timer: 1500,
                    showConfirmButton: false
                });

                if (teacherBsModal) teacherBsModal.hide();

                // إعادة تحميل جدول المعلمين
                if (teachersDataTable) teachersDataTable.ajax.reload(null, false);

                // تنظيف الحقول
                document.getElementById('teacher-fullname').value = '';
                document.getElementById('teacher-subject').value = '';
                document.getElementById('teacher-phone').value = '';
                document.getElementById('teacher-password').value = '';
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء حفظ البيانات، يرجى المحاولة مرة أخرى',
                    confirmButtonColor: '#dc3545'
                });
            });
    }

    // وظائف مساعدة للتوافق مع الكود القديم
    function openModal() {
        openStudentModal();
    }

    function addTeacher() {
        openTeacherModal();
    }

    function closeModal() {
        if (studentBsModal) studentBsModal.hide();
        document.getElementById('student-name').value = '';
        document.getElementById('student-seat').value = '';
        document.getElementById('student-phone').value = '';
        document.getElementById('student-classroom').value = '';
    }

    function closeTeacherModal() {
        if (teacherBsModal) teacherBsModal.hide();
        document.getElementById('teacher-fullname').value = '';
        document.getElementById('teacher-subject').value = '';
        document.getElementById('teacher-phone').value = '';
        document.getElementById('teacher-password').value = '';
    }

    function toggleModal(id, show) {
        if (id === 'studentModal') {
            if (show) openStudentModal();
            else if (studentBsModal) studentBsModal.hide();
        } else if (id === 'teacherModal') {
            if (show) openTeacherModal();
            else if (teacherBsModal) teacherBsModal.hide();
        }
    }
</script>

@stack('scripts')
</body>
</html>
