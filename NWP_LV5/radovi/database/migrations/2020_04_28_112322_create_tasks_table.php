<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string("naziv_rada");
            $table->string("naziv_rada_engleski")->nullable();
            $table->string("zadatak_rada");
            $table->enum("tip_studija", ["stručni", "preddiplomski", "diplomski"]);

            $table->integer("creator_id")->unsigned()->index();
            $table->foreign("creator_id")->references("id")->on("users");

            $table->integer("student_id")->unsigned()->index()->nullable();
            $table->foreign("student_id")->references("id")->on("users");

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
        Schema::dropIfExists('tasks');
    }
}
