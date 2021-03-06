<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id');
            $table->integer('invoice_no');

            $table->integer('amount_usd')->nullable();
            $table->integer('usd_to_cad_rate')->nullable();
            $table->integer('amount_cad')->nullable();

            $table->dateTime('generated_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('paid_at')->nullable();


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
        Schema::drop('invoices');
    }
}
