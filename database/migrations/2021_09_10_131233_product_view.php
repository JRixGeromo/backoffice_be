<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductView extends Migration
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
            CREATE VIEW product_view AS
                SELECT 
                    `linked_id`,
                    SUM(`count`) as count,
                    SUM(`price`) as price,
                    SUM(`discount`) as discount,
                    SUM(`gross`) as gross,
                    SUM(`net`) as net
                FROM `tx_shop_domain_model_order_product`
                GROUP BY `linked_id`
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

            DROP VIEW IF EXISTS `product_view`;
            SQL;
    }
}
