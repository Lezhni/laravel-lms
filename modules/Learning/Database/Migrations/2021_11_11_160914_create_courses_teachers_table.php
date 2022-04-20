<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('teacher_id');
        });

        Schema::create('courses_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses_teachers', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['teacher_id']);
        });

        Schema::dropIfExists('courses_teachers');

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->after('published');
        });
    }
}
