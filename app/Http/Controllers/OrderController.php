<?php

namespace App\Http\Controllers;

use App\Models\FeUser;
use App\Models\OrderImportTracker;
use App\Models\TxShopDomainModelOrderItem;
use App\Models\TxShopDomainModelOrderProduct;
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
        /*
        $helper = new Helper();
        
        $url = "https://zotterdev.developer.at/?type=2289002";
        $maxOrderNumber = OrderImportTracker::max('order_number');
        $start = 1;
        if((int)$maxOrderNumber > 0) {
            $start = $maxOrderNumber;
            $url = "https://zotterdev.developer.at/?type=2289002&tx_devshopfeed_xmlfeed[ordernumber]=".$start."&tx_devshopfeed_xmlfeed[limitorders]=10000";
        } else {
            $url = "https://zotterdev.developer.at/?type=2289002&tx_devshopfeed_xmlfeed[ordernumber]=".$start."&tx_devshopfeed_xmlfeed[limitorders]=10000";
        }
        $result = $helper->consumeAPIClient($url, true);  // for XML REST
        if($result) {
            $result = $helper->importOrderTracker($result);  // store ORDERS for import
        }


        OrderImportTracker::where('import_status', 0)->chunk(100, function($orders) {
            $h = new Helper();
            foreach ($orders as $order) {
                $url = "https://zotterdev.developer.at/rest/shop_item/".$order['uid'];
                $result = $h->consumeAPIClient($url, false);  // for REST
                if($result) {
                    sleep(1);
                    $result = $h->importOrderByUID($result, $order['id']);  // import ORDER
                }
            }
        });
        */

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


    public function common($type)
    {

        $common['all_products'] = TxShopDomainModelOrderProduct::query()
            ->selectRaw('title, sku')
            ->groupBy('title', 'sku')
            ->get();

        return $common;
    }
    
    public function orderDetails($orderNumber)
    {
    
        $data['order'] = TxShopDomainModelOrderItem::query()
            ->leftjoin('fe_users', 'fe_users.id', '=', 'tx_shop_domain_model_order_item.fe_user')
            ->leftjoin('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
            ->leftjoin('tx_shop_domain_model_order_shipping', 'tx_shop_domain_model_order_shipping.linked_id', '=', 'tx_shop_domain_model_order_item.id')
            ->selectRaw(
                'tx_shop_domain_model_order_item.*, 
                fe_users.*,
                tx_shop_domain_model_order_product.*,
                tx_shop_domain_model_order_shipping.*'
                )
            ->where('tx_shop_domain_model_order_item.order_number', '=', $orderNumber)
            ->get();

        $data['products'] = TxShopDomainModelOrderItem::query()
        ->distinct()    
        ->leftjoin('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
        ->leftjoin('tx_shop_domain_model_order_tax', 'tx_shop_domain_model_order_tax.linked_id', '=', 'tx_shop_domain_model_order_item.id')
        ->selectRaw(
            'tx_shop_domain_model_order_product.*'
            )
        ->where('tx_shop_domain_model_order_item.order_number', '=', $orderNumber)
        ->get();

        /*    
        $data['totals'] = TxShopDomainModelOrderItem::query()
        ->distinct()    
        ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
        ->leftjoin('tx_shop_domain_model_order_product', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_product.linked_id')
        ->leftjoin('tx_shop_domain_model_order_tax', 'tx_shop_domain_model_order_tax.linked_id', '=', 'tx_shop_domain_model_order_item.id')
        ->leftjoin('tx_shop_domain_model_order_discount', 'tx_shop_domain_model_order_discount.linked_id', '=', 'tx_shop_domain_model_order_item.id')
        ->selectRaw(
            'tx_shop_domain_model_order_product.*,
            tx_shop_domain_model_order_tax.*,
            tx_shop_domain_model_order_discount.*'
            )
        ->where('tx_shop_domain_model_order_item.order_number', '=', $orderNumber)
        ->get();
        */    

        return $data;
    }

    public function customerDetails($customerId)
    {
        $data['customer_info'] = FeUser::query()->selectRaw("*")
        ->where('fe_users.id', '=',$customerId)
        ->limit(1)
        ->get();
        $data['orders'] = TxShopDomainModelOrderProduct::query()
            ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
            ->leftjoin('fe_users', 'tx_shop_domain_model_order_item.fe_user', '=', 'fe_users.id')
            ->leftjoin('tx_shop_domain_model_order_payment', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_payment.linked_id')
            ->selectRaw('tx_shop_domain_model_order_item.*, tx_shop_domain_model_order_product.*, tx_shop_domain_model_order_payment.*')
            ->where('fe_users.id', '=',$customerId)
            ->where('fe_users.id', '=',$customerId)
            ->limit(6)
            ->get();
        $data['viewed_products'] = null;
        $data['message'] = null;
        $data['voucher'] = null;
        $data['last_emails'] = null;
        $data['groups'] = TxShopDomainModelOrderItem::query()
            ->leftjoin('fe_users', 'tx_shop_domain_model_order_item.fe_user', '=', 'fe_users.id')
            ->selectRaw('fe_users.*')
            ->where('fe_users.id', '=',$customerId)
            ->get();

        return $data;
    }

    public function analytics($type, $curr, $prev, $prod)
    {
        /*
          $periods = array(
          'decade' => 315569260,
          'year' => 31556926,
          'month' => 2629744,
          'week' => 604800,
          'day' => 86400,
          'hour' => 3600,
          'minute' => 60,
          'second' => 1
          );

        $tomorrow = Carbon::now()->addDay();
        $lastWeek = Carbon::now()->subWeek();
        $dt = Carbon::now();
        $date =  Carbon::parse('2019-04-08')->addMonth()->format('m-d-Y');
        echo $dt->subDay(); 
        echo $dt->subDays(29); 
        $date = new \Carbon\Carbon('-3 months');
        $firstOfQuarter = $date->firstOfQuarter();
        $lastOfQuarter = $date->lastOfQuarter();
        $currentDateTime = Carbon::now();
        $newDateTime = Carbon::now()->addYear();
        $currentDateTime = Carbon::now();
        $newDateTime = Carbon::now()->subYear();        

        'CurrToday', 'PrevYesterday'
        'CurrWeekToDate', 'PrevLastWeek'
        'CurrMonthToDate', 'PrevLastMonth'
        'CurrQuarterToDate', 'PrevLastQuarter'
        'CurrYearToDate', 'PrevLastYear'
        'CurrPreviousPeriod', 'PrevPreviousYear'

        */

        $currFrom = '';  
        $currTo = '';
        $prevFrom = '';
        $prevTo = '';

        $data = array();
        //$now = '2020/06/01';
        $now = Carbon::now();
        
        // these are from input
        //$internalDate = Carbon::parse(substr($order->orderDate, 0,10)); // TO DO : need to format properly and get the 'Y-m-d' only

        if($curr == 'CurrToday')  { // get today  

            $currFrom = Carbon::parse($now)->format('Y/m/d');
            $currTo = Carbon::parse($now)->format('Y/m/d');
            $prevFrom = Carbon::parse($prevFrom)->subDay(1)->format('Y/m/d');
            $prevTo = Carbon::parse($prevFrom)->subDay(1)->format('Y/m/d');

            $g2 = Carbon::parse($currFrom)->format('d'); // current group
            $g1 = Carbon::parse($prevFrom)->format('d'); // previous group
            $g = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%d') as gby";

            $data['criteria']['currentText'] = 'Today ('. $currFrom.')';
            $data['criteria']['previousText'] = 'Yesterday ('. $prevFrom.')';
            $data['criteria']['g1'] = $g1;
            $data['criteria']['g2'] = $g2;


        } else if ($curr == 'CurrWeekToDate') {  // get week  

            $currWeekFirstDay = Carbon::parse($now)->startOfWeek();
            $currWeeklastDay = Carbon::parse($now)->endOfWeek();
            $prevWeekFirstDay = Carbon::parse($now)->startOfWeek()->subWeek();
            $prevWeeklastDay = Carbon::parse($now)->subWeek()->endOfWeek();

            $currFrom = Carbon::parse($currWeekFirstDay)->format('Y/m/d');
            $currTo = Carbon::parse($currWeeklastDay)->format('Y/m/d');
            $prevFrom = Carbon::parse($prevWeekFirstDay)->format('Y/m/d');
            $prevTo = Carbon::parse($prevWeeklastDay)->format('Y/m/d');

            $g2 = Carbon::parse($currFrom)->format('w'); // current group
            $g1 = Carbon::parse($prevFrom)->format('w'); // previous group
            $g = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%w') as gby";

            $data['criteria']['currentText'] = 'This Week ('. $currWeekFirstDay->format('M d Y').' to '.$currWeeklastDay->format('M d Y').')';
            $data['criteria']['previousText'] = 'Last Week ('. $prevWeekFirstDay->format('M d Y').' to '.$prevWeeklastDay->format('M d Y').')';
            $data['criteria']['g1'] = $g1;
            $data['criteria']['g2'] = $g2;

        } else if ($curr == 'CurrMonthToDate') {  // get month  
            
            $currMonthFirstDay = Carbon::parse($now)->startOfMonth();
            $currMonthlastDay = Carbon::parse($now)->endOfMonth();
            $prevMonthFirstDay = Carbon::parse($now)->startOfMonth()->subMonth();
            $prevMonthlastDay = Carbon::parse($now)->subMonth()->endOfMonth();

            $currFrom = Carbon::parse($currMonthFirstDay)->format('Y/m/d');
            $currTo = Carbon::parse($currMonthlastDay)->format('Y/m/d');
            $prevFrom = Carbon::parse($prevMonthFirstDay)->format('Y/m/d');
            $prevTo = Carbon::parse($prevMonthlastDay)->format('Y/m/d');

            $g2 = Carbon::parse($currFrom)->format('m'); // current group
            $g1 = Carbon::parse($prevFrom)->format('m'); // previous group
            $g = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%m') as gby";

            $data['criteria']['currentText'] = 'This Month ('. $currMonthFirstDay->format('M d Y').' to '.$currMonthlastDay->format('M d Y').')';
            $data['criteria']['previousText'] = 'Last Month ('. $prevMonthFirstDay->format('M d Y').' to '.$prevMonthlastDay->format('M d Y').')';
            $data['criteria']['g1'] = $g1;
            $data['criteria']['g2'] = $g2;

        } else if ($curr == 'CurrQuarterToDate') {  // get quarter  

            $currQuarterFirstDay = Carbon::parse($now)->startOfQuarter();
            $currQuarterlastDay = Carbon::parse($now)->endOfQuarter();
            $prevQuarterFirstDay = Carbon::parse($now)->startOfQuarter()->subQuarter();
            $prevQuarterlastDay = Carbon::parse($now)->subQuarter()->endOfQuarter();

            $currFrom = Carbon::parse($currQuarterFirstDay)->format('Y/m/d');
            $currTo = Carbon::parse($currQuarterlastDay)->format('Y/m/d');
            $prevFrom = Carbon::parse($prevQuarterFirstDay)->format('Y/m/d');
            $prevTo = Carbon::parse($prevQuarterlastDay)->format('Y/m/d');

            $g2 = Carbon::parse($currFrom)->format('m'); // current group
            $g1 = Carbon::parse($prevFrom)->format('m'); // previous group
            $g = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%m') as gby";

            $data['criteria']['currentText'] = 'This Quarter ('. $currQuarterFirstDay->format('M d Y').' to '.$currQuarterlastDay->format('M d Y').')';
            $data['criteria']['previousText'] = 'Last Quarter ('. $prevQuarterFirstDay->format('M d Y').' to '.$prevQuarterlastDay->format('M d Y').')';
            $data['criteria']['g1'] = $g1;
            $data['criteria']['g2'] = $g2;


        } else if ($curr == 'CurrYearToDate') {  // get year  

            $currYearFirstDay = Carbon::parse($now)->startOfYear();
            $currYearlastDay = Carbon::parse($now)->endOfYear();
            $prevYearFirstDay = Carbon::parse($now)->startOfYear()->subYear();
            $prevYearlastDay = Carbon::parse($now)->subYear()->endOfYear();

            $currFrom = Carbon::parse($currYearFirstDay)->format('Y/m/d');
            $currTo = Carbon::parse($currYearlastDay)->format('Y/m/d');
            $prevFrom = Carbon::parse($prevYearFirstDay)->format('Y/m/d');
            $prevTo = Carbon::parse($prevYearlastDay)->format('Y/m/d');

            $g2 = Carbon::parse($currFrom)->format('Y'); // current group
            $g1 = Carbon::parse($prevFrom)->format('Y'); // previous group
            $g = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y') as gby";

            $data['criteria']['currentText'] = 'This Year ('. $currYearFirstDay->format('M d Y').' to '.$currYearlastDay->format('M d Y').')';
            $data['criteria']['previousText'] = 'Last Year ('. $prevYearFirstDay->format('M d Y').' to '.$prevYearlastDay->format('M d Y').')';

            $data['criteria']['g1'] = $g1;
            $data['criteria']['g2'] = $g2;

        } else if ($curr == 'CurrPreviousPeriod') {  // get period  
        }

        $currF = $currFrom;  
        $currT = $currTo;  
        $prevF = $prevFrom;  
        $prevT = $prevTo;  

        // $data['criteria']['currentFrom'] = Carbon::parse($currF)->format('Y');
        // $data['criteria']['currentTo'] = Carbon::parse($currT)->format('Y');
        // $data['criteria']['previousFrom'] = Carbon::parse($prevF)->format('Y');
        // $data['criteria']['previousTo'] = Carbon::parse($prevT)->format('Y');
        $data['criteria']['gby'] = 'gby';

        $currentFrom = Carbon::parse($currF)->timestamp;
        $currentTo = Carbon::parse($currT)->timestamp;
        $previousFrom = Carbon::parse($prevF)->timestamp;
        $previousTo = Carbon::parse($prevT)->timestamp;
        
        // these are from input
       
        $ymd = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y-%m-%d') as ymd";
        $md = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%m-%d') as md";
        $y = "DATE_FORMAT(tx_shop_domain_model_order_item.order_date, '%Y') as y";

        $orders = "COUNT(tx_shop_domain_model_order_item.order_number) as orders";
        $total_sales = "SUM(tx_shop_domain_model_order_product.gross) as total_sales";
        $items_sold = "SUM(tx_shop_domain_model_order_product.count) as items_sold";
        $raw_items_sold = "SUM(tx_shop_domain_model_order_product.count) as items_sold";
        $net_sales = "SUM(tx_shop_domain_model_order_product.net) as net_sales";
        $gross_sales = "SUM(tx_shop_domain_model_order_product.gross) as gross_sales";

        if($type=='overview') {
            //$data['sales'] = TxShopDomainModelOrderItem::query()
            $data['sales'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(
                    $ymd .' , '. 
                    $md .' , '. 
                    $y .' , '. 
                    $orders .' , '. 
                    $net_sales
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('ymd', 'md', 'y' )
                ->orderBy('md')
                ->get();

            $data['sales_summary'] = TxShopDomainModelOrderProduct::query()
            ->distinct()    
            ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
            ->selectRaw(
                $g .' , '.  // this varies 
                $net_sales . ", ". 
                $orders
                )
            ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
            ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
            })
            ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
            })
            ->groupBy('gby') // this varies 
            ->get();

        } else if($type=='overview_summary') {
            
            $data['summary'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                // ->join('product_view', 'tx_shop_domain_model_order_item.id', '=', 'product_view.linked_id')
                ->selectRaw(
                    $g .' , '.  // this varies 
                    $orders ." , ". 
                    $net_sales . ", ". 
                    $items_sold. ", ". 
                    $total_sales . ", '0' as black"
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('gby') // this varies 
                ->get();

        } else if($type=='products') {

            $data['sales'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                // ->join('product_view', 'tx_shop_domain_model_order_item.id', '=', 'product_view.linked_id')
                ->selectRaw(
                    $ymd .' , '. 
                    $md .' , '. 
                    $y .' , '. 
                    $items_sold
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('ymd', 'md', 'y')
                ->orderBy('md')
                ->get();

        } else if($type=='products_summary') {
            $data['summary'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                // ->join('product_view', 'tx_shop_domain_model_order_item.id', '=', 'product_view.linked_id')
                ->selectRaw(
                    $g .' , '.  // this varies 
                    $orders .' , '. 
                    $net_sales . ", ".
                    $items_sold
                    )                    
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('gby') // this varies 
                ->get();

        } else if($type=='product_list') {
            /*
            $data['list'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('tx_shop_domain_model_order_discount', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_discount.linked_id')
                ->selectRaw(
                    $g .' , '.
                    $ymd. ',
                    tx_shop_domain_model_order_item.id, 
                    tx_shop_domain_model_order_product.title as title, 
                    tx_shop_domain_model_order_product.sku as sku, 
                    tx_shop_domain_model_order_product.count as items_sold,
                    tx_shop_domain_model_order_item.total_net as net_sales, 
                    tx_shop_domain_model_order_item.order_number as orders,
                    tx_shop_domain_model_order_item.order_status as order_status, 
                    "???" as variations, 
                    "???" as stock'
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->orderBy('ymd', 'DESC')
                ->limit(6)
                ->get();
            */

            $data['list'] = TxShopDomainModelOrderProduct::query()
            ->distinct()    
            ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
            ->selectRaw(
                $g .' ,
                tx_shop_domain_model_order_product.title as title, 
                tx_shop_domain_model_order_product.sku as sku, 
                SUM(tx_shop_domain_model_order_product.count) as items_sold,
                SUM(tx_shop_domain_model_order_product.net) as net_sales, 
                COUNT(tx_shop_domain_model_order_product.linked_id) as orders,
                tx_shop_domain_model_order_item.order_status as order_status, 
                "???" as variations, 
                "???" as stock'
                )
            ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
            ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
            })
            // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
            //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
            // })
            ->groupBy('gby', 'title', 'sku', 'order_status')
            ->orderBy('gby', 'DESC')
            ->limit(6)
            ->get();

        } else if($type=='orders') {

            $data['sales'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                //->join('product_view', 'tx_shop_domain_model_order_item.id', '=', 'product_view.linked_id')
                ->selectRaw(
                    $ymd .' , '. 
                    $md .' , '. 
                    $y .' , '. 
                    $orders
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('ymd', 'md', 'y')
                ->orderBy('md')
                ->get();


        } else if($type=='orders_summary') {
            $data['summary'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                //->join('product_view', 'tx_shop_domain_model_order_item.id', '=', 'product_view.linked_id')
                ->selectRaw(
                    $g .' , '. // this varies 
                    $orders .' , '. 
                    $items_sold .' , '. 
                    $net_sales
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('gby') // this varies 
                ->get();

                // Ave order value =  net_sales / orders
                // Ave items per order =  items_sold / orders

        } else if($type=='order_list') {
            $data['list'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('fe_users', 'tx_shop_domain_model_order_item.fe_user', '=', 'fe_users.id')
                ->leftjoin('tx_shop_domain_model_order_discount', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_discount.linked_id')
                ->selectRaw(
                    $ymd .' , '.
                    $g .' , 
                    tx_shop_domain_model_order_item.order_number as order_number, 
                    tx_shop_domain_model_order_item.order_status as order_status, 
                    SUM(tx_shop_domain_model_order_product.net) as net_sales, 
                    fe_users.name as customer, 
                    fe_users.user_type as customer_type, 
                    MIN(tx_shop_domain_model_order_product.title) as product, 
                    SUM(tx_shop_domain_model_order_product.count) as items_sold,
                    COUNT(tx_shop_domain_model_order_product.linked_id) as orders,
                    tx_shop_domain_model_order_discount.code as coupon'
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->groupBy('ymd', 'gby', 'order_number', 'order_status', 'customer', 'customer_type', 'coupon')
                ->orderBy('gby', 'DESC')
                ->limit(6)
                ->get();
                
        } else if($type=='categories') {
            $data['sales'] = TxShopDomainModelOrderProduct::query()
                ->distinct()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(
                    $ymd .' , '. 
                    $md .' , '. 
                    $y .' , '. 
                    $items_sold
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('ymd', 'md', 'y')
                ->orderBy('md')
                ->get();            

        } else if($type=='categories_summary') {
            $data['summary'] = TxShopDomainModelOrderProduct::query()
                ->distinct()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(
                    $g .' , '.
                    $orders .' , '. 
                    $items_sold .' , '. 
                    $net_sales
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('gby') 
                ->get();

        } else if($type=='categories_list') {
            $data['list'] = TxShopDomainModelOrderProduct::query()
                ->distinct()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(
                    $g .' , 
                    COUNT(tx_shop_domain_model_order_item.order_number) as orders, 
                    SUM(tx_shop_domain_model_order_product.net) as net_sales, 
                    COUNT(tx_shop_domain_model_order_product.title) as products,
                    SUM(tx_shop_domain_model_order_product.count) as items_sold, 
                    tx_shop_domain_model_order_product.title as categories'
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                // })
                ->groupBy('categories', 'gby')
                ->orderBy('products', 'DESC')
                ->limit(6)
                ->get();
        } else if($type=='revenue') {

            $data['sales'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                //->join('product_view', 'tx_shop_domain_model_order_item.id', '=', 'product_view.linked_id')
                ->selectRaw(
                    $ymd .' , '. 
                    $md .' , '. 
                    $y .' , '. 
                    $gross_sales .' , '. 
                    $net_sales
                   )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    if($curr == 'CurrToday') {
                       $query->where('tx_shop_domain_model_order_item.internal_date','>=',$currentFrom)
                             ->Where('tx_shop_domain_model_order_item.internal_date', '<=', $currentTo);
                    } else {
                        $query->whereBetween('tx_shop_domain_model_order_item.internal_date', [$currentFrom, $currentTo]);
                    }
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    if($curr == 'CurrToday') {
                       $query->where('tx_shop_domain_model_order_item.internal_date','>=',$previousFrom)
                             ->Where('tx_shop_domain_model_order_item.internal_date', '<=', $previousTo);
                    } else {
                       $query->whereBetween('tx_shop_domain_model_order_item.internal_date', [$previousFrom, $previousTo]);
                    }
                })
                ->groupBy('ymd', 'md', 'y')
                ->orderBy('md')
                ->get();

        } else if($type=='revenue_summary') {
            $data['summary'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('tx_shop_domain_model_order_tax', 'tx_shop_domain_model_order_tax.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('tx_shop_domain_model_order_shipping', 'tx_shop_domain_model_order_shipping.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('tx_shop_domain_model_order_discount', 'tx_shop_domain_model_order_discount.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(
                    $g .' , '. // this varies 
                    $net_sales .' , '. 
                    $gross_sales .' , '. 
                    $total_sales .' , '. 
                    "SUM(tx_shop_domain_model_order_tax.tax) as taxes,
                    SUM(tx_shop_domain_model_order_shipping.gross) as shipping,
                    SUM(tx_shop_domain_model_order_discount.gross) as coupons"
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                    $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                })
                ->groupBy('gby') // this varies 
                ->get();

        } else if($type=='revenue_list') {
            $data['list'] = TxShopDomainModelOrderProduct::query()
                ->distinct()    
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('fe_users', 'tx_shop_domain_model_order_item.fe_user', '=', 'fe_users.id')
                ->leftjoin('tx_shop_domain_model_order_discount', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_discount.linked_id')
                ->selectRaw(
                    $g .' , '. 
                    $ymd .',
                    tx_shop_domain_model_order_item.order_number as order_number, 
                    tx_shop_domain_model_order_item.order_status as order_status, 
                    SUM(tx_shop_domain_model_order_product.net) as net_sales, 
                    fe_users.name as customer, 
                    fe_users.id as customer_id, 
                    fe_users.user_type as customer_type, 
                    MIN(tx_shop_domain_model_order_product.title) as product, 
                    COUNT(tx_shop_domain_model_order_product.title) as product_count, 
                    SUM(tx_shop_domain_model_order_product.count) as items_sold, 
                    tx_shop_domain_model_order_discount.code as coupon'
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                // })
                ->groupBy('gby', 'ymd', 'order_number', 'order_status', 'customer', 'customer_id', 'customer_type', 'coupon')
                ->orderBy('gby', 'DESC')
                ->limit(6)
                ->get();

        } else if($type=='customer') {
            $data['customer_info'] = FeUser::query()->select("*")->limit(1)->get();
            $data['orders'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->select('*')
                ->limit(6)
                ->get();
            $data['viewed_products'] = TxShopDomainModelOrderProduct::query()
                ->select('*')
                ->limit(6)
                ->get();
            $data['message'] = 'This is a test message';
            $data['voucher'] = 'This is a test voucher';
            $data['last_emails'] = TxShopDomainModelOrderProduct::query()
                ->select('*')
                ->limit(6)
                ->get();
            $data['groups'] = TxShopDomainModelOrderProduct::query()
                ->select('*')
                ->limit(6)
                ->get();

        } else {
            $top = $this->top($type, $currentFrom, $currentTo, $previousFrom, $previousTo, $orders, $total_sales, $items_sold, $raw_items_sold, $net_sales, $ymd, $md, $y, $curr, $prod, $g);    
            $data = array_merge($data, $top); 
        }       
        
        return $data;
    }
    
    private function top($type, $currentFrom, $currentTo, $previousFrom, $previousTo, $orders, $total_sales, $items_sold, $raw_items_sold, $net_sales, $ymd, $md, $y, $curr, $prod, $g)
    {

        if($type == 'top_countries') {
            $data['top_countries'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('tx_shop_domain_model_order_address', 'tx_shop_domain_model_order_item.id', '=', 'tx_shop_domain_model_order_address.linked_id')
                ->selectRaw(
                    $g .' , '. 
                    $total_sales .' , '. 
                    $orders .' , '. 
                    "tx_shop_domain_model_order_address.country as country"
                    )
                ->where('tx_shop_domain_model_order_item.order_status', '=','transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                // })
                ->groupBy('country', 'gby')
                ->orderBy('orders', 'DESC')
                ->limit(6)
                ->get();

        } else if ($type == 'top_customers') {
            $data['top_customers'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->leftjoin('fe_users', 'tx_shop_domain_model_order_item.fe_user', '=', 'fe_users.id')
                ->selectRaw(
                    $g .' , '. 
                    $total_sales .' , '. 
                    $orders .' , '. 
                    "fe_users.email as customer"
                )
                ->where('tx_shop_domain_model_order_item.order_status', '=', 'transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                // })
                ->groupBy('customer', 'gby')
                ->orderBy('total_sales', 'DESC')
                ->limit(6)
                ->get();

        } else if ($type == 'top_categories') {
            $data['top_categories'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(        
                    $g .' , '. 
                    $total_sales .' , '.
                    $orders .' , '. 
                    "tx_shop_domain_model_order_product.title as categories"
                )
                ->where('tx_shop_domain_model_order_item.order_status', '=', 'transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                // })
                ->groupBy('title', 'gby')
                ->orderBy('total_sales', 'DESC')
                ->limit(6)
                ->get();

        } else if ($type == 'top_products') {
            $data['top_products'] = TxShopDomainModelOrderProduct::query()
                ->leftjoin('tx_shop_domain_model_order_item', 'tx_shop_domain_model_order_product.linked_id', '=', 'tx_shop_domain_model_order_item.id')
                ->selectRaw(
                    $g .' , '. 
                    $raw_items_sold .' , '. 
                    $net_sales .' , '. 
                    "tx_shop_domain_model_order_product.title as title"
                )
                ->where('tx_shop_domain_model_order_item.order_status', '=', 'transferred')
                ->where(function($query) use ($currentFrom, $currentTo, $curr, $prod) {
                    $query = $this->qry($query, $currentFrom, $currentTo, $curr, $prod);
                })
                // ->orWhere(function($query) use ($previousFrom, $previousTo, $curr, $prod) {
                //     $query = $this->qry($query, $previousFrom, $previousTo, $curr, $prod);
                // })
                ->groupBy('title', 'gby')
                ->orderBy('net_sales', 'DESC')
                ->limit(6)
                ->get();
        }    

        return $data;        
    }
    
    public function qry($query, $from, $to, $get, $prod){ // review $prod tomorrow
        
        if($get == 'CurrToday') {
            if($prod != 'All') {
                $q = $query->where('tx_shop_domain_model_order_item.internal_date','>=',$from)
                    ->Where('tx_shop_domain_model_order_item.internal_date', '<=', $to)
                    ->Where('tx_shop_domain_model_order_product.sku', '=', $prod);
            } else {
                $q = $query->where('tx_shop_domain_model_order_item.internal_date','>=',$from)
                    ->Where('tx_shop_domain_model_order_item.internal_date', '<=', $to);
            }

        } else {
            if($prod != 'All') {
                $q = $query->whereBetween('tx_shop_domain_model_order_item.internal_date', [$from, $to])
                    ->Where('tx_shop_domain_model_order_product.sku', '=', $prod);
            } else {
                $q = $query->where('tx_shop_domain_model_order_item.internal_date','>=',$from)
                    ->Where('tx_shop_domain_model_order_item.internal_date', '<=', $to);
            }            
        }        
        return $q;
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
