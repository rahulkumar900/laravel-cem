<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRequestLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_request_leads', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('lead_id');
            $table->enum('lead_status', ['picked_up', 'rejected']);
            $table->tinyInteger('lead_type')->comment('0 = website leads, 1 = facebook leads,  2 = data acount, 3 = crm leads,  4 = converted leads, 5 = website leads (subscription view),  6 =, 7 = incomplete leads channel1, 8 = incomplete leads data channel');
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
        Schema::dropIfExists('user_request_leads');
    }
}
