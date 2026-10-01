<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable =[

        "purchase_id", "user_id", "sale_id", "account_id", "payment_reference", "amount", "change", "paying_method", "payment_note","payment_status"
    ];
    
    public function status()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status', 'id');
    }
}
