<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderProductadditionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_productadditional', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0)->index('linked_id');
            $table->unsignedInteger('product')->nullable()->default(0);
            $table->string('additional_type', 255)->nullable()->default('');
            $table->string('additional_key', 255)->nullable()->default('');
            $table->text('additional_value')->nullable();
            $table->text('additional')->nullable();
            $table->text('additional_data')->nullable();
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
        Schema::dropIfExists('tx_shop_domain_model_order_productadditional');
    }
}
