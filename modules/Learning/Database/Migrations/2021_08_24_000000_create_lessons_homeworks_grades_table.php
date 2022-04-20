<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsHomeworksGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons_homeworks_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('homework_id')->index();
            $table->unsignedInteger('grade')->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('homework_id')
                ->references('id')
                ->on('lessons_homeworks')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lessons_homeworks_grades');
        Schema::enableForeignKeyConstraints();
    }
}
