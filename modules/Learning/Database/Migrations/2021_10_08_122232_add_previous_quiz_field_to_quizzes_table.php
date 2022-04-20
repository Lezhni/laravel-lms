<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 *
 */
class AddPreviousQuizFieldToQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedBigInteger('previous_quiz_id')->nullable()->after('lesson_id');

            $table->foreign('previous_quiz_id')
                ->references('id')
                ->on('quizzes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropColumn('previous_quiz_id');
            Schema::enableForeignKeyConstraints();
        });
    }
}
