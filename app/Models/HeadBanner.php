<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadBanner extends Model
{
    public $table = 'head_banner';

    protected $guarded = ['id'];

    public $timestamps = false;
    
    use HasFactory;
}
