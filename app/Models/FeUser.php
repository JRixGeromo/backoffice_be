<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeUser extends Model
{
    use HasFactory;
    protected $table = 'fe_users';
    protected $fillable = [
        'tx_shop_wishlist_hash',
        'tx_shop_is_guest',
        'tx_shop_is_unsubscribed',
        'username',
        'password',
        'usergroup',
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'telephone',
        'fax',
        'email',
        'lock_to_domain',
        'title',
        'zip',
        'city',
        'country',
        'www',
        'company',
        'image',
        'lastlogin',
        'uid',
        'pid',
        'user_type'
    ];
}
