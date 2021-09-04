<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0)->index('linked_id');
            $table->string('record_type', 255)->nullable()->default('');
            $table->string('item', 255)->nullable()->default('');
            $table->string('title', 255)->nullable()->default('');
            $table->string('salutation', 255)->nullable()->default('');
            $table->string('first_name', 255)->nullable()->default('');
            $table->string('last_name', 255)->nullable()->default('');
            $table->string('name', 255)->nullable()->default('');
            $table->string('email', 255)->nullable()->default('');
            $table->string('phone', 255)->nullable()->default('');
            $table->string('fax', 255)->nullable()->default('');
            $table->string('company', 255)->nullable()->default('');
            $table->string('department', 255)->nullable()->default('');
            $table->string('tax_identification_number', 255)->nullable()->default('');
            $table->string('street', 255)->nullable()->default('');
            $table->string('street_number', 255)->nullable()->default('');
            $table->string('zip', 255)->nullable()->default('');
            $table->string('city', 255)->nullable()->default('');
            $table->string('country', 255)->nullable()->default('');
            $table->text('additional')->nullable();
            
            $table->string('is_tax_free')->nullable()->default('');
            $table->string('wants_newsletter')->nullable()->default('');
            
            $table->unsignedInteger('uid')->nullable()->default(0)->index('uid');
            $table->unsignedInteger('pid')->nullable()->default(0)->index('pid');
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
        Schema::dropIfExists('tx_shop_domain_model_order_address');
    }
}
