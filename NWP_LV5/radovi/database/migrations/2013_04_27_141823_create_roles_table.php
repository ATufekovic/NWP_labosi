<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string("name")->unique();
            $table->string("description");
            $table->timestamps();
        });

        //nakon sto se napravi tablica, ubaci odgovarajuce uloge i objasnjenja
        if (Schema::hasTable('roles')) {
            $roleData = [
                "Admin" => "Administrator stranice",
                "Nastavnik" => "Nastavnik koji je korisnik stranice",
                "Student" => "Student koji je korisnik stranice"
            ];

            foreach ($roleData as $dataKey => $dataValue) {
                $role = new Role;

                $role->name = $dataKey;
                $role->description = $dataValue;

                $role->save();
            }
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
