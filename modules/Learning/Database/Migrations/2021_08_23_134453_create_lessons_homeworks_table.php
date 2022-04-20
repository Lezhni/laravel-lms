<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons_homeworks', function (Blueprint $table) {
            $table->id()->index();
            $table->boolean('published')->default(true);
            $table->unsignedBigInteger('lesson_id')->unique();
            $table->mediumText('content')->nullable();
            $table->unsignedInteger('max_grade')->default(0);

            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
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
        Schema::dropIfExists('lessons_homeworks');
        Schema::enableForeignKeyConstraints();
    }
}
