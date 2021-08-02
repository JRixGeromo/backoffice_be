<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_discount', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0);
            $table->unsignedInteger('item')->nullable()->default(0);
            $table->string('title', 255)->nullable()->default('');
            $table->string('code', 255)->nullable()->default('');
            $table->string('type', 255)->nullable()->default('');
            $table->double('gross', 11, 2)->nullable()->default(0.00);
            $table->double('net', 11, 2)->nullable()->default(0.00);
            $table->unsignedInteger('tax_class_id')->nullable()->default(1);
            $table->double('tax', 11, 2)->nullable()->default(0.00);
            $table->string('exportsku', 255)->nullable()->default('');
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
        Schema::dropIfExists('tx_shop_domain_model_order_discount');
    }
}
