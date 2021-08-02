<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShopDomainModelOrderItem extends Model
{
    use HasFactory;
    protected $table = 'tx_shop_domain_model_order_item';
    protected $fillable = ['cart_pid',
                        'fe_user',
                        'order_number',
                        'order_date',
                        'order_status',
                        'invoice_number',
                        'invoice_date',
                        'billing_address',
                        'shipping_address',
                        'additional_data',
                        'additional',
                        'currency',
                        'currency_code',
                        'currency_sign',
                        'currency_translation',
                        'gross',
                        'total_gross',
                        'net',
                        'total_net',
                        'tax',
                        'tax_class',
                        'total_tax',
                        'products',
                        'discounts',
                        'payment',
                        'shipping',
                        'delivery_method',
                        'order_pdfs',
                        'invoice_pdfs',
                        'delivery_pdfs',
                        'crdate',
                        'accept_terms_and_conditions', 
                        'accept_revocation_instruction',
                        'accept_privacy_policy',
                        'comment',
                        'is_proposal',
                        'shipping_same_as_billing',
                        'is_taxfree',
                        'confirmation_sent',
                        'order_language',
                        'uid',
                        'pid',
                        'internal_date',
                        ];
    
}
