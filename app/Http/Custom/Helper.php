<?php
namespace App\Http\Custom;

use App\Models\FeUser;
use App\Models\OrderImportTracker;
use App\Models\TxShopDomainModelOrderItem;
use App\Models\TxShopDomainModelOrderAddress;
use App\Models\TxShopDomainModelOrderTax;
use App\Models\TxShopDomainModelOrderTaxClass;
use App\Models\TxShopDomainModelOrderProduct;
use App\Models\TxShopDomainModelOrderShipping;
use App\Models\TxShopDomainModelOrderPayment;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Helper {
   
    function consumeAPIClient($url, $xml = false)
    {
        $client = new Client();
                
        $params = [
            //'var' => ''
        ];

        $headers = [
            //'api-key' => 'k3Hy5qr73QhXrmHLXhpEh6CQ'
        ];

        try {

            /*
            $response = $client->request('GET', $url, [
                //'timeout' => 15,
                'auth' => ['preview', 'preview'],
                'verify'  => true,
            ]);
            */
    
            // start request
            $promise = $client->getAsync($url, [
                    'timeout' => 15,
                    'auth' => ['preview', 'preview'],
                    'verify'  => true,
                ])->then(
                function ($response) {
                    return $response->getBody();
                }, function ($exception) {
                    return $exception->getMessage();
                }
            );
            
            // wait for request to finish
            $response = $promise->wait();
            if($xml) {
                $xmlObj = simplexml_load_string($response);
                $value = $this->objectsIntoArray($xmlObj);
                return $value;
            } else {
                return json_decode($response);
            }

        }
        catch (\Exception $e) {
            // code to handle the exception
            return $e->getMessage();
        }
        
    }

    function importOrderTracker($orderArray) {
        if(isset($orderArray['order'])) {
            if(sizeof($orderArray['order']) > 1) {
                for($i = 0; $i < sizeof($orderArray['order']); $i++) {
                    $found = OrderImportTracker::where('order_number',$orderArray['order'][$i]['order_number'])->first();
                    if(!$found) {
                        DB::insert("INSERT INTO order_import_trackers(`uid`, `order_number`, `order_status`) VALUES(?, ?, ?)", [
                            $orderArray['order'][$i]['uid'],
                            $orderArray['order'][$i]['order_number'],
                            $orderArray['order'][$i]['order_status'],
                        ]);
                    }
                }
            } else {
                if($orderArray['order']['uid']) {
                    $found = OrderImportTracker::where('order_number',$orderArray['order']['order_number'])->first();
                    if(!$found) {                
                        DB::insert("INSERT INTO order_import_trackers(`uid`, `order_number`, `order_status`) VALUES(?, ?, ?)", [
                            $orderArray['order']['uid'],
                            $orderArray['order']['order_number'],
                            $orderArray['order']['order_status'],
                        ]);
                    }
                }
            }
        }
    }

    function from_camel_case($input) {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
          $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $ret);
    }
      
    function importOrderByUID($order, $id) {
        
        /*
        $orderArr = array();
        foreach ($order as $o => $result) {
            $orderArr[$this->from_camel_case($o)] = $result;
        }
        */
      
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        try {

            // DB::beginTransaction();
            if(isset($order->feUser->email)) {
                $wishlistHash = $order->feUser->wishlistHash;
                $isGuest = $order->feUser->isGuest;
                $isUnsubscribed = $order->feUser->isUnsubscribed;
                $username = $order->feUser->username;
                $password = $order->feUser->password;
                $name = $order->feUser->name;
                $firstName = $order->feUser->firstName;
                $middleName = $order->feUser->middleName;
                $lastName = $order->feUser->lastName;
                $address = $order->feUser->address;
                $telephone = $order->feUser->telephone;
                $fax = $order->feUser->fax;
                $email = $order->feUser->email;
                $lockToDomain = $order->feUser->lockToDomain;
                $title = $order->feUser->title;
                $zip = $order->feUser->zip;
                $city = $order->feUser->city;
                $country = $order->feUser->country;
                $www = $order->feUser->www;
                $company = $order->feUser->company;
                $lastlogin = $order->feUser->lastlogin;
                $uid = $order->feUser->uid;
                $pid = $order->feUser->pid;
                $user_type = 'registered';
            } else {
                $user = "billingAddress";
                if(!$order->billingAddress->email) {
                    $user = "shippingAddress";
                }
                $wishlistHash = null;
                $isGuest = 1;
                $isUnsubscribed = null;
                $username = $order->shippingAddress->salutation;
                $password = null;
                $name = $order->shippingAddress->name;
                $firstName = $order->shippingAddress->firstName;
                $middleName = null;
                $lastName = $order->shippingAddress->lastName;
                $address = null;
                $telephone = $order->shippingAddress->phone;
                $fax = $order->shippingAddress->fax;
                $email = $order->shippingAddress->email;
                $lockToDomain = null;
                $title = $order->shippingAddress->title;
                $zip = $order->shippingAddress->zip;
                $city = $order->shippingAddress->city;
                $country = $order->shippingAddress->country;
                $www = null;
                $company = $order->shippingAddress->company;
                $lastlogin = null;
                $uid = null;
                $pid = null;
                $user_type = 'guest';
            }

            $feUser = FeUser::where('email',$email)->first(); // check if the user already in the backoffice
            if(!$feUser) {  // if none, create the user
                $newFeUser = FeUser::create(array(
                    'tx_shop_wishlist_hash' => $wishlistHash,
                    'tx_shop_is_guest' => $isGuest,
                    'tx_shop_is_unsubscribed' => $isUnsubscribed,
                    'username' => $username,
                    'password' => $password,
                    //'usergroup' => $usergroup,
                    'name' => $name,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'address' => $address,
                    'telephone' => $telephone,
                    'fax' => $fax,
                    'email' => $email,
                    'lockToDomain' => $lockToDomain,
                    'title' => $title,
                    'zip' => $zip,
                    'city' => $city,
                    'country' => $country,
                    'www' => $www,
                    'company' => $company,
                    //'image' => $image,
                    'lastlogin' => $lastlogin,
                    'uid' => $uid,
                    'pid' => $pid,
                    'user_type' => $user_type
                ));
                $feUserId = $newFeUser->id;
            } else {
                $feUserId = $feUser->id;
            }
            
            $internalDate = Carbon::parse(substr($order->orderDate, 0,10)); // TO DO : need to format properly and get the 'Y-m-d' only
            
            $newTxShopDomainModelOrderItem = TxShopDomainModelOrderItem::create(array(
                'cart_pid' => $order->cartPid,
                'fe_user' => $feUserId,
                'order_number' => $order->orderNumber,
                'order_date' => $order->orderDate,
                'internal_date' => $internalDate->timestamp,
                'order_status' => $order->orderStatus,
                'invoice_number' => $order->invoiceNumber,
                'invoice_date' => $order->invoiceDate,
                'additional_data' => $order->additionalData,
                'additional' => $order->additional,
                'currency' => $order->currency,
                'currency_code' => $order->currencyCode,
                'currency_sign' => $order->currencySign,
                'currency_translation' => $order->currencyTranslation,
                'gross' => $order->gross,
                'total_gross' => $order->totalGross,
                'net' => $order->net,
                'total_net' => $order->totalNet,
                'delivery_method' => $order->deliveryMethod,
                'crdate' => $order->crdate,
                'accept_terms_and_conditions' => $order->acceptTermsAndConditions,
                'accept_revocation_instruction' => $order->acceptRevocationInstruction,
                'accept_privacy_policy' => $order->acceptPrivacyPolicy,
                'comment' => $order->comment,
                'is_proposal' => $order->isProposal,
                'shipping_same_as_billing' => $order->shippingSameAsBilling,
                'is_taxfree' => $order->isTaxfree,
                'confirmation_sent' => $order->confirmationSent,
                'order_language' => $order->orderLanguage,
                'uid' => $order->uid,
                'pid' => $order->pid
    
                // fields below will be linked using the internal id found in the TxShopDomainModelOrderItem model
                /*
                'billing_address' => $order->billingAddress,
                'shipping_address' => $order->shippingAddress,
                'tax' => $order->tax,
                'tax_class' => $order->taxClass,
                'total_tax' => $order->totalTax,
                'products' => $order->products,
                'discounts' => $order->discounts,
                'payment' => $order->payment,
                'shipping' => $order->shipping,
                'order_pdfs' => $order->orderPdfs,
                'invoice_pdfs' => $order->invoicePdfs,
                'delivery_pdfs' => $order->deliveryPdfs,
                */
            ));
    
            $newTxShopDomainModelOrderAddress = TxShopDomainModelOrderAddress::create(array(
                'linked_id' => $newTxShopDomainModelOrderItem->id,
                'item' => $order->billingAddress->item,
                'title' => $order->billingAddress->title,
                'salutation' => $order->billingAddress->salutation,
                'first_name' => $order->billingAddress->firstName,
                'last_name' => $order->billingAddress->lastName,
                'name' => $order->billingAddress->name,
                'email' => $order->billingAddress->email,
                'company' => $order->billingAddress->company,
                'department' => $order->billingAddress->department,
                'street' => $order->billingAddress->street,
                'street_number' => $order->billingAddress->streetNumber,
                'zip' => $order->billingAddress->zip,
                'city' => $order->billingAddress->city,
                'country' => $order->billingAddress->country,
                'phone' => $order->billingAddress->phone,
                'fax' => $order->billingAddress->fax,
                'additional' => $order->billingAddress->additional,
                'tax_identification_number' => $order->billingAddress->taxIdentificationNumber,
                'is_tax_free' => $order->billingAddress->isTaxFree,
                'wants_newsletter' => $order->billingAddress->wantsNewsletter,
                'uid' => $order->billingAddress->uid,
                'pid' => $order->billingAddress->pid
            ));
            
            foreach ($order->tax as $value) {
                $newTxShopDomainModelOrderTax = TxShopDomainModelOrderTax::create(array(
                    'linked_id' => $newTxShopDomainModelOrderItem->id,
                    //'tax_class' => $order->tax->taxClass,
                    'tax' => $value->tax,
                    'uid' => $value->uid,
                    'pid' => $value->pid,
                ));
            }
    
            foreach ($order->taxClass as $value) {
                $newTxShopDomainModelOrderTaxClass = TxShopDomainModelOrderTaxClass::create(array(
                    'linked_id' => $newTxShopDomainModelOrderItem->id,
                    'title' => $value->title,
                    'value' => $value->value,
                    'calc' => $value->calc,
                    'uid' => $value->uid,
                    'pid' => $value->pid,
                ));
            }
    
            // Total Tax is for further investigation
    
            foreach ($order->products as $value) {
                $newTxShopDomainModelOrderProduct = TxShopDomainModelOrderProduct::create(array(
                    'linked_id' => $newTxShopDomainModelOrderItem->id,
                    'item' => $value->item,
                    'product_type' => $value->productType,
                    'sku' => $value->sku,
                    'title' => $value->title,
                    'count' => $value->count,
                    'price' => $value->price,
                    'discount' => $value->discount,
                    'gross' => $value->gross,
                    'net' => $value->net,
                    //'tax_class' => $value->taxClass,
                    'tax' => $value->tax,
                    'additional_data' => $value->additionalData,
                    //'product_additional' => $value->productAdditional,
                    //'additional' => $value->additional,
                    'shop_product' => $value->shopProduct,
                    'uid' => $value->uid,
                    'pid' => $value->pid,
                ));
            }
    
            $newTxShopDomainModelOrderPayment = TxShopDomainModelOrderPayment::create(array(
                'linked_id' => $newTxShopDomainModelOrderItem->id,
                'provider' => $order->payment->provider,
                'addtional' => $order->payment->addtional,
                //'transactions' => $order->payment->transactions,
                'item' => $order->payment->item,
                'service_country' => $order->payment->serviceCountry,
                'service_id' => $order->payment->serviceId,
                'name' => $order->payment->name,
                'status' => $order->payment->status,
                'net' => $order->payment->net,
                'gross' => $order->payment->gross,
                //'tax_class' => $order->payment->taxClass,
                'tax' => $order->payment->tax,
                'note' => $order->payment->note,
                'uid' => $order->payment->uid,
                'pid' => $order->payment->pid,
            ));
    
            $newTxShopDomainModelOrderShipping = TxShopDomainModelOrderShipping::create(array(
                'linked_id' => $newTxShopDomainModelOrderItem->id,
                'item' => $order->shipping->item,
                'service_country' => $order->shipping->serviceCountry,
                'service_id' => $order->shipping->serviceId,
                'name' => $order->shipping->name,
                'status' => $order->shipping->status,
                'net' => $order->shipping->net,
                'gross' => $order->shipping->gross,
                //'tax_class' => $order->shipping->taxClass,
                'tax' => $order->shipping->tax,
                'note' => $order->shipping->note,
                'uid' => $order->shipping->uid,
                'pid' => $order->shipping->pid
            ));
            // DB::commit();

            // set import_status to true
			$updateOrder = OrderImportTracker::find($id);
			$updateOrder->import_status = 1;
			$updateOrder->save();

            return response(['message' => 'Successful'], 200);

          } catch (\Exception $e) {
            // DB::rollBack();
            // return response(['message' => 'Error'], 400);
            $this->errorMessage = $e->getMessage();
         };
    }



    function objectsIntoArray($arrObjData, $arrSkipIndices = array())
    {
        $arrData = array();

        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }
    
        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }
}

?>