<?php

namespace App\Models;

use App\Models\PairedData;
use Illuminate\Database\Eloquent\Model;

class PairedDevices extends Model
{

    protected $guarded = [];

    public function PairedData(){
        return $this->hasmany(PairedData::class, 'paired_device_id');
    }

    public function latestPairedData()
    {
        return $this->hasOne(PairedData::class, 'paired_device_id')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
