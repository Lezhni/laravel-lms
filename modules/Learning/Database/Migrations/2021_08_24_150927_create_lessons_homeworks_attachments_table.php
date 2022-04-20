<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsHomeworksAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons_homeworks_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id')->index();
            $table->string('filename');

            $table->foreign('message_id')
                ->references('id')
                ->on('lessons_homeworks_messages')
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
        Schema::dropIfExists('lessons_homeworks_attachments');
        Schema::enableForeignKeyConstraints();
    }
}
