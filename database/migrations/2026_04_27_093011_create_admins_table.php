<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            // اسم المسؤول بالكامل
            $table->string('full_name');

            // اسم المستخدم للدخول
            $table->string('username')->unique();

            // البريد الإلكتروني للمسؤول
            $table->string('email')->unique();

            // كلمة المرور (مشفرة)
            $table->string('password');

            // المستوى الصلاحي (مثلاً: مدير عام، مراقب، محرر)
            $table->string('role')->default('super-admin');

            // رقم الهاتف للتواصل الرسمي
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
