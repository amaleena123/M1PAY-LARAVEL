<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutPayment extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'cust_orderid',
        'cust_orderamt',
        'cust_ordercurr',
        'cust_first_name',
        'cust_last_name',
        'cust_mobile',
        'cust_email',
        'description',
        'payment_option',
        'bank',
        'status_order',
        'state_order',
    ];
}
