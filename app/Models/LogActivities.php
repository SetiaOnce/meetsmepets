<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivities extends Model
{
    public $table = 'log_activities';

    public $timestamps = false;

    protected $guarded = [];
    
    use HasFactory;

    public function user()
    {
    	return $this->belongsTo('App\Models\DataSdm', 'fid_user', 'id'); 
    } 
}
