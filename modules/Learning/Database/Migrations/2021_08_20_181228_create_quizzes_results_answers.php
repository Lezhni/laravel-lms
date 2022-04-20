<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesResultsAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes_results_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('quiz_result_id')->index();
            $table->unsignedBigInteger('answer_id')->index();

            $table->foreign('quiz_result_id')
                ->references('id')
                ->on('quizzes_results')
                ->onDelete('cascade');

            $table->foreign('answer_id')
                ->references('id')
                ->on('quizzes_answers')
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
        Schema::dropIfExists('quizzes_results_answers');
    }
}
