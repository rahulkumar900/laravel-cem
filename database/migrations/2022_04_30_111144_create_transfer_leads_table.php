<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_leads', function (Blueprint $table) {
            $table->id();
            $table->string('transfered_from');
            $table->string('transfered_to');
            $table->string('transfered_by');
            $table->dateTime('transfered_on');
            $table->json('transfered_leads');
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
        Schema::dropIfExists('transfer_leads');
    }
}
