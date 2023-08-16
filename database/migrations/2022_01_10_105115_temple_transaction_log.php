<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TempleTransactionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temple_transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->double('debit_amt',12,2)->unsigned()->default(0)->comment("amount which user is receiving")->nullable();
            $table->double('credit_amt', 12, 2)->unsigned()->default(0)->comment("amount whihc user is giving to someone")->nullable();
            $table->double('closing_balance', 12, 2)->unsigned()->default(0)->comment("closing balance of the user");
            $table->string('rec_temple_id');
            $table->string('handover_to')->nullable();
            $table->string('narration')->nullable();
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
        //
    }
}
