<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginDetails extends Model
{
    use HasFactory;
    protected $table = 'login_details';
    protected $fillable = [
        'login_id',
        'login_email',
        'login_password',
        'role'
    ];
}
