<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الاختبارات الثانوية المتكاملة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');
        body { font-family: 'Tajawal', sans-serif; background-color: #f0f2f5; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-bg { background-color: rgba(0, 0, 0, 0.5); }
    </style>
</head>
<body class="text-gray-800 min-h-screen flex items-center justify-center px-4">

<section class="w-full max-w-md fade-in">

    <!-- Logo + Welcome -->
    <div class="text-center mb-6">
        <img src="https://via.placeholder.com/100x100.png?text=Logo"
             class="mx-auto mb-3 w-20 h-20 rounded-full shadow-lg"
             alt="School Logo">

        <h1 class="text-2xl font-bold text-indigo-700">
            مدرسة Learn To Be
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            نظام إدارة الاختبارات الثانوية المتكامل
        </p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-indigo-700 p-6 sm:p-8 text-center text-white">
            <h2 class="text-2xl sm:text-3xl font-bold">تسجيل الدخول</h2>
            <p class="text-indigo-200 mt-2 text-sm sm:text-base">
                اختر نوع الحساب للدخول إلى النظام
            </p>
        </div>
        <!-- Role -->
        <select name="role" id="role" onchange="updateUI()"
                class="w-full p-3 sm:p-4 bg-gray-50 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="student">طالب</option>
            <option value="teacher">معلم</option>
            <option value="admin">إدارة</option>
        </select>
        <!-- Form -->
        <form style="padding: 15px" method="POST" action="{{ route('login') }}">
            @csrf


            <!-- Identifier -->
            <input name="identifier" id="identifier"
                   class="w-full p-3 sm:p-4 bg-gray-50 rounded-2xl mt-3"
                   placeholder="رقم الجلوس">

            <!-- Password -->
            <input type="password" name="password" id="password"
                   class="w-full p-3 sm:p-4 bg-gray-50 rounded-2xl mt-3 hidden"
                   placeholder="كلمة المرور">

            <!-- Button -->
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 mt-4 text-white py-3 rounded-2xl font-bold">
                دخول
            </button>
        </form>
    </div>

    <!-- Footer -->
    <p class="text-center text-xs text-gray-400 mt-4">
        © 2026 Learn To Be School - جميع الحقوق محفوظة
    </p>

</section>
<!-- تذييل الصفحة -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateUI() {
        let role = document.getElementById('role').value;
        let pass = document.getElementById('password');
        let input = document.getElementById('identifier');

        if (role === 'student') {
            pass.classList.add('hidden');
            input.placeholder = 'رقم الجلوس';
        } else {
            pass.classList.remove('hidden');
            input.placeholder = 'البريد الإلكتروني';
        }
    }

</script>
</body>
</html>
