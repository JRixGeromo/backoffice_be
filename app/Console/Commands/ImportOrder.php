<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Custom\Helper;
use App\Models\OrderImportTracker;

class ImportOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve orders from zotter api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $helper = new Helper();
        $url = "https://zotterdev.developer.at/?type=2289002";
        $maxOrderNumber = OrderImportTracker::max('order_number');
        $start = 1;
        if((int)$maxOrderNumber > 0) {
            $start = $maxOrderNumber;
            $url = "https://zotterdev.developer.at/?type=2289002&tx_devshopfeed_xmlfeed[ordernumber]=".$start."&tx_devshopfeed_xmlfeed[limitorders]=200";
        } else {
            $url = "https://zotterdev.developer.at/?type=2289002&tx_devshopfeed_xmlfeed[ordernumber]=".$start."&tx_devshopfeed_xmlfeed[limitorders]=200";
        }
        $result = $helper->consumeAPIClient($url, true);  // for XML REST
        if($result) {
            $result = $helper->importOrderTracker($result);  // store ORDERS for import
        }
        
        $forImportOrders = OrderImportTracker::where('import_status', 0)->limit(200)->get()->toArray();

        foreach ($forImportOrders as $order) { // for uid REST
            $url = "https://zotterdev.developer.at/rest/shop_item/".$order['uid'];
            $result = $helper->consumeAPIClient($url, false);  // for REST
            if($result) {
                $result = $helper->importOrderByUID($result, $order['id']);  // import ORDER
            }
        }
        $this->info('Successfully imported orders.');
    }
}
