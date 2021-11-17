<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->string("filename");
            $table->string("extension");
            $table->unsignedInteger("size");
            $table->string("MIME");
            $table->string("visibility")->default("public");
            
            $table->string("cipher");
            $table->string("iv");
            $table->string("tag");
            
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");

            $table->timestamps();
        });

        Schema::create('file_data', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
        });

        Schema::table("file_data", function(Blueprint $table){
            $table->foreignId("file_id")->nullable()->references("id")->on("files")->onDelete("cascade");
        });

        DB::statement("ALTER TABLE file_data ADD data MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_data');
    }
}
