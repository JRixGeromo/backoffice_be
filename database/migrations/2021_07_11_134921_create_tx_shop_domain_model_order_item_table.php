<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxShopDomainModelOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_shop_domain_model_order_item', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_pid')->default(0);
            $table->unsignedInteger('fe_user')->nullable()->default(0)->index('fe_user');
            $table->unsignedInteger('billing_address')->nullable()->default(0);
            $table->unsignedInteger('shipping_address')->nullable()->default(0);
            $table->string('order_number', 255)->nullable()->default('');
            $table->string('order_date', 255)->nullable()->default('');
            $table->unsignedInteger('order_pdfs')->nullable()->default(0);
            $table->string('order_status', 255)->nullable()->default('');
            $table->string('invoice_number', 255)->nullable()->default('');
            $table->unsignedInteger('invoice_date')->nullable()->default(0);
            $table->unsignedInteger('invoice_pdfs')->nullable()->default(0);
            $table->string('delivery_number', 255)->nullable()->default('');
            $table->unsignedInteger('delivery_date')->nullable()->default(0);
            $table->unsignedInteger('delivery_pdfs')->nullable()->default(0);
            $table->string('currency', 255)->nullable()->default('');
            $table->string('currency_code', 255)->nullable()->default('');
            $table->string('currency_sign', 255)->nullable()->default('');
            $table->string('currency_translation', 255)->nullable()->default('');
            $table->double('gross', 11, 2)->nullable()->default(0.00);
            $table->double('net', 11, 2)->nullable()->default(0.00);
            $table->double('total_gross', 11, 2)->nullable()->default(0.00);
            $table->double('total_net', 11, 2)->nullable()->default(0.00);
            $table->unsignedInteger('is_proposal')->nullable()->default(0);
            $table->unsignedInteger('is_taxfree')->nullable()->default(0);
            $table->unsignedInteger('shipping_same_as_billing')->nullable()->default(0);
            $table->unsignedInteger('confirmation_sent')->nullable()->default(0);
            $table->unsignedInteger('order_language')->nullable()->default(0);
            $table->unsignedInteger('tax')->nullable()->default(0);
            $table->unsignedInteger('total_tax')->nullable()->default(0);
            $table->unsignedInteger('tax_class')->nullable()->default(0);
            $table->unsignedInteger('products')->nullable()->default(0);
            $table->unsignedInteger('discounts')->nullable()->default(0);
            $table->unsignedInteger('shipping')->nullable()->default(0);
            $table->unsignedInteger('payment')->nullable()->default(0);
            $table->text('comment')->nullable();
            $table->text('additional')->nullable();
            $table->text('additional_data')->nullable();
            
            $table->string('delivery_method')->nullable()->default('');
            $table->string('crdate')->nullable()->default('');
            $table->string('accept_terms_and_conditions')->nullable()->default('');
            $table->string('accept_revocation_instruction')->nullable()->default('');
            $table->string('accept_privacy_policy')->nullable()->default('');

            $table->unsignedInteger('uid')->nullable()->default(0)->index('uid');
            $table->unsignedInteger('pid')->nullable()->default(0)->index('pid');
            $table->unsignedInteger('internal_date')->nullable()->default(0)->index('internal_date');
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
        Schema::dropIfExists('tx_shop_domain_model_order_item');
    }
}
