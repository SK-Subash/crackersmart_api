<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;
    protected $table = 'users';
	public $timestamps = true;
	protected $fillable = [
        "username",
		"name",
        "date_of_birth",
        "address",
        "phone_number",
        "email",
        "password",
        "otp",
        "user_type",
        "verify_email"
	];
    protected $hidden = [
        'password'
    ];
}
