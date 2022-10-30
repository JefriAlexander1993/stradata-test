<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonPublicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_publics', function (Blueprint $table) {
            $table->id();
            $table->string('department',60);
            $table->string('location',60);
            $table->string('municipality',60);
            $table->string('name',100);
            $table->string('active_years',60);
            $table->string('person_type',40);
            $table->string('type_of_load',40);
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
        Schema::dropIfExists('person_publics');
    }
}
