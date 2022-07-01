<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_questions', function (Blueprint $table) {

            $table->id();
            $table->string('title');
            $table->integer('client_id')->unsigned()->nullable();
            $table->string('description');
            $table->integer('feedback_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')
            ->onDelete('cascade');
            $table->foreign('feedback_id')->references('id')->on('feedbacks')
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
        Schema::dropIfExists('client_questions');
    }
}
