<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShopDomainModelOrderTaxClass extends Model
{
    use HasFactory;
    protected $table = 'tx_shop_domain_model_order_tax_class';
    protected $fillable = [
        'linked_id',
        'title',
        'value',
        'calc',
        'uid',
        'pid'
    ];
}
