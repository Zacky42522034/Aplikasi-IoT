<?php

namespace App\Models;

use App\Models\PairedDevices;
use Illuminate\Database\Eloquent\Model;

class PairedData extends Model
{
    protected $guarded = [];

    public function PairedDevice(){
        return $this->belongsTo(PairedDevices::class, 'paired_device_id'); 
    }
}
