<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // اسم الطالب بالكامل
            $table->string('full_name');

            // رقم الجلوس (يجب أن يكون فريداً لكل طالب)
            $table->string('seating_number')->unique();

            $table->string('class_room');

            // رقم التواصل المعتمد
            $table->string('phone_number');

            // البريد الإلكتروني (اختياري، أو يمكن استخدامه كبديل للدخول)
            $table->string('email')->unique()->nullable();


            // حالة الحساب (نشط / موقوف)
            $table->boolean('is_active')->default(true);

            // لحفظ تاريخ الإنشاء والتعديل تلقائياً
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
        Schema::dropIfExists('students');
    }
}
