<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PairedDevices;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalDevices = PairedDevices::count();
        $devices = PairedDevices::with('latestPairedData')->get();
        
        // Ambil data terbaru dari PairedData (bukan dari builder PairedDevices)
        $latestData = \App\Models\PairedData::latest()->first();
    
        $data1 = $latestData?->data1;
        $data2 = $latestData?->data2;
    
        $onlineCount = 0;
        $offlineCount = 0;
    
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
            } else {
                $offlineCount++;
            }
        }
    
        return view('App.Dashboard', compact('totalDevices', 'onlineCount', 'offlineCount', 'devices', 'latestData', 'data1', 'data2'));
    }
    
}
