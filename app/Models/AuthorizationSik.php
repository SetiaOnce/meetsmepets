<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizationSik extends Model
{
    public $table = 'authorization_sik';

    protected $guarded = [];
    
    use HasFactory;
}
