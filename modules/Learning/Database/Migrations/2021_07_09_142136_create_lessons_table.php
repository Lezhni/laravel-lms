<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLessonsTable
 */
class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('record_link')->nullable();
            $table->boolean('published')->default(false);
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('users')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lessons');
        Schema::enableForeignKeyConstraints();
    }
}
