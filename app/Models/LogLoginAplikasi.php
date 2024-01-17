<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogLoginAplikasi extends Model
{
    public $table = 'log_login_aplikasi';

    public $timestamps = false;

    protected $guarded = [];

    use HasFactory;
}
