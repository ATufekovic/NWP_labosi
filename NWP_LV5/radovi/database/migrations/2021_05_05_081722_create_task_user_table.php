<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_user', function (Blueprint $table) {
            $table->integer("user_id")->unsigned()->index();
            $table->integer("task_id")->unsigned()->index();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("task_id")->references("id")->on("tasks")->onDelete("cascade");
            $table->primary(array("user_id", "task_id"));

            $table->enum("order", ["1", "2", "3", "4", "5"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_user');
    }
}
