<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DataSdm extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = 'data_sdm';

    protected $guarded = ['id'];
}
