{{--
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
--}}
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/font/Tajawal.css') }}" rel="stylesheet">

    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #f0f2f5; }

        /* تأثير ظهور الرسالة */
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .error-alert {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

<section class="w-full max-w-md">

    <div class="text-center mb-6">
        <img src="https://via.placeholder.com/100"
             class="mx-auto mb-3 w-20 h-20 rounded-full shadow-lg">

        <h1 class="text-2xl font-bold text-indigo-700">
            مدرسة Learn To Be
        </h1>

        <p class="text-gray-500 text-sm">
            نظام الاختبارات
        </p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="bg-indigo-700 p-6 text-center text-white">
            <h2 class="text-2xl font-bold">تسجيل الدخول</h2>
        </div>

        <form method="POST" action="{{ route('Admin.login') }}" class="p-6">
            @csrf

            <!-- رسالة الخطأ المحسنة -->
            @if ($errors->any())
                <div class="error-alert mb-4 p-4 bg-red-50 border-r-4 border-red-500 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <h3 class="text-red-800 font-bold text-sm mb-1">
                                فشل تسجيل الدخول
                            </h3>
                            <p class="text-red-600 text-sm">
                                {{ $errors->first() }}
                            </p>
                        </div>
                        <button type="button" class="flex-shrink-0" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times text-red-400 hover:text-red-600 transition"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Email مع تحسين عرض الخطأ على الحقل -->
            <div class="mb-3">
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="البريد الإلكتروني"
                       class="w-full p-3 bg-gray-50 rounded-2xl @error('email') border-2 border-red-500 bg-red-50 @enderror"
                       required autofocus>
                @error('email')
                <p class="text-red-500 text-xs mt-1 mr-2">
                    <i class="fas fa-exclamation-circle ml-1"></i>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <input type="password"
                       name="password"
                       placeholder="كلمة المرور"
                       class="w-full p-3 bg-gray-50 rounded-2xl @error('password') border-2 border-red-500 bg-red-50 @enderror"
                       required>
            </div>

            <!-- Remember -->
            <label class="flex items-center text-sm mb-3">
                <input type="checkbox" name="remember" class="ml-2">
                تذكرني
            </label>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-2xl font-bold transition duration-200">
                دخول
            </button>

        </form>
    </div>

</section>

<script>
    // إخفاء الرسالة تلقائياً بعد 5 ثوانٍ
    setTimeout(function() {
        const errorAlert = document.querySelector('.error-alert');
        if (errorAlert) {
            errorAlert.style.opacity = '0';
            setTimeout(function() {
                if (errorAlert.parentElement) {
                    errorAlert.remove();
                }
            }, 300);
        }
    }, 5000);
</script>

</body>
</html>
