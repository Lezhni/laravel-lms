<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes_results', function (Blueprint $table) {
            $table->id('id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('question_id')->index();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('question_id')
                ->references('id')
                ->on('quizzes_questions')
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
        Schema::dropIfExists('quizzes_results');
        Schema::enableForeignKeyConstraints();
    }
}
