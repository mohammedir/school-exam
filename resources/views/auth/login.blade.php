<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الاختبارات الثانوية المتكاملة</title>

    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-icons.min.css')}}">
    <link href="{{asset('assets/css/bootstrap.rtl.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/all.min.css')}}" rel="stylesheet">
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

        .custom-card {
            border: none;
            border-radius: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15) !important;
        }

        .custom-input {
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .custom-input.is-invalid {
            border-color: #dc3545;
            background-color: #fff8f8;
        }

        .btn-custom {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            transform: translateY(-2px);
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        /* رسالة الخطأ المخصصة */
        .error-alert {
            animation: shake 0.5s ease-in-out;
            border-right: 4px solid #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .error-icon {
            width: 40px;
            height: 40px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* تأثيرات إضافية */
        .input-error {
            border-color: #dc3545 !important;
            background-color: #fff8f8 !important;
        }

        .error-message {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 576px) {
            .custom-card {
                margin: 1rem;
            }
        }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center">

<section class="container fade-in" style="max-width: 450px;">

    <!-- Logo + Welcome -->
    <div class="text-center mb-4">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/photo/logo.jpg') }}"
                 alt="شعار المدرسة"
                 class="rounded-circle img-fluid shadow-lg"
                 style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #ffffff;">
        </div>

        <h1 class="display-6 fw-bold text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
            مدرسة Learn To Be
        </h1>

        <p class="text-white-50 small mt-2">
            نظام إدارة الاختبارات الثانوية المتكامل
        </p>
    </div>

    <!-- Card -->
    <div class="card custom-card shadow-lg">

        <!-- Header -->
        <div class="card-header bg-primary text-white text-center p-4" style="border: none; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
            <h3 class="card-title h2 fw-bold mb-2">تسجيل الدخول</h3>
            <p class="text-white-50 mb-0 small">
                اختر نوع الحساب للدخول إلى النظام
            </p>
        </div>

        <!-- Form -->
        <div class="card-body p-4">

            <!-- عرض رسالة الخطأ بشكل جميل -->
            @if ($errors->any())
                <div class="alert error-alert mb-4 p-3 rounded-3 shadow-sm" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <div class="error-icon flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-danger">فشل تسجيل الدخول</h6>
                            <p class="mb-0 text-muted small">
                                {{ $errors->first() }}
                            </p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- عرض رسالة نجاح إذا وجدت -->
            @if (session('success'))
                <div class="alert alert-success mb-4 p-3 rounded-3 shadow-sm" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 text-success">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Role -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-person-badge me-2"></i>نوع الحساب
                    </label>
                    <select name="role" id="role" onchange="updateUI()"
                            class="form-select custom-select py-2 rounded-3">
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>👨‍🎓 طالب</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>👨‍🏫 معلم</option>
                    </select>
                </div>

                <!-- Identifier -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary" id="identifierLabel">
                        <i class="bi bi-person-badge me-2"></i>رقم الجلوس
                    </label>
                    <input type="text" name="identifier" id="identifier"
                           class="form-control custom-input py-2 rounded-3 @error('identifier') is-invalid input-error @enderror"
                           placeholder="أدخل رقم الجلوس"
                           value="{{ old('identifier') }}">

                    <!-- رسالة خطأ مخصصة تحت الحقل -->
                    @error('identifier')
                    <div class="error-message text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4" id="passwordContainer" style="{{ old('role') == 'teacher' ? 'display: block;' : 'display: none;' }}">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-lock me-2"></i>كلمة المرور
                    </label>
                    <input type="password" name="password" id="password"
                           class="form-control custom-input py-2 rounded-3 @error('password') is-invalid input-error @enderror"
                           placeholder="أدخل كلمة المرور">

                    @error('password')
                    <div class="error-message text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Button -->
                <button type="submit"
                        class="btn btn-custom text-white w-100 py-2 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-box-arrow-in-left me-2"></i>
                    دخول
                </button>

                <!-- روابط إضافية -->
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <a href="#" class="text-decoration-none text-primary" onclick="showHelp()">مساعدة في تسجيل الدخول؟</a>
                    </small>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4">
        <p class="small text-white-50">
            <i class="bi bi-c-circle me-1"></i> 2026 Learn To Be School - جميع الحقوق محفوظة
        </p>
    </footer>

</section>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function updateUI() {
        let role = document.getElementById('role').value;
        let passwordContainer = document.getElementById('passwordContainer');
        let identifierInput = document.getElementById('identifier');
        let identifierLabel = document.getElementById('identifierLabel');

        if (role === 'student') {
            passwordContainer.style.display = 'none';
            identifierInput.placeholder = 'أدخل رقم الجلوس (مثال: 2024001)';
            identifierLabel.innerHTML = '<i class="bi bi-person-badge me-2"></i>رقم الجلوس';
        } else {
            passwordContainer.style.display = 'block';
            identifierInput.placeholder = 'أدخل رقم الهاتف (مثال: 05xxxxxxxx)';
            identifierLabel.innerHTML = '<i class="bi bi-phone me-2"></i>رقم الهاتف';
        }
    }

    // عرض رسائل الخطأ باستخدام SweetAlert2 إذا كان هناك خطأ في الجلسة
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'خطأ في تسجيل الدخول',
        text: '{{ session('error') }}',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'حاول مرة أخرى',
        timer: 5000,
        timerProgressBar: true,
        showClass: {
            popup: 'animate__animated animate__shakeX'
        }
    });
    @endif

    // عرض رسائل الخطأ من validation errors باستخدام SweetAlert2
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'فشل تسجيل الدخول',
        html: `
                <div class="text-right">
                    <p class="mb-2"><strong>سبب الخطأ:</strong></p>
                    <p class="text-danger">⚠️ {{ $errors->first() }}</p>
                    <hr class="my-2">
                    <p class="small text-muted mt-2">💡 تأكد من صحة البيانات وحاول مرة أخرى</p>
                </div>
            `,
        confirmButtonColor: '#4f46e5',
        confirmButtonText: 'حسنًا',
        showClass: {
            popup: 'animate__animated animate__shakeX'
        }
    });
    @endif

    function showHelp() {
        Swal.fire({
            icon: 'info',
            title: 'مساعدة في تسجيل الدخول',
            html: `
                <div class="text-right">
                    <p><strong>👨‍🎓 للطلاب:</strong></p>
                    <p>• استخدم رقم الجلوس الخاص بك</p>
                    <p>• مثال: 2024001</p>
                    <hr>
                    <p><strong>👨‍🏫 للمعلمين:</strong></p>
                    <p>• استخدم رقم الهاتف المسجل في النظام</p>
                    <p>• استخدم كلمة المرور الخاصة بك</p>
                    <hr>
                    <p class="text-muted small">إذا كنت تواجه مشكلة، يرجى التواصل مع إدارة المدرسة</p>
                </div>
            `,
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'فهمت'
        });
    }

    // إضافة تأثيرات تفاعلية
    document.addEventListener('DOMContentLoaded', function() {
        updateUI();

        // إضافة تأثير hover للبطاقة
        const card = document.querySelector('.custom-card');
        if (card) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }

        // إزالة رسائل الخطأ عند الكتابة في الحقول
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'input-error');
                const errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.style.display = 'none';
                }
            });
        });

        // تحسين تجربة المستخدم - إظهار/إخفاء كلمة المرور
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            const togglePassword = document.createElement('button');
            togglePassword.type = 'button';
            togglePassword.className = 'position-absolute end-0 top-50 translate-middle-y btn btn-link text-muted';
            togglePassword.style.background = 'transparent';
            togglePassword.style.border = 'none';
            passwordInput.parentElement.style.position = 'relative';
            passwordInput.parentElement.appendChild(togglePassword);

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            });
        }
    });
</script>

</body>
</html>
