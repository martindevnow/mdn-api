<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function(Blueprint $table)
        {
            $table->increments('id');

            $table->boolean('active')->default(true);
            $table->string('name')->default('main');
            $table->string('description')->nullable();
            $table->string('company')->nullable();

            $table->string('street_1');
            $table->string('street_2')->nullable();
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('country');

            // extras
            $table->string('phone')->nullable();
            $table->string('buzzer')->nullable();

            // polymorphic relations
            $table->integer('addressable_id')->nullable();
            $table->string('addressable_type')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addresses');
    }

}
