<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTtAddressShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tt_address_shop', function (Blueprint $table) {
            $table->unsignedInteger('fe_user')->nullable()->default(0)->index('fe_user');
            $table->string('address_type', 255)->default('');
            $table->string('salutation', 255)->default('');
            $table->string('department', 255)->default('');
            $table->string('tax_identification_number', 255)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tt_address_shop');
    }
}
