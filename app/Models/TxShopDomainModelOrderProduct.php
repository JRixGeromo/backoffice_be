<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShopDomainModelOrderProduct extends Model
{
    use HasFactory;
    protected $table = 'tx_shop_domain_model_order_product';
    protected $fillable = [
        'linked_id',
        'item',
        'product_type',
        'sku',
        'title',
        'count',
        'price',
        'discount',
        'gross',
        'net',
        'tax_class',
        'tax',
        'additional_data',
        'product_additional',
        'additional',
        'shop_product',
        'uid',
        'pid' 
    ];
}
