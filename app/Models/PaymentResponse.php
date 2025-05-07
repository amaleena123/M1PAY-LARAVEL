<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentResponse extends Model
{
    protected $table = 'payment_responses'; // Specify your table name here

    protected $fillable = [
        'id',
        'transaction_id',
        'transaction_status',
        'product_description',
        'transaction_amount',
        'transaction_amount_converted',
        'transaction_currency',
        'channel',
        'exchange_order_no',
        'merchant_order_no',
        'created_date',
        'modified_date',
        'merchant_id',
        'phone_number',
        'email_address',
        'model',
        'expired_date_time',
        // Add or remove fields based on your actual table structure
    ];
}
