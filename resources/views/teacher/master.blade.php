<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>منصة الاختبارات الثانوية المتكاملة - لوحة المعلم</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CKEditor 5 with MathType -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/translations/ar.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .exam-card {
            background: white;
            border-radius: 1.5rem;
            border: none;
            border-right: 8px solid #4f46e5;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .exam-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15) !important;
        }

        .question-block {
            background-color: #f9fafb;
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .question-block:hover {
            border-color: #c7d2fe;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .remove-question {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .remove-question:hover {
            background: #dc2626;
            color: white;
        }

        .question-number {
            background: #e0e7ff;
            color: #4f46e5;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
        }

        .ck-editor__editable {
            min-height: 200px;
            max-height: 400px;
        }

        .ck-content img {
            max-width: 100%;
            height: auto;
        }

        .toast-custom {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%) translateY(-100%);
            background: #1f2937;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .toast-custom.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .image-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            border-radius: 8px;
        }

        .math-preview {
            background: #f3f4f6;
            padding: 10px;
            border-radius: 8px;
            font-family: monospace;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-custom shadow-lg mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center w-100 py-2">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-university fs-2 text-white"></i>
                <span class="text-white fw-bold fs-5">بوابة التميز التعليمية</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-pill dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ auth()->guard('teacher')->user()->full_name ?? 'المعلم' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" onclick="openProfileModal()">
                                <i class="fas fa-user-edit me-2"></i>تعديل الملف الشخصي
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('teacher.logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold">
            <i class="fas fa-chalkboard-user me-2"></i>لوحة تحكم المعلم
        </h2>
        <button class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="openCreateModal()">
            <i class="fas fa-plus-circle me-2"></i>اختبار جديد
        </button>
    </div>

    @yield('content')
</div>

<!-- Modal for Exam Form -->
<div class="modal fade modal-custom" id="examModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header-custom p-4">
                <h5 class="modal-title text-white fw-bold" id="modalTitle">إعداد اختبار جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="exam_id">

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-heading me-1"></i>عنوان الاختبار
                        </label>
                        <input type="text" id="exam_title" class="form-control custom-input"
                               placeholder="مثال: اختبار الرياضيات - الفصل الأول">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar me-1"></i>تاريخ الاختبار
                        </label>
                        <input type="datetime-local" id="exam_date" class="form-control custom-input">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clock me-1"></i>المدة (دقيقة)
                        </label>
                        <input type="number" id="exam_duration" class="form-control custom-input" value="30">
                    </div>
                </div>
                <!-- الصف الجديد للفئة المستهدفة -->
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">
                            <i class="fas fa-users me-1"></i>الفئة المستهدفة
                        </label>
                        <select id="target_category" class="form-select custom-input">
                            <option value="both">الاثنين (علمي وأدبي)</option>
                            <option value="scientific">علمي</option>
                            <option value="literary">أدبي</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold fs-5 mb-0">
                            <i class="fas fa-question-circle me-2"></i>أسئلة الاختبار
                        </label>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" onclick="addQuestionField()">
                            <i class="fas fa-plus me-1"></i>إضافة سؤال
                        </button>
                    </div>
                    <div id="questions-container"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>إلغاء
                </button>
                <button type="button" class="btn btn-success rounded-pill px-4" onclick="saveExam()" id="saveExamBtn">
                    <i class="fas fa-save me-2"></i>حفظ الاختبار
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal تعديل الملف الشخصي للمعلم -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-user-edit me-2"></i>تعديل الملف الشخصي
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="profileForm">
                    @csrf
                    <input type="hidden" id="teacher_id" value="{{ auth()->guard('teacher')->user()->id ?? '' }}">

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user me-1"></i>الاسم الكامل
                        </label>
                        <input type="text" id="full_name" class="form-control rounded-3"
                               value="{{ auth()->guard('teacher')->user()->full_name ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-phone me-1"></i>رقم الهاتف
                        </label>
                        <input type="tel" id="phone_number" class="form-control rounded-3"
                               value="{{ auth()->guard('teacher')->user()->phone_number ?? '' }}" required>
                    </div>

                    {{--<div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-envelope me-1"></i>البريد الإلكتروني
                        </label>
                        <input type="email" id="email" class="form-control rounded-3"
                               value="{{ auth()->guard('teacher')->user()->email ?? '' }}" required>
                    </div>--}}

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-book me-1"></i>المادة الدراسية
                        </label>
                        <input type="text" id="subject_specialization" class="form-control rounded-3"
                               value="{{ auth()->guard('teacher')->user()->subject_specialization ?? '' }}" required>
                    </div>

                    {{--<div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-image me-1"></i>الصورة الشخصية
                        </label>
                        <input type="file" id="profile_image" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">اتركه فارغاً إذا لم ترد تغيير الصورة</small>
                        <div id="imagePreview" class="mt-2">
                            @if(auth()->guard('teacher')->user()->profile_image)
                                <img src="{{ asset('storage/' . auth()->guard('teacher')->user()->profile_image) }}"
                                     class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                            @endif
                        </div>
                    </div>--}}

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-lock me-1"></i>كلمة المرور الجديدة
                        </label>
                        <input type="password" id="password" class="form-control rounded-3"
                               placeholder="اتركها فارغة إذا لم ترد التغيير">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-lock me-1"></i>تأكيد كلمة المرور
                        </label>
                        <input type="password" id="password_confirmation" class="form-control rounded-3"
                               placeholder="أعد كتابة كلمة المرور الجديدة">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>إلغاء
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="updateProfile()">
                    <i class="fas fa-save me-2"></i>حفظ التغييرات
                </button>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast-custom">
    <span id="toast-text"></span>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    let editorInstances = {};
    let questionCounter = 0;
    let currentExamId = null;
    let bsModal = null;

    // المسارات
    const storeUrl = '{{ route("teacher.exams.store") }}';
    const updateUrl = '{{ route("teacher.exams.update", "") }}';
    const deleteUrl = '{{ route("teacher.exams.delete", "") }}';
    const togglePublishUrl = '{{ route("teacher.exams.toggle-publish", "") }}';
    const getExamUrl = '{{ route("teacher.exams.get", "") }}';
    const uploadUrl = '{{ route("teacher.upload") }}';

    // Custom upload adapter for CKEditor 5
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            // دالة لإصلاح الرابط في JavaScript
                            function fixImageUrl(url) {
                                if (!url) return null;

                                // إذا كان الرابط يبدأ بـ /storage/ أضف public
                                if (url.includes('/storage/') && !url.includes('/public/')) {
                                    url = url.replace('/storage/', '/public/storage/');
                                }

                                // إذا كان الرابط نسبياً، أضف المسار الكامل
                                if (url.startsWith('/')) {
                                    url = window.location.origin + url;
                                }

                                return url;
                            }

                            const fixedUrl = fixImageUrl(result.url);
                            console.log('Original URL:', result.url);
                            console.log('Fixed URL:', fixedUrl);

                            resolve({ default: fixedUrl });
                        } else {
                            reject(result.message || 'فشل رفع الصورة');
                        }
                    })
                    .catch(error => {
                        reject(error);
                    });
            }));
        }

        abort() {
            // Abort upload if needed
        }
    }

    // Initialize CKEditor 5 for a question
    async function initCKEditor(editorId, content = '') {
        try {
            const editor = await ClassicEditor.create(document.querySelector(`#${editorId}`), {
                language: 'ar',
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'alignment', '|',
                        'fontColor', 'fontBackgroundColor', '|',
                        'link', 'blockQuote', '|',
                        'insertTable', 'mediaEmbed', '|',
                        'imageUpload', '|',
                        'undo', 'redo'
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side'
                    ]
                },
                placeholder: 'اكتب السؤال هنا... يمكنك إضافة صور ومعادلات...'
            });

            // Set content if provided
            if (content) {
                editor.setData(content);
            }

            // Add custom upload adapter
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };

            editorInstances[editorId] = editor;
            return editor;
        } catch (error) {
            console.error('CKEditor initialization error:', error);
            return null;
        }
    }

    // Add new question field
    async function addQuestionField(questionData = null) {
        const container = document.getElementById('questions-container');
        const questionId = `q_${Date.now()}_${questionCounter++}`;
        const editorId = `editor_${questionId}`;

        const defaultData = questionData || {
            text: '',
            points: 1,
            options: ['', '', '', ''],
            correct_answer: ''
        };

        const questionHtml = `
            <div class="question-block" data-question-id="${questionId}" data-editor-id="${editorId}">
                <button type="button" class="remove-question" onclick="removeQuestion(this)">
                    <i class="fas fa-times"></i>
                </button>
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="question-number">${document.querySelectorAll('.question-block').length + 1}</div>
                    <div class="d-flex align-items-center gap-2">
                        <label class="text-muted small mb-0">درجة السؤال:</label>
                        <input type="number" value="${defaultData.points}" class="q-points form-control form-control-sm" style="width: 70px;" min="1">
                    </div>
                </div>

                <textarea id="${editorId}" class="q-text form-control mb-3" rows="5" placeholder="اكتب السؤال هنا...">${escapeHtml(defaultData.text)}</textarea>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input type="radio" name="correct_${questionId}" value="0" class="custom-radio" ${defaultData.correct_answer === defaultData.options[0] ? 'checked' : ''}>
                            <input type="text" placeholder="الخيار الأول" class="q-option form-control form-control-sm" value="${escapeHtml(defaultData.options[0] || '')}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input type="radio" name="correct_${questionId}" value="1" class="custom-radio" ${defaultData.correct_answer === defaultData.options[1] ? 'checked' : ''}>
                            <input type="text" placeholder="الخيار الثاني" class="q-option form-control form-control-sm" value="${escapeHtml(defaultData.options[1] || '')}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input type="radio" name="correct_${questionId}" value="2" class="custom-radio" ${defaultData.correct_answer === defaultData.options[2] ? 'checked' : ''}>
                            <input type="text" placeholder="الخيار الثالث" class="q-option form-control form-control-sm" value="${escapeHtml(defaultData.options[2] || '')}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input type="radio" name="correct_${questionId}" value="3" class="custom-radio" ${defaultData.correct_answer === defaultData.options[3] ? 'checked' : ''}>
                            <input type="text" placeholder="الخيار الرابع" class="q-option form-control form-control-sm" value="${escapeHtml(defaultData.options[3] || '')}">
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', questionHtml);

        // Initialize CKEditor
        await initCKEditor(editorId, defaultData.text);

        // Scroll to new question
        const newQuestion = container.lastElementChild;
        newQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Remove question
    function removeQuestion(button) {
        const questionBlock = button.closest('.question-block');
        const editorId = questionBlock.getAttribute('data-editor-id');

        if (editorId && editorInstances[editorId]) {
            editorInstances[editorId].destroy();
            delete editorInstances[editorId];
        }

        questionBlock.remove();
        reorderQuestions();
    }

    // Reorder question numbers
    function reorderQuestions() {
        const questions = document.querySelectorAll('.question-block');
        questions.forEach((question, index) => {
            const numberSpan = question.querySelector('.question-number');
            numberSpan.textContent = index + 1;
        });
    }

    // Collect questions data
    function collectQuestionsData() {
        const questions = [];
        const questionBlocks = document.querySelectorAll('.question-block');

        for (let block of questionBlocks) {
            const editorId = block.getAttribute('data-editor-id');
            let questionText = '';

            if (editorId && editorInstances[editorId]) {
                questionText = editorInstances[editorId].getData();
            } else {
                const textarea = block.querySelector('.q-text');
                if (textarea) questionText = textarea.value;
            }

            if (!questionText.trim()) {
                throw new Error('يرجى إدخال نص السؤال');
            }

            const points = parseInt(block.querySelector('.q-points').value) || 1;
            const options = Array.from(block.querySelectorAll('.q-option')).map(opt => opt.value);
            const selectedRadio = block.querySelector('input[type="radio"]:checked');

            if (!selectedRadio) {
                throw new Error('يرجى اختيار الإجابة الصحيحة');
            }

            const correctAnswer = options[parseInt(selectedRadio.value)];

            questions.push({
                text: questionText,
                points: points,
                options: options,
                correct_answer: correctAnswer
            });
        }

        if (questions.length === 0) {
            throw new Error('يجب إضافة سؤال واحد على الأقل');
        }

        return questions;
    }

    // Open create modal
    function openCreateModal() {
        currentExamId = null;
        document.getElementById('modalTitle').innerText = 'إعداد اختبار جديد';
        resetForm();
        const modal = new bootstrap.Modal(document.getElementById('examModal'));
        modal.show();
        bsModal = modal;
    }

    // Open edit modal
    async function openEditModal(examId) {
        currentExamId = examId;
        document.getElementById('modalTitle').innerText = 'تعديل الاختبار';

        try {
            showToast('جاري تحميل البيانات...');
            const response = await fetch(`${getExamUrl}/${examId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                await loadExamData(result.exam);
                const modal = new bootstrap.Modal(document.getElementById('examModal'));
                modal.show();
                bsModal = modal;
            } else {
                showToast(result.message || 'حدث خطأ في تحميل البيانات');
            }
        } catch (error) {
            console.error('Error loading exam:', error);
            showToast('فشل في تحميل بيانات الاختبار');
        }
    }

    // Load exam data for editing
    async function loadExamData(exam) {
        document.getElementById('exam_id').value = exam.id;
        document.getElementById('exam_title').value = exam.title;
        document.getElementById('exam_date').value = exam.scheduled_at;
        document.getElementById('exam_duration').value = exam.duration_minutes;
        document.getElementById('target_category').value = exam.target_category;

        // Clear existing questions
        const container = document.getElementById('questions-container');
        container.innerHTML = '';
        questionCounter = 0;

        // Destroy existing editors
        for (let key in editorInstances) {
            if (editorInstances[key]) {
                editorInstances[key].destroy();
            }
        }
        editorInstances = {};

        // Add questions
        if (exam.questions && exam.questions.length > 0) {
            for (let question of exam.questions) {
                await addQuestionField({
                    text: question.question_text,
                    points: question.points,
                    options: question.options,
                    correct_answer: question.correct_answer
                });
            }
        } else {
            await addQuestionField();
        }
    }

    // Reset form
    async function resetForm() {
        document.getElementById('exam_id').value = '';
        document.getElementById('exam_title').value = '';
        document.getElementById('exam_date').value = '';
        document.getElementById('exam_duration').value = '30';
        document.getElementById('target_category').value = '';

        const container = document.getElementById('questions-container');
        container.innerHTML = '';
        questionCounter = 0;

        // Destroy existing editors
        for (let key in editorInstances) {
            if (editorInstances[key]) {
                editorInstances[key].destroy();
            }
        }
        editorInstances = {};

        await addQuestionField();
    }

    // Save exam
    async function saveExam() {
        const btn = document.getElementById('saveExamBtn');

        try {
            const examId = document.getElementById('exam_id').value;
            const title = document.getElementById('exam_title').value.trim();
            const date = document.getElementById('exam_date').value;
            const duration = document.getElementById('exam_duration').value;
            const target_category = document.getElementById('target_category').value;

            if (!title) {
                showToast('يرجى إدخال عنوان الاختبار');
                return;
            }

            if (!date) {
                showToast('يرجى اختيار تاريخ الاختبار');
                return;
            }

            const questions = collectQuestionsData();

            const payload = {
                title: title,
                date: date,
                target_category: target_category,
                duration: parseInt(duration),
                questions: questions,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';

            const url = examId ? `${updateUrl}/${examId}` : storeUrl;
            const method = examId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showToast(examId ? 'تم تحديث الاختبار بنجاح' : 'تم إنشاء الاختبار بنجاح');
                if (bsModal) bsModal.hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(result.message || 'حدث خطأ أثناء الحفظ');
            }
        } catch (error) {
            console.error('Save error:', error);
            showToast(error.message || 'فشل الاتصال بالخادم');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ الاختبار';
        }
    }

    // Delete exam
    async function deleteExam(examId) {
        const result = await Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من استعادة هذا الاختبار وبياناته!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${deleteUrl}/${examId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    showToast('تم حذف الاختبار بنجاح');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'حدث خطأ في حذف الاختبار');
                }
            } catch (error) {
                showToast('فشل في حذف الاختبار');
            }
        }
    }

    // Toggle publish status
    async function togglePublish(examId, publish) {
        try {
            const response = await fetch(`${togglePublishUrl}/${examId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ is_published: publish })
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                showToast(publish ? 'تم نشر الاختبار' : 'تم إلغاء نشر الاختبار');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'حدث خطأ');
            }
        } catch (error) {
            showToast('فشل في تغيير حالة النشر');
        }
    }

    // Show toast message
    // دالة showToast محسنة بالكامل
    function showToast(message, type = 'info') {
        // إزالة أي toast موجود
        const existingToast = document.querySelector('.global-toast');
        if (existingToast) {
            existingToast.remove();
        }

        // إنشاء عنصر toast جديد
        const toast = document.createElement('div');
        toast.className = 'global-toast';

        // تحديد الأيقونة واللون
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };

        toast.innerHTML = `
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 18px;">${icons[type] || 'ℹ️'}</span>
            <span>${message}</span>
        </div>
    `;

        toast.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        background: ${colors[type] || '#333'};
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-family: 'Tajawal', sans-serif;
        font-size: 14px;
        z-index: 999999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        opacity: 0;
        pointer-events: none;
        min-width: 200px;
        text-align: center;
        direction: rtl;
    `;

        document.body.appendChild(toast);

        // إظهار الرسالة
        setTimeout(() => {
            toast.style.transform = 'translateX(-50%) translateY(0)';
            toast.style.opacity = '1';
        }, 10);

        // إخفاء وإزالة الرسالة
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(-100px)';
            setTimeout(() => {
                if (toast && toast.remove) {
                    toast.remove();
                }
            }, 300);
        }, 3000);
    }
    // Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        if ($('.data-table').length) {
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                },
                responsive: true
            });
        }
    });

    // فتح مودال تعديل الملف الشخصي
    function openProfileModal() {
        const modalElement = document.getElementById('profileModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            showToast('خطأ: لم يتم العثور على نافذة الملف الشخصي', 'error');
        }
    }

    // معاينة الصورة قبل الرفع
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('profile_image');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('imagePreview');
                        preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" width="80" height="80" style="object-fit: cover;">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // تحديث الملف الشخصي
    // تحديث الملف الشخصي
    async function updateProfile() {
        const teacherId = document.getElementById('teacher_id')?.value;
        const full_name = document.getElementById('full_name')?.value.trim();
        const phone_number = document.getElementById('phone_number')?.value.trim();
        const email = document.getElementById('email')?.value.trim();
        const subject = document.getElementById('subject_specialization')?.value.trim();
        const password = document.getElementById('password')?.value;
        const password_confirmation = document.getElementById('password_confirmation')?.value;
        const imageFile = document.getElementById('profile_image')?.files[0];

        if (!full_name) {
            showToast('يرجى إدخال الاسم الكامل', 'warning');
            return;
        }

        if (!phone_number) {
            showToast('يرجى إدخال رقم الهاتف', 'warning');
            return;
        }


        if (!subject) {
            showToast('يرجى إدخال المادة الدراسية', 'warning');
            return;
        }

        if (password && password !== password_confirmation) {
            showToast('كلمة المرور وتأكيدها غير متطابقين', 'warning');
            return;
        }

        if (password && password.length < 6) {
            showToast('كلمة المرور يجب أن تكون 6 أحرف على الأقل', 'warning');
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

        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');
            formData.append('full_name', full_name);
            formData.append('phone_number', phone_number);
            formData.append('email', email);
            formData.append('subject_specialization', subject);

            if (password) {
                formData.append('password', password);
                formData.append('password_confirmation', password_confirmation);
            }

            if (imageFile) {
                formData.append('profile_image', imageFile);
            }
            const profileUpdateUrl = "{{ route('teacher.profile.update', '') }}";
            const profileShowUrl = "{{ route('teacher.profile.show') }}";
            const response = await fetch(`${profileUpdateUrl}/${teacherId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التحديث',
                    text: 'تم تحديث الملف الشخصي بنجاح',
                    timer: 2000,
                    showConfirmButton: false
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
                if (modal) modal.hide();

                setTimeout(() => location.reload(), 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: result.message || 'حدث خطأ أثناء تحديث البيانات'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ في الاتصال بالخادم'
            });
        }
    }



</script>

</body>
</html>
