<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id');

            $table->dateTime('received_at');
            $table->string('cheque_number');

            $table->double('amount_usd', 8, 2)->nullable();
            $table->double('usd_to_cad_rate', 9, 7)->nullable();
            $table->double('amount_cad', 8, 2)->nullable();

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
        Schema::drop('payments');
    }
}
