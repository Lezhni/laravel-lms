<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->string('name')->index();
            $table->boolean('is_correct')->default(false);

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
        Schema::dropIfExists('quizzes_answers');
        Schema::enableForeignKeyConstraints();
    }
}
