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

            $table->double('amount_usd', 8, 2)->nullable();
            $table->double('usd_to_cad_rate', 9, 7)->nullable();
            $table->double('amount_cad', 8, 2)->nullable();

            $table->dateTime('generated_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->integer('project_id');
            $table->integer('client_id');

            $table->integer('invoice_no');

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
