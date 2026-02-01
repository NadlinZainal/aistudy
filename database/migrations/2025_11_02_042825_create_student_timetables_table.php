<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_timetables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable();
            $table->foreignId('subject_id')->nullable();
            $table->foreignId('day_id')->nullable();
            $table->foreignId('hall_id')->nullable();
            $table->foreignId('lecturer_group_id')->nullable();

            $table->string('time_from')->nullable();
            $table->string('time_to')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_timetables');
    }
};
