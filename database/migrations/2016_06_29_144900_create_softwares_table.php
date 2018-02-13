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
            $table->dateTime('cancelled_at');
            $table->string('purchased_from');

            $table->double('amount_cad', 6, 2);
            $table->double('usd_to_cad_rate', 9, 7);
            $table->double('amount_usd', 6, 2);
            $table->enum('billing_cycle', [
                'onetime',
                'monthly',
                'yearly',
            ]);
            
            $table->text('license_information');
            
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
