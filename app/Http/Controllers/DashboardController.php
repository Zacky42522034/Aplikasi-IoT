<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PairedDevices;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        $devices = PairedDevices::with('latestPairedData')
            ->where('user_id', $userId)
            ->get();
        
        $totalDevices = $devices->count();
        $onlineCount = 0;
        $offlineCount = 0;
    
        // Array untuk menyimpan data tiap device
        $deviceData = [];
    
        foreach ($devices as $device) {
            $latest = $device->latestPairedData;
    
            if ($latest) {
                $lastUpdated = Carbon::parse($latest->created_at);
                $now = Carbon::now();
    
                if ($lastUpdated->isToday() && $lastUpdated->diffInMinutes($now) <= 5) {
                    $onlineCount++;
                } else {
                    $offlineCount++;
                }
    
                $deviceData[$device->id] = [
                    'data1' => $latest->data1,
                    'data2' => $latest->data2,
                ];
            } else {
                $offlineCount++;
                $deviceData[$device->id] = [
                    'data1' => null,
                    'data2' => null,
                ];
            }
        }
    
        return view('App.Dashboard', compact('totalDevices', 'onlineCount', 'offlineCount', 'devices', 'deviceData'));
    }
    
}
