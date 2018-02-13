<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id');
            $table->integer('invoice_id')->nullable();

            $table->integer('chargeable_id');
            $table->string('chargeable_type');

            $table->double('rate', 6, 2);
            $table->double('quantity', 6, 2);
            $table->double('total_cost', 7, 2);

            $table->dateTime('billable_as_of');
            $table->dateTime('billed_at')->nullable();

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
        Schema::dropIfExists('charges');
    }
}
