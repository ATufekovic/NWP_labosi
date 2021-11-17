<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->integer("leader_user_id")->unsigned()->index();
            $table->foreign("leader_user_id")->references("id")->on("users");
            $table->string("naziv_projekta");
            $table->string("opis_projekta");
            $table->decimal("cijena_projekta", 16, 2);
            $table->string("obavljeni_poslovi");
            $table->dateTime("datum_pocetka");
            $table->dateTime("datum_zavrsetka")->nullable();
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
        Schema::dropIfExists('projects');
    }
}
