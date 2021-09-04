<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0)->index('linked_id');
            $table->string('item', 255)->nullable()->default('');
            $table->string('addtional', 255)->nullable()->default('');
            $table->string('service_country', 255)->nullable()->default('');
            $table->integer('service_id')->nullable()->default(0);
            $table->string('name', 255)->nullable()->default('');
            $table->string('provider', 255)->nullable()->default('');
            $table->string('status', 255)->nullable()->default('0');
            $table->double('gross', 11, 2)->nullable()->default(0.00);
            $table->double('net', 11, 2)->nullable()->default(0.00);
            $table->double('tax', 11, 2)->nullable()->default(0.00);
            $table->unsignedInteger('tax_class')->nullable()->default(0);
            $table->text('note')->nullable();
            $table->unsignedInteger('transactions')->nullable()->default(0);
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
        Schema::dropIfExists('tx_shop_domain_model_order_payment');
    }
}
