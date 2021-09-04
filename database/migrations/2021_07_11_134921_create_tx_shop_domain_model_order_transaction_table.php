<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('linked_id')->nullable()->default(0)->index('linked_id');
            $table->unsignedInteger('payment')->nullable()->default(0);
            $table->string('provider', 20)->nullable()->default('');
            $table->string('transaction_id', 255)->nullable()->default('');
            $table->text('transaction_txt')->nullable();
            $table->string('status', 255)->nullable()->default('unknown');
            $table->string('external_status_code', 255)->nullable()->default('');
            $table->text('note')->nullable()->nullable();
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
        Schema::dropIfExists('tx_shop_domain_model_order_transaction');
    }
}
