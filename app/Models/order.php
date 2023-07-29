<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $table = 'orders';
	public $timestamps = true;
	protected $fillable = [
        'order_id',
        'user_details',
        'order_details',
        'order_status',
        'order_payment_method',
    ];
    protected $primaryKey = 'id';
}