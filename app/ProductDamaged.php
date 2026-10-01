<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDamaged extends Model
{
    protected $table = "product_damaged";
    protected $fillable =[
        "product_id", "damaged_qty"
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}