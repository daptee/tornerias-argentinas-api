<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications_questions_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->references('id')->on('publications');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('ask', 400);
            $table->dateTime('ask_date');
            $table->string('answer', 400)->nullable();
            $table->dateTime('answer_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications_questions_answers');
    }
};
