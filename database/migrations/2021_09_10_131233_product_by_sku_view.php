<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductBySkuView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement($this->createView());
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement($this->dropView());
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function createView(): string
    {
        return <<<SQL
            CREATE OR REPLACE VIEW product_by_sku_view AS
                SELECT 
                    `linked_id`,
                    `sku`,
                    COUNT(`product_type`) as product_type_total,
                    COUNT(`sku`) as sku_total,
                    COUNT(`title`) as title_total,
                    SUM(`count`) as count,
                    SUM(`price`) as price,
                    SUM(`discount`) as discount,
                    SUM(`gross`) as gross,
                    SUM(`net`) as net
                FROM `tx_shop_domain_model_order_product`
                GROUP BY `linked_id`, `sku`
            SQL;
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return <<<SQL

            DROP VIEW IF EXISTS `product_by_sku_view`;
            SQL;
    }
}
