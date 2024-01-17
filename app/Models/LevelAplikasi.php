<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelAplikasi extends Model
{
    public $table = 'level_aplikasi';

    protected $guarded = ['id'];
    
    use HasFactory;
}