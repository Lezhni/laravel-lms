<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsHomeworksMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons_homeworks_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->index();
            $table->unsignedBigInteger('grade_id')->index();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('grade_id')
                ->references('id')
                ->on('lessons_homeworks_grades')
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
        Schema::dropIfExists('lessons_homeworks_messages');
        Schema::enableForeignKeyConstraints();
    }
}
