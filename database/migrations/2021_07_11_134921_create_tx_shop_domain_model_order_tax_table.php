<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_tax', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0)->index('linked_id');
            $table->unsignedInteger('item')->nullable()->default(0);
            $table->double('tax', 11, 2)->nullable()->default(0.00);
            $table->unsignedInteger('tax_class')->nullable()->default(0);
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
        Schema::dropIfExists('tx_shop_domain_model_order_tax');
    }
}
