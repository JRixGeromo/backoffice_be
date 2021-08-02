<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShopDomainModelOrderAddress extends Model
{
    use HasFactory;
    protected $table = 'tx_shop_domain_model_order_address';
    protected $fillable = [
                        'linked_id',
                        'item',
                        'title',
                        'salutation',
                        'first_name',
                        'last_name',
                        'name',
                        'email',
                        'company',
                        'department',
                        'street',
                        'street_number',
                        'zip',
                        'city',
                        'country',
                        'phone',
                        'fax',
                        'additional',
                        'tax_identification_number',
                        'is_tax_free',
                        'wants_newsletter',
                        'uid',
                        'pid'
                        ];
    
}
