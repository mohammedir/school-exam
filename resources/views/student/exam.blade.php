<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الاختبار - {{ $exam->title }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 1.5rem;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-card {
            background: white;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .question-card {
            background: white;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .option-label:hover {
            background: #e0e7ff;
            border-color: #4f46e5;
            transform: translateX(-5px);
        }

        .option-label.selected {
            background: #e0e7ff;
            border-color: #4f46e5;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .custom-radio {
            accent-color: #4f46e5;
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
        }

        .timer-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .timer-display {
            font-size: 2rem;
            font-weight: bold;
            color: #dc2626;
            font-family: monospace;
        }

        .progress-custom {
            height: 8px;
            border-radius: 1rem;
        }

        .progress-custom .progress-bar {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border-radius: 1rem;
            transition: width 0.3s ease;
        }

        .nav-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
        }

        .question-indicator {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #f3f4f6;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.2rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .question-indicator:hover {
            background: #e0e7ff;
            transform: scale(1.05);
        }

        .question-indicator.answered {
            background: #10b981;
            color: white;
        }

        .question-indicator.current {
            background: #4f46e5;
            color: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
        }

        .question-number {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: #4f46e5;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            font-size: 0.875rem;
            font-weight: bold;
            margin-left: 0.75rem;
        }

        .question-text {
            flex: 1;
            line-height: 1.6;
        }

        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 10px 0;
        }

        .warning-banner {
            background: #fef3c7;
            color: #92400e;
            border-right: 4px solid #f59e0b;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .timer-display {
                font-size: 1.5rem;
            }

            .option-label {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container fade-in" style="max-width: 900px;">
    <!-- رأس الصفحة + المؤقت -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card header-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-file-alt text-primary fs-3"></i>
                        <h3 class="fw-bold mb-0">{{ $exam->title }}</h3>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <p class="text-muted mb-0 small">
                                <i class="fas fa-list-ol me-1"></i>
                                عدد الأسئلة: <strong>{{ count($questions) }}</strong>
                            </p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-0 small">
                                <i class="fas fa-clock me-1"></i>
                                المدة: <strong>{{ $exam->duration_minutes }}</strong> دقيقة
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card timer-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                        <i class="fas fa-hourglass-half text-danger"></i>
                        <span class="text-muted small fw-bold">الوقت المتبقي</span>
                    </div>
                    <div class="timer-display" id="timer">--:--</div>
                </div>
            </div>
        </div>
    </div>

    <!-- تحذير التصفح -->
    <div class="warning-banner fade-in">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>تنبيه:</strong> يرجى عدم تحديث الصفحة أو إغلاقها أثناء الاختبار، فقد تفقد إجاباتك.
    </div>

    <!-- شريط التقدم -->
    <div class="mb-4">
        <div class="d-flex justify-content-between mb-2">
            <small class="text-white fw-bold">تقدم الاختبار</small>
            <small class="text-white fw-bold" id="progress-percent">0%</small>
        </div>
        <div class="progress progress-custom">
            <div class="progress-bar" id="progress-bar" style="width: 0%"></div>
        </div>
    </div>

    <!-- مؤشرات الأسئلة السريعة -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-center gap-1" id="indicators-container">
                <!-- سيتم إنشاء المؤشرات بواسطة JavaScript -->
            </div>
        </div>
    </div>

    <form id="examForm" onsubmit="submitExam(event)">
        <div id="questions-container"></div>

        <!-- أزرار التنقل -->
        <div class="d-flex justify-content-between gap-3 mt-4">
            <button type="button" id="prevBtn" onclick="changePage(-1)" class="btn btn-secondary nav-btn">
                <i class="fas fa-chevron-right me-2"></i>
                السابق
            </button>
            <button type="button" id="nextBtn" onclick="changePage(1)" class="btn btn-primary nav-btn">
                التالي
                <i class="fas fa-chevron-left ms-2"></i>
            </button>
        </div>

        <button type="submit" id="submitBtn" class="btn btn-success w-100 mt-4 py-3 rounded-pill fw-bold shadow">
            <i class="fas fa-paper-plane me-2"></i>
            تسليم الامتحان
        </button>
    </form>
</div>

<script>
    // بيانات الأسئلة من الخادم
    const questionsData = @json($questions);
    let questions = [];
    let currentPage = 0;
    let questionsPerPage = 5; // عدد الأسئلة في كل صفحة
    let answers = {};
    let timerInterval = null;
    let timeRemaining = {{ $exam->duration_minutes }} * 60;
    let examSubmitted = false;

    // تهيئة الأسئلة مع التأكد من وجود shuffled_options
    function initializeQuestions() {
        if (!questionsData || !Array.isArray(questionsData)) {
            console.error('No questions data received');
            return false;
        }

        questions = questionsData.map((q, index) => {
            let options = q.shuffled_options || q.options;

            if (typeof options === 'string') {
                try {
                    options = JSON.parse(options);
                } catch(e) {
                    console.error('Failed to parse options for question', q.id);
                    options = ['الخيار الأول', 'الخيار الثاني', 'الخيار الثالث', 'الخيار الرابع'];
                }
            }

            if (!Array.isArray(options)) {
                options = ['الخيار الأول', 'الخيار الثاني', 'الخيار الثالث', 'الخيار الرابع'];
            }

            return {
                id: q.id,
                question_text: q.question_text,
                points: q.points || 1,
                options: options,
                correct_answer: q.correct_answer
            };
        });

        console.log('Initialized questions:', questions.length);
        return true;
    }

    // تحميل الإجابات المحفوظة
    function loadSavedAnswers() {
        const examId = {{ $exam->id }};
        const saved = localStorage.getItem(`exam_${examId}_answers`);
        const savedTime = localStorage.getItem(`exam_${examId}_time`);
        const savedTimestamp = localStorage.getItem(`exam_${examId}_timestamp`);
        const savedPage = localStorage.getItem(`exam_${examId}_page`);

        if (saved && savedTimestamp) {
            const hoursPassed = (Date.now() - parseInt(savedTimestamp)) / (1000 * 60 * 60);
            if (hoursPassed < 24) {
                try {
                    const parsedAnswers = JSON.parse(saved);
                    answers = { ...parsedAnswers };
                    console.log('Loaded saved answers:', Object.keys(answers).length);

                    if (savedTime && hoursPassed < 1) {
                        const remaining = parseInt(savedTime);
                        if (remaining > 0 && remaining < timeRemaining) {
                            timeRemaining = remaining;
                        }
                    }

                    if (savedPage) {
                        currentPage = parseInt(savedPage);
                    }
                } catch(e) {
                    console.error('Error loading saved answers:', e);
                }
            }
        }

        updateProgress();
        updateIndicators();
    }

    // حفظ الإجابات في localStorage
    function saveToLocalStorage() {
        const examId = {{ $exam->id }};
        localStorage.setItem(`exam_${examId}_answers`, JSON.stringify(answers));
        localStorage.setItem(`exam_${examId}_time`, timeRemaining);
        localStorage.setItem(`exam_${examId}_page`, currentPage);
        localStorage.setItem(`exam_${examId}_timestamp`, Date.now());
    }

    // عرض الأسئلة في الصفحة الحالية
    function renderCurrentPage() {
        if (!questions.length) {
            console.error('No questions to render');
            return;
        }

        const startIdx = currentPage * questionsPerPage;
        const endIdx = Math.min(startIdx + questionsPerPage, questions.length);
        const currentQuestions = questions.slice(startIdx, endIdx);

        let html = '';

        currentQuestions.forEach((question, idx) => {
            const globalIndex = startIdx + idx;
            const isAnswered = answers[question.id] !== undefined && answers[question.id] !== '';
            const userAnswer = answers[question.id] || '';

            let questionText = question.question_text || 'السؤال غير متوفر';

            html += `
            <div class="card question-card mb-4 fade-in" data-question-id="${question.id}">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-4">
                        <div class="question-number">${globalIndex + 1}</div>
                        <div class="question-text flex-grow-1">
                            <div class="fw-bold fs-5">${questionText}</div>
                        </div>
                        ${isAnswered ? '<i class="fas fa-check-circle text-success fs-4"></i>' : '<i class="fas fa-circle-question text-muted fs-4"></i>'}
                    </div>

                    <div class="options-container mt-3">
                        ${question.options.map((opt, optIndex) => `
                            <label class="option-label ${userAnswer === opt ? 'selected' : ''}"
                                   onclick="selectOption('${question.id}', '${escapeHtml(opt).replace(/'/g, "\\'")}')">
                                <input type="radio"
                                       name="q_${question.id}"
                                       value="${escapeHtml(opt)}"
                                       class="custom-radio"
                                       ${userAnswer === opt ? 'checked' : ''}
                                       onchange="saveAnswer('${question.id}', this.value)">
                                <span class="flex-grow-1">${escapeHtml(opt)}</span>
                            </label>
                        `).join('')}
                    </div>

                    <div class="mt-3 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-star-of-life text-warning"></i>
                            درجة السؤال: ${question.points} نقطة
                        </small>
                    </div>
                </div>
            </div>
        `;
        });

        // إضافة مؤشر الصفحة
        const totalPages = Math.ceil(questions.length / questionsPerPage);
        const pageInfo = `
        <div class="text-center mb-3">
            <span class="badge bg-primary px-3 py-2">
                <i class="fas fa-file-alt me-1"></i>
                الصفحة ${currentPage + 1} من ${totalPages}
                <i class="fas fa-arrow-left me-1"></i>
                <i class="fas fa-arrow-right ms-1"></i>
            </span>
        </div>
    `;

        document.getElementById('questions-container').innerHTML = pageInfo + html;
        updateButtons();
        saveToLocalStorage();
    }

    // تغيير الصفحة
    function changePage(delta) {
        saveCurrentPageAnswers();

        const totalPages = Math.ceil(questions.length / questionsPerPage);
        const newPage = currentPage + delta;

        if (newPage >= 0 && newPage < totalPages) {
            currentPage = newPage;
            renderCurrentPage();
            updateIndicators();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // حفظ جميع الإجابات في الصفحة الحالية
    function saveCurrentPageAnswers() {
        document.querySelectorAll('.question-card').forEach(card => {
            const selectedRadio = card.querySelector('input[type="radio"]:checked');
            if (selectedRadio) {
                const questionId = selectedRadio.name.replace('q_', '');
                answers[questionId] = selectedRadio.value;
            }
        });
        saveToLocalStorage();
    }

    // حفظ إجابة محددة (عند اختيار خيار)
    function saveAnswer(questionId, value) {
        answers[questionId] = value;
        updateProgress();
        updateIndicators();
        saveToLocalStorage();

        // تأثير بصري للإجابة
        const currentCard = document.querySelector(`.question-card[data-question-id="${questionId}"]`);
        if (currentCard) {
            currentCard.style.transform = 'scale(1.01)';
            setTimeout(() => {
                if (currentCard) currentCard.style.transform = '';
            }, 200);
        }
    }

    // اختيار خيار
    function selectOption(questionId, value) {
        const radio = document.querySelector(`input[name="q_${questionId}"][value="${value}"]`);
        if (radio) {
            radio.checked = true;
            saveAnswer(questionId, value);

            // تحديث المظهر المرئي للخيار المحدد
            const optionLabels = document.querySelectorAll(`.question-card[data-question-id="${questionId}"] .option-label`);
            optionLabels.forEach(label => {
                label.classList.remove('selected');
            });
            radio.closest('.option-label').classList.add('selected');
        }
    }

    // تحديث الأزرار
    function updateButtons() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const totalPages = Math.ceil(questions.length / questionsPerPage);

        if (prevBtn) {
            prevBtn.style.display = currentPage === 0 ? 'none' : 'inline-flex';
        }

        if (nextBtn) {
            nextBtn.style.display = currentPage >= totalPages - 1 ? 'none' : 'inline-flex';
        }
    }

    // تحديث مؤشرات الأسئلة
    function updateIndicators() {
        const container = document.getElementById('indicators-container');
        if (!container) return;

        let html = '';
        questions.forEach((question, index) => {
            const isAnswered = answers[question.id] !== undefined && answers[question.id] !== '';
            const questionPage = Math.floor(index / questionsPerPage);
            const isCurrentPage = questionPage === currentPage;

            html += `
            <div class="question-indicator ${isAnswered ? 'answered' : ''} ${isCurrentPage ? 'current' : ''}"
                 onclick="goToQuestion(${index})"
                 title="السؤال ${index + 1}">
                ${index + 1}
            </div>
        `;
        });
        container.innerHTML = html;
    }

    // الذهاب إلى سؤال محدد
    function goToQuestion(index) {
        if (index >= 0 && index < questions.length) {
            saveCurrentPageAnswers();
            const targetPage = Math.floor(index / questionsPerPage);
            currentPage = targetPage;
            renderCurrentPage();
            updateIndicators();

            // التمرير إلى السؤال المحدد
            setTimeout(() => {
                const questionsContainer = document.getElementById('questions-container');
                const questionCards = questionsContainer.querySelectorAll('.question-card');
                const targetCard = questionCards[index % questionsPerPage];
                if (targetCard) {
                    targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    targetCard.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        if (targetCard) targetCard.style.transform = '';
                    }, 500);
                }
            }, 100);
        }
    }

    // تحديث شريط التقدم
    function updateProgress() {
        const total = questions.length;
        const answeredCount = Object.keys(answers).filter(key => answers[key] && answers[key] !== '').length;
        const percent = total > 0 ? (answeredCount / total) * 100 : 0;

        const progressBar = document.getElementById('progress-bar');
        const progressPercent = document.getElementById('progress-percent');

        if (progressBar) progressBar.style.width = `${percent}%`;
        if (progressPercent) progressPercent.textContent = `${Math.round(percent)}%`;
    }

    // بدء المؤقت
    function startTimer() {
        updateTimerDisplay();

        timerInterval = setInterval(() => {
            if (examSubmitted) return;

            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                document.getElementById('timer').innerHTML = '00:00';

                Swal.fire({
                    icon: 'warning',
                    title: 'انتهى الوقت!',
                    text: 'انتهى الوقت المخصص للاختبار. سيتم تسليم الامتحان تلقائياً.',
                    confirmButtonText: 'حسناً',
                    allowOutsideClick: false
                }).then(() => {
                    if (!examSubmitted) {
                        autoSubmitExam();
                    }
                });
                return;
            }

            timeRemaining--;
            updateTimerDisplay();

            if (timeRemaining % 60 === 0) {
                saveToLocalStorage();
            }

            if (timeRemaining === 300) {
                showToast('⚠️ تبقى 5 دقائق على انتهاء الاختبار', 'warning');
            } else if (timeRemaining === 60) {
                showToast('⚠️ تبقى دقيقة واحدة فقط!', 'warning');
            } else if (timeRemaining === 30) {
                showToast('⚠️ تبقى 30 ثانية!', 'danger');
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timerElement = document.getElementById('timer');
        if (timerElement) {
            timerElement.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeRemaining <= 60) {
                timerElement.style.color = '#ef4444';
                timerElement.style.animation = 'pulse 1s infinite';
            } else if (timeRemaining <= 300) {
                timerElement.style.color = '#f59e0b';
            } else {
                timerElement.style.color = '#dc2626';
                timerElement.style.animation = 'none';
            }
        }
    }

    // إرسال تلقائي
    async function autoSubmitExam() {
        if (examSubmitted) return;
        saveCurrentPageAnswers();
        await submitExamToServer(true);
    }

    // إرسال الامتحان
    async function submitExam(e) {
        e.preventDefault();

        if (examSubmitted) return;

        saveCurrentPageAnswers();

        const total = questions.length;
        const answeredCount = Object.keys(answers).filter(key => answers[key] && answers[key] !== '').length;
        const unanswered = total - answeredCount;

        if (unanswered > 0) {
            const result = await Swal.fire({
                title: 'تنبيه',
                html: `لم تقم بالإجابة على <strong class="text-danger">${unanswered}</strong> سؤالاً من أصل <strong>${total}</strong>.<br><br>هل تريد تسليم الامتحان؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'نعم، أسلم الامتحان',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            });

            if (!result.isConfirmed) {
                return;
            }
        } else {
            const confirmSubmit = await Swal.fire({
                title: 'تأكيد التسليم',
                text: 'هل أنت متأكد من تسليم الامتحان؟ لن تتمكن من تعديل الإجابات بعد التسليم.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، أسلم',
                cancelButtonText: 'إلغاء'
            });

            if (!confirmSubmit.isConfirmed) {
                return;
            }
        }

        await submitExamToServer(false);
    }

    // إرسال البيانات للخادم
    async function submitExamToServer(isAuto = false) {
        if (examSubmitted) return;
        examSubmitted = true;

        if (timerInterval) {
            clearInterval(timerInterval);
        }

        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري تسليم الامتحان...';

        const formattedAnswers = {};
        for (const [questionId, answer] of Object.entries(answers)) {
            formattedAnswers[questionId] = answer;
        }

        try {
            const response = await fetch("{{ route('student.exam.submit') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    exam_id: {{ $exam->id }},
                    answers: formattedAnswers
                })
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                const examId = {{ $exam->id }};
                localStorage.removeItem(`exam_${examId}_answers`);
                localStorage.removeItem(`exam_${examId}_time`);
                localStorage.removeItem(`exam_${examId}_page`);
                localStorage.removeItem(`exam_${examId}_timestamp`);

                await Swal.fire({
                    icon: 'success',
                    title: 'تم التسليم بنجاح',
                    html: data.message || 'تم تسليم الامتحان وحفظ الإجابات بنجاح',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#4f46e5',
                    timer: 3000,
                    timerProgressBar: true
                });

                window.location.href = "{{ route('student.dashboard') }}";
            } else {
                throw new Error(data.message || 'حدث خطأ أثناء تسليم الامتحان');
            }
        } catch (error) {
            console.error('Submit error:', error);
            examSubmitted = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            Swal.fire({
                icon: 'error',
                title: 'خطأ في الإرسال',
                text: error.message || 'فشل الاتصال بالخادم. يرجى المحاولة مرة أخرى.',
                confirmButtonText: 'حاول مرة أخرى',
                confirmButtonColor: '#4f46e5'
            });
        }
    }

    // عرض رسالة
    function showToast(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: type === 'warning' ? 'warning' : (type === 'danger' ? 'error' : 'success'),
            title: message
        });
    }

    // ترميز HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // منع التحديث
    window.addEventListener('beforeunload', function(e) {
        const hasAnswers = Object.keys(answers).length > 0;
        if (hasAnswers && !examSubmitted && timeRemaining > 0 && timeRemaining < {{ $exam->duration_minutes }} * 60) {
            e.preventDefault();
            e.returnValue = 'لديك إجابات غير محفوظة. هل أنت متأكد من مغادرة الصفحة؟';
            return e.returnValue;
        }
    });

    // تهيئة الصفحة
    function init() {
        if (initializeQuestions()) {
            loadSavedAnswers();
            renderCurrentPage();
            startTimer();
        } else {
            document.getElementById('questions-container').innerHTML = `
            <div class="alert alert-danger text-center p-5">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>عذراً، لا توجد أسئلة في هذا الاختبار</h5>
                <p>يرجى التواصل مع المعلم</p>
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
                </a>
            </div>
        `;
            document.getElementById('submitBtn').style.display = 'none';
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('nextBtn').style.display = 'none';
        }
    }

    // بدء التطبيق
    init();
</script>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    .option-label {
        transition: all 0.2s ease;
    }

    .option-label:active {
        transform: scale(0.98);
    }

    .question-text img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }
</style>

</body>
</html>
