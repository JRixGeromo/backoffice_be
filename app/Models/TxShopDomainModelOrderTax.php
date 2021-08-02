<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShopDomainModelOrderTax extends Model
{
    use HasFactory;
    protected $table = 'tx_shop_domain_model_order_tax';
    protected $fillable = [
        'linked_id',
        'tax_class',
        'tax',
        'uid',
        'pid'
    ];
}
