<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetsAlbum extends Model
{
    public $table = 'pets_album';

    protected $guarded = ['id'];
}