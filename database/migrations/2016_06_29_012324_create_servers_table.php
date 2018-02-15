<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('host');
            $table->string('os');
            $table->string('username');
            $table->string('email');

            $table->dateTime('purchased_at');
            $table->dateTime('expires_at');

            $table->integer('cost_monthly');
            $table->enum('currency', [
                'USD',
                'CAD',
                'JPY',
            ]);
            $table->enum('billing_cycle', [
                'yearly',
                'monthly'
            ]);

            $table->boolean('active')->default(0);

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
        Schema::drop('servers');
    }
}
