<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $table = 'products';
	public $timestamps = true;
	protected $fillable = [
        'brand_id',
        'category_id',
        'product_name',
        'product_image',
        'product_link',
        'product_mrp',
        'product_discount',
        'stock_quantity',
	];
}
