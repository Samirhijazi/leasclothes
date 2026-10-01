<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleCostDelevery extends Model
{
    protected $table = "sale_cost_delevery";
    protected $fillable =[
        "sale_id", "product_id", "cost"
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }
}