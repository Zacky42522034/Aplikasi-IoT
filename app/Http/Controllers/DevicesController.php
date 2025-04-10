<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Device;
use App\Models\PairedData;
use Illuminate\Http\Request;
use App\Models\PairedDevices;
use Illuminate\Support\Facades\DB;

class DevicesController extends Controller
{
    public function pairs(Request $request)
    {
            // Validasi input
    $request->validate([
        'device_id' => 'required|string',
        'IP_Address' => 'required|ip',
        'name' => 'required|string|min:2',
        'category' => 'required|string|min:2',
        'lokasi' => 'required|string'
    ]);

    // Cek apakah device_id dan IP_Address ada di tabel devices
    $deviceExists = Device::where([
        'device_id' => $request->device_id,
        'IP_Address' => $request->IP_Address
    ])->exists();

    if (!$deviceExists) {
        return back()->with('error', 'Device tidak ditemukan di database!');
    }

    // Cek apakah device sudah ada di paired_devices untuk menghindari duplikasi
    $alreadyPaired = PairedDevices::where([
        'device_id' => $request->device_id,
        'IP_Address' => $request->IP_Address
    ])->exists();

    if ($alreadyPaired) {
        return back()->with('error', 'Device sudah dipasangkan sebelumnya!');
    }

    // Simpan ke dalam tabel paired_devices
    PairedDevices::create([
        'device_id' => $request->device_id,
        'name' => $request->name,
        'category' => $request->category,
        'IP_Address' => $request->IP_Address,
        'lokasi' => $request->lokasi
    ]);

    return redirect('/pairings')->with('success', 'Device berhasil dipasangkan!'); 
    }

    
    
    public function pairedDevices(Request $request)
    {
        $query = PairedDevices::with('latestPairedData');
    
        // Filter lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }
    
        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name-asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'last-active':
                    // Sorting after fetching because latestPairedData is related model
                    break;
                case 'status':
                    // Will be handled after fetch too
                    break;
            }
        }
    
        // Get all devices first
        $devices = $query->get();
    
        // Handle online/offline filtering after fetching
        if ($request->filled('status')) {
            $status = $request->status;
            $devices = $devices->filter(function ($device) use ($status) {
                $latest = $device->latestPairedData;
                $isOnline = false;
    
                if ($latest) {
                    $lastUpdated = Carbon::parse($latest->created_at);
                    $isOnline = $lastUpdated->isToday() && $lastUpdated->diffInMinutes(now()) <= 5;
                }
    
                return $status === 'online' ? $isOnline : !$isOnline;
            });
        }
    
        // Re-sort manually if needed (especially for last-active or status)
        if ($request->sort === 'last-active') {
            $devices = $devices->sortByDesc(function ($device) {
                return optional($device->latestPairedData)->created_at;
            });
        }
    
        if ($request->sort === 'status') {
            $devices = $devices->sortByDesc(function ($device) {
                $latest = $device->latestPairedData;
                if ($latest) {
                    $lastUpdated = Carbon::parse($latest->created_at);
                    return $lastUpdated->isToday() && $lastUpdated->diffInMinutes(now()) <= 5 ? 1 : 0;
                }
                return 0;
            });
        }
    
        $lokasiList = PairedDevices::select('lokasi')->distinct()->pluck('lokasi');

        $totalDevices = PairedDevices::count();

    
        return view('app.device', [
            'devices' => $devices,
            'lokasiList' => $lokasiList,
            'totalDevices' => $totalDevices
        ]);
    }
    
    
    

    public function detailDevices($id){
        
        $paired = PairedDevices::with('pairedData')->find($id);

        // Periksa apakah perangkat ditemukan
        if (!$paired) {
            return redirect()->back()->with('error', 'Perangkat tidak ditemukan');
        } 
    
        $latestData = $paired->pairedData()->latest()->first(); // Ambil data terbaru
        $latest = $paired->pairedData()->latest()->take(3)->get();
        // Debugging untuk melihat hasil

        $labels = $latestData->pluck('created_at')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('H:i'); // Format HH:MM
        });
    
        $data1 = $latestData->pluck('data1'); // Ambil data suhu
        $data2 = $latestData->pluck('data2'); // Ambil data kelembaban
        return view('app.detail', compact('paired', 'latestData', 'latest', 'labels', 'data1', 'data2'));
    }
    
    // public function searchLocation(Request $request){
            
       
    // return view('app.device', compact('search', 'lokasiList'));
    // }

}
