<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftwaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('softwares', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('description');
            
            $table->dateTime('purchased_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->string('purchased_from');

            $table->integer('amount_cad');
            $table->integer('usd_to_cad_rate');
            $table->integer('amount_usd');
            $table->enum('billing_cycle', [
                'onetime',
                'monthly',
                'yearly',
            ]);
            
            $table->text('license_information')->nullable();
            
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
        Schema::drop('softwares');
    }
}
