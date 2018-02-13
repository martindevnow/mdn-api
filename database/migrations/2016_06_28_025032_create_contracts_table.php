<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id');

            $table->double('programming_hourly_rate', 6, 2);
            $table->double('sysadmin_hourly_rate', 6, 2);
            $table->double('consulting_hourly_rate', 6, 2);

            $table->dateTime('activated_at');
            $table->dateTime('deactivated_at')->nullable();
            $table->dateTime('valid_from_date');
            $table->dateTime('valid_until_date')->nullable();

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
        Schema::drop('contracts');
    }
}
