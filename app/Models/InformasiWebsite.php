<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiWebsite extends Model
{
    public $table = 'informasi_website';

    public $timestamps = false;
    
    use HasFactory;
}
