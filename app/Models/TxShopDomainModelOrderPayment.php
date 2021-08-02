<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShopDomainModelOrderPayment extends Model
{
    use HasFactory;
    protected $table = 'tx_shop_domain_model_order_payment';
    protected $fillable = [
        'linked_id',
        'provider',
        'addtional',
        'transactions',
        'item',
        'service_country',
        'service_id',
        'name',
        'status',
        'net',
        'gross',
        'tax_class',
        'tax',
        'note',
        'uid',
        'pid'        
    ];
}
