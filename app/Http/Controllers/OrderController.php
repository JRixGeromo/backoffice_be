<?php

namespace App\Http\Controllers;

use App\Models\FeUser;
use App\Models\OrderImportTracker;
use App\Models\TxShopDomainModelOrderItem;
use Illuminate\Http\Request;
use App\Http\Custom\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $helper = new Helper();
        $url = "https://zotterdev.developer.at/?type=2289002";
        $maxOrderNumber = OrderImportTracker::max('order_number');
        if((int)$maxOrderNumber > 0) {
            $url = "https://zotterdev.developer.at/?type=2289002&tx_devshopfeed_xmlfeed[ordernumber]=".$maxOrderNumber;
        }
        sleep(1);
        
        $result = $helper->consumeAPIClient($url, true);  // for XML REST
        if($result) {
            $result = $helper->importOrderTracker($result);  // store ORDERS for import
        }
        //$forImportOrders = OrderImportTracker::where('import_status', 0)->limit(100)->get()->toArray();
        $forImportOrders = OrderImportTracker::where('import_status', 0)->get()->toArray();
        foreach ($forImportOrders as $order) { // for uid REST
            $url = "https://zotterdev.developer.at/rest/shop_item/".$order['uid'];
            $result = $helper->consumeAPIClient($url, false);  // for REST
            if($result) {
                //sleep(1);
                $result = $helper->importOrderByUID($result, $order['id']);  // import ORDER
            }
            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TxShopDomainModelOrderItem  $order
     * @return \Illuminate\Http\Response
     */
    public function show(TxShopDomainModelOrderItem $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TxShopDomainModelOrderItem  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(TxShopDomainModelOrderItem $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TxShopDomainModelOrderItem  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TxShopDomainModelOrderItem $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TxShopDomainModelOrderItem  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(TxShopDomainModelOrderItem $order)
    {
        //
    }

    public function analytics($type)
    {
        $data = array();
        $fromYear = strtotime('2020/01/01');
        $toYear = strtotime('2020/12/31');
        if($type=='overview') {
            $data['sales'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y-%m-%d') as date, 
                    SUM(tx_shop_domain_model_order_product.count) as items_sold,
                    SUM(tx_shop_domain_model_order_item.total_net) as net_sales"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('date')
                ->get();

        } else if($type=='overview_summary') {
            
            $data['summary'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.total_net) as total_total_net,
                    SUM(tx_shop_domain_model_order_item.net) as total_net,
                    COUNT(tx_shop_domain_model_order_item.order_number) as total_orders,
                    SUM(tx_shop_domain_model_order_product.count) as total_items_sold,
                    213213 as total_black"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                //->groupBy('date')
                ->get();
            
            $data['percent'] = array("total_total_net"=>89,
                                    "total_net"=>54,
                                    "total_orders"=>67,
                                    "total_items_sold"=>78,
                                    "total_black"=>78
                                    );        

        } else if($type=='products') {

            $data['sales'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y-%m-%d') as date, 
                    SUM(tx_shop_domain_model_order_product.count) as items_sold,
                    SUM(tx_shop_domain_model_order_item.total_net) as net_sales"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('date')
                ->get();

        } else if($type=='products_summary') {
            $data['summary'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_product.count) as total_items_sold,
                    COUNT(tx_shop_domain_model_order_item.order_number) as total_orders,
                    SUM(tx_shop_domain_model_order_item.total_net) as total_net_sales"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                //->groupBy('date')
                ->get();

            $data['percent'] = array("orders"=>67,
                                    "net_sales"=>89,
                                    "item_sold"=>18,
                                    );
                                    
        } else if($type=='orders') {

            $data['sales'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y-%m-%d') as date, 
                    SUM(tx_shop_domain_model_order_product.count) as orders,
                    SUM(tx_shop_domain_model_order_item.total_net) as net_sales"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('date')
                ->get();


        } else if($type=='orders_summary') {
            $data['summary'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.net) as net_sales,
                    COUNT(tx_shop_domain_model_order_item.order_number) as total_orders,
                    SUM(tx_shop_domain_model_order_product.count) as total_items_sold,
                    SUM(tx_shop_domain_model_order_item.total_net) as total_net_sales"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                //->groupBy('date')
                ->get();

                // Ave order value =  total_net_sales / total_orders
                // Ave items per order =  total_items_sold / total_orders

            $data['percent'] = array("orders"=>67,
                                "net_sales"=>89,
                                "ave_order_value"=>54,
                                "ave_item_per_order"=>78
                                );

        } else if($type=='revenue') {

            $data['sales'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%m-%d') as ymd, 
                    DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%m-%d') as md, 
                    DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y') as y, 
                    SUM(tx_shop_domain_model_order_product.count) as items_sold,
                    SUM(tx_shop_domain_model_order_item.total_net) as net_sales"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('ymd', 'md', 'y')
                ->get();
            
        } else if($type=='revenue_summary') {
            $data['summary'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.net) as total_net,
                    SUM(tx_shop_domain_model_order_item.gross) as total_gross_sales,
                    SUM(tx_shop_domain_model_order_item.total_net) as total_net_sales,
                    SUM(tx_shop_domain_model_order_item.total_gross) as total_gross_sales,
                    34324 as total_taxes,
                    54634 as total_shipping,
                    232 as total_coupons,
                    564544 as total_returns,
                    324323 as total_sales
                    "
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                //->groupBy('date')
                ->get();
            
            $data['percent'] = array("gross_sales"=>67,
                                "returns"=>89,
                                "coupons"=>54,
                                "net_sales"=>78,
                                "taxes"=>81,
                                "shipping"=>70,
                                "total_sales"=>78
                                );
                                
        } else if($type=='customer') {
            $data['customer_info'] = FeUser::query()->select("*")->limit(1)->get();
            $data['orders'] = TxShopDomainModelOrderItem::query()
                ->select('*')
                ->limit(6)
                ->get();
            $data['viewed_products'] = TxShopDomainModelOrderItem::query()
                ->select('*')
                ->limit(6)
                ->get();
            $data['message'] = 'This is a test message';
            $data['voucher'] = 'This is a test voucher';
            $data['last_emails'] = TxShopDomainModelOrderItem::query()
                ->select('*')
                ->limit(6)
                ->get();
            $data['groups'] = TxShopDomainModelOrderItem::query()
                ->select('*')
                ->limit(6)
                ->get();

        } else {
            $top = $this->top($type, $fromYear, $toYear);    
            $data = array_merge($data, $top); 
        }       
        
        return $data;
    }
    
    private function top($type, $fromYear, $toYear)
    {
        
        if($type == 'top_countries') {
            $data['top_countries'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_address', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_address.linked_id')
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.total_gross) as total_sales,
                    SUM(tx_shop_domain_model_order_product.count) as orders,
                    tx_shop_domain_model_order_address.country as country"
                    )
                //->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('country')
                ->orderBy('orders', 'DESC')
                ->limit(6)
                ->get();
        } else if ($type == 'top_customers') {
            $data['top_customers'] = TxShopDomainModelOrderItem::query()
                ->join('fe_users', 'tx_shop_domain_model_order_item.fe_user', '=', 'fe_users.id')
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.total_gross) as total_sales,
                    SUM(tx_shop_domain_model_order_product.count) as orders,
                    fe_users.email as customer"
                )
                //->where('tx_shop_domain_model_order_item.order_status', '=', 'transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('customer')
                ->orderBy('total_sales', 'DESC')
                ->limit(6)
                ->get();
        } else if ($type == 'top_categories') {
            $data['top_categories'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.total_gross) as total_sales,
                    SUM(tx_shop_domain_model_order_product.count) as orders,
                    tx_shop_domain_model_order_product.product_type as product_type"
                )
                //->where('tx_shop_domain_model_order_item.order_status', '=', 'transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('product_type')
                ->orderBy('total_sales', 'DESC')
                ->limit(6)
                ->get();
        } else if ($type == 'top_products') {
            $data['top_products'] = TxShopDomainModelOrderItem::query()
                ->join('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
                ->selectRaw(
                    "SUM(tx_shop_domain_model_order_item.total_gross) as total_sales,
                    SUM(tx_shop_domain_model_order_product.count) as orders,
                    tx_shop_domain_model_order_product.title as title"
                )
                //->where('tx_shop_domain_model_order_item.order_status', '=', 'transferred')
                ->whereBetween('tx_shop_domain_model_order_item.internal_date', [$fromYear, $toYear])
                ->groupBy('title')
                ->orderBy('total_sales', 'DESC')
                ->limit(6)
                ->get();
        }    
        
        return $data;        
            
    }
    

    public function dateGrouping()
    {
        
        
        $begin = Carbon::parse("2018-01-01");
        $end = Carbon::parse("2018-12-31");

        $d1_2018 = Carbon::parse("2018-01-01");
        $d1_2019 = Carbon::parse("2019-01-01");
        $d1_2020 = Carbon::parse("2020-01-01");
        $d1_2021 = Carbon::parse("2021-01-01");
        $d1_2022 = Carbon::parse("2022-01-01");
        $d1_2023 = Carbon::parse("2023-01-01");
        $d1_2024 = Carbon::parse("2024-01-01");
       
        $d7_2018 = Carbon::parse("2018-01-01");
        $d7_2019 = Carbon::parse("2019-01-01");
        $d7_2020 = Carbon::parse("2020-01-01");
        $d7_2021 = Carbon::parse("2021-01-01");
        $d7_2022 = Carbon::parse("2022-01-01");
        $d7_2023 = Carbon::parse("2023-01-01");
        $d7_2024 = Carbon::parse("2024-01-01");
        
        $i7 = 1;

        for($i = $begin; $i <= $end; $i->addDays(1)){
            DB::insert("INSERT INTO date_grouping(
                `daily18`, `weekly18`,
                `daily19`, `weekly19`,
                `daily20`, `weekly20`,
                `daily21`, `weekly21`,
                `daily22`, `weekly22`,
                `daily23`, `weekly23`,
                `daily24`, `weekly24`
                ) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $d1_2018->timestamp,
                $d7_2018->timestamp,

                $d1_2019->timestamp, 
                $d7_2019->timestamp, 

                $d1_2020->timestamp, 
                $d7_2020->timestamp, 

                $d1_2021->timestamp, 
                $d7_2021->timestamp, 

                $d1_2022->timestamp, 
                $d7_2022->timestamp, 

                $d1_2023->timestamp, 
                $d7_2023->timestamp, 

                $d1_2024->timestamp,
                $d7_2024->timestamp  
            ]);

            $d1_2018 = $d1_2018->addDays(1);
            $d1_2019 = $d1_2019->addDays(1);
            $d1_2020 = $d1_2020->addDays(1);
            $d1_2021 = $d1_2021->addDays(1);
            $d1_2022 = $d1_2022->addDays(1);
            $d1_2023 = $d1_2023->addDays(1);
            $d1_2024 = $d1_2024->addDays(1);

            if($i7 == 7) {
                $d7_2018 = $d7_2018->addDays(7);
                $d7_2019 = $d7_2019->addDays(7);
                $d7_2020 = $d7_2020->addDays(7);
                $d7_2021 = $d7_2021->addDays(7);
                $d7_2022 = $d7_2022->addDays(7);
                $d7_2023 = $d7_2023->addDays(7);
                $d7_2024 = $d7_2024->addDays(7);

                $i7 = 1;
            } else {
                $i7 ++;
            }
                
            
        }
       
    }
}
