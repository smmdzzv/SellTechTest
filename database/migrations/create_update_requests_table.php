<?php

/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 * @copyright Copyright (c) 2022, coded.tj
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_request_states', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at');
            $table->timestamp('terminated_at')->nullable();
            $table->unsignedTinyInteger('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('update_request_states');
    }
};
