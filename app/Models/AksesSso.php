<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesSso extends Model
{
    public $table = 'akses_sso';

    protected $guarded = ['id'];
    
    use HasFactory;
}