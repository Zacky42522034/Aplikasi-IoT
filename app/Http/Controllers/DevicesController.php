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
        $device = Device::where([
            'device_id' => $request->device_id,
            'IP_Address' => $request->IP_Address
        ])->first();
    
        if (!$device) {
            return back()->with('error', 'Device tidak ditemukan di database!');
        }
    
        // Cek apakah device sudah ada di paired_devices untuk menghindari duplikasi
        $alreadyPaired = PairedDevices::where([
            'user_id' => auth()->id(),
            'device_id' => $request->device_id,
            'IP_Address' => $request->IP_Address
        ])->exists();
    
        if ($alreadyPaired) {
            return back()->with('error', 'Device sudah dipasangkan sebelumnya!');
        }
    
        // Simpan ke dalam tabel paired_devices
        PairedDevices::create([
            'user_id' => auth()->id(),
            'device_id' => $request->device_id,
            'name' => $request->name,
            'category' => $request->category,
            'IP_Address' => $request->IP_Address,
            'lokasi' => $request->lokasi
        ]);
    
        // Hapus dari tabel devices setelah berhasil dipasangkan
        $device->delete();
    
        return redirect('/pairings')->with('success', 'Device berhasil dipasangkan dan dihapus dari daftar awal!');
    }
    

    public function pairedDevices(Request $request)
    {
        $userId = auth()->id();
    
        $query = PairedDevices::with('latestPairedData')
            ->where('user_id', $userId); // Filter berdasarkan user login
    
        // Tambahkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('device_id', 'like', '%' . $search . '%');
            });
        }
    
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
    
        // Get all devices
        $devices = $query->get();
    
        // Filter online/offline
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
    
        // Re-sort jika pilih last-active atau status
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
    
        // Ambil lokasi hanya milik user login
        $lokasiList = PairedDevices::where('user_id', $userId)
            ->select('lokasi')->distinct()->pluck('lokasi');
    
        // Hitung total device hanya milik user login
        $totalDevices = PairedDevices::where('user_id', $userId)->count();
    
        return view('app.device', [
            'devices' => $devices,
            'lokasiList' => $lokasiList,
            'totalDevices' => $totalDevices
        ]);
    }
    
    
    
    public function detailDevices($id)
    {
        $userId = auth()->id();

        $today = Carbon::today();
    
        $paired = PairedDevices::with('pairedData')
            ->where('user_id', $userId)
            ->find($id);
    
        if (!$paired) {
            return redirect()->back()->with('error', 'Perangkat tidak ditemukan');
        }
    
        $latestData = $paired->pairedData()->latest()->first();
        $chart = $paired->pairedData()->latest()->take(50)->get(); // Ambil 5 data untuk chart
        $latest = $paired->pairedData()->latest()->paginate(5); // Ini untuk tampilkan data lain kalau perlu
    
        // Perubahan: ambil label dan data dari $chart, bukan $latest
        $labels = $chart->pluck('created_at')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('H:i');
        });
    
        $data1 = $chart->pluck('data1');
        $data2 = $chart->pluck('data2');
    
        return view('app.detail', compact('paired', 'latestData', 'latest', 'labels', 'data1', 'data2'));
    }
    
    

    
    // public function searchLocation(Request $request){
            
       
    // return view('app.device', compact('search', 'lokasiList'));
    // }

}
