<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    use HasFactory;
    protected $table = 'brands';
	public $timestamps = true;
	protected $fillable = [
        'brand_name',
    ];
}
