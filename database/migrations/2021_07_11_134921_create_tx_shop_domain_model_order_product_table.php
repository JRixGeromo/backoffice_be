<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0);
            $table->string('item', 255)->nullable()->default('');
            $table->string('product_type', 255)->nullable()->default('');
            $table->string('sku', 255)->nullable()->default('');
            $table->string('title', 255)->nullable()->default('');
            $table->integer('count')->nullable()->default(0);
            $table->double('price', 11, 2)->nullable()->default(0.00);
            $table->double('discount', 11, 2)->nullable()->default(0.00);
            $table->double('gross', 11, 2)->nullable()->default(0.00);
            $table->double('net', 11, 2)->nullable()->default(0.00);
            $table->double('tax', 11, 2)->nullable()->default(0.00);
            $table->unsignedInteger('tax_class')->nullable()->default(0);
            $table->text('additional')->nullable();
            $table->text('additional_data')->nullable();
            $table->unsignedInteger('product_additional')->nullable()->default(0);
            $table->string('shop_product')->nullable()->default('');
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
        Schema::dropIfExists('tx_shop_domain_model_order_product');
    }
}
