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
<body class="text-gray-800">

<!-- شريط التنقل العلوي -->
<nav id="main-nav" class="bg-indigo-800 text-white shadow-xl ">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <i class="fas fa-university text-2xl"></i>
            <span class="text-xl font-bold">بوابة التميز التعليمية</span>
        </div>
        <div class="flex items-center gap-6">
            <span id="user-info" class="text-sm border-l pl-4  md:inline">مرحباً بك في النظام</span>
            <button onclick="showSection('login')" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-sign-out-alt"></i> خروج
            </button>
        </div>
    </div>
</nav>

<div class="container mx-auto p-4 mt-6">
    @yield('content')
    <!-- 1. واجهة تسجيل الدخول -->


    <!-- 2. واجهة الطالب -->

    <!-- 3. واجهة المعلم -->

    <!-- 4. واجهة الإدارة -->

</div>

<!-- نافذة إضافة طالب (Modal) -->
<div id="studentModal" class="fixed inset-0 modal-bg z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden fade-in">
        <div class="bg-indigo-700 p-6 text-white flex justify-between items-center">
            <h3 class="text-xl font-bold">إضافة طالب جديد للنظام</h3>
            <button onclick="toggleModal('studentModal', false)"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-8 space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">اسم الطالب بالكامل</label>
                <input type="text" id="new-student-name" placeholder="مثال: يوسف خالد محمد" class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-indigo-500 outline-none transition">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجلوس</label>
                    <input type="text" id="new-student-id" placeholder="100XXXX" class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-indigo-500 outline-none transition font-mono">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">رقم التواصل</label>
                    <input type="text" id="new-student-phone" placeholder="05XXXXXXXX" class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-indigo-500 outline-none transition font-mono">
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button onclick="saveStudent()" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
                    حفظ بيانات الطالب
                </button>
                <button onclick="toggleModal('studentModal', false)" class="px-6 py-4 text-gray-500 font-bold hover:bg-gray-100 rounded-2xl transition">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>

<!-- تذييل الصفحة -->
<footer class="mt-20 py-8 text-center text-gray-400 text-sm border-t border-gray-200">
    <p>&copy; 2024 نظام إدارة الاختبارات المدرسية المطوّر - جميع الحقوق محفوظة</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


    // فتح وإغلاق النوافذ (Modals)
    function toggleModal(id, show) {
        const modal = document.getElementById(id);
        if(show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    // حفظ الطالب وإضافته للجدول (معاينة)
    function saveStudent() {
        const name = document.getElementById('new-student-name').value;
        const id = document.getElementById('new-student-id').value;
        const phone = document.getElementById('new-student-phone').value;

        if(!name || !id || !phone) {
            alert("يرجى ملء كافة الحقول");
            return;
        }

        const tbody = document.getElementById('students-table-body');
        const newRow = `
                <tr class="hover:bg-gray-50 transition fade-in">
                    <td class="p-4 font-bold text-gray-900">${name}</td>
                    <td class="p-4 text-indigo-600 font-mono">${id}</td>
                    <td class="p-4 font-mono text-sm">${phone}</td>
                    <td class="p-4"><span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold">نشط</span></td>
                    <td class="p-4 flex gap-2">
                        <button class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:bg-red-50 p-2 rounded-lg"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
        tbody.insertAdjacentHTML('afterbegin', newRow);

        // تنظيف الحقول وإغلاق المودال
        document.getElementById('new-student-name').value = '';
        document.getElementById('new-student-id').value = '';
        document.getElementById('new-student-phone').value = '';
        toggleModal('studentModal', false);
    }

    // تغيير تسمية الحقل بناءً على نوع الدخول
    document.getElementById('role-select').addEventListener('change', function(e) {
        const label = document.getElementById('id-label');
        if(e.target.value === 'student') {
            label.innerText = 'رقم الجلوس';
        } else {
            label.innerText = 'البريد الإلكتروني / اسم المستخدم';
        }
    });
</script>
</body>
</html>
