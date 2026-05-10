<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            // اسم المعلم بالكامل
            $table->string('full_name');

            // التخصص الأكاديمي (مثلاً: رياضيات، فيزياء)
            $table->string('subject_specialization');


            // كلمة المرور (مشفرة)
            $table->string('password');

            // رقم الهاتف للتواصل
            $table->string('phone_number')->nullable();

            // حالة الحساب (نشط / موقوف)
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('teachers');
    }
}
