<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function process(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'device_id' => 'required',
            'category' => 'required',
            'IP_Address' => 'required'
        ]);

        // Cari device berdasarkan name dan device_id
        $device = Device::where('name', $request->name)
                        ->where('device_id', $request->device_id)
                        ->where('category', $request->category)
                        ->where('IP_Address', $request->IP_Address)
                        ->first();

        if ($device) {
            // Ambil daftar device dari session
            $deviceList = session('device_list', []);

            // Cek apakah device sudah ada di session
            $exists = collect($deviceList)->contains('device_id', $device->device_id);

            if (!$exists) {
                $deviceList[] = [
                    'device_id' => $device->device_id,
                    'name' => $device->name,
                    'category' => $device->category,
                    'IP_Address' => $device->IP_Address
                ];
                session(['device_list' => $deviceList]);
            }

            return redirect('/')->with('success', 'Device ditemukan dan ditambahkan ke session!');
        }

        return back()->with('error', 'Device tidak ditemukan!');
    }

    public function checkSession()
    {
        // Ambil daftar device berdasarkan session
        $deviceIds = session('device_list', []);

        if (empty($deviceIds)) {
            return response()->json(['devices' => []]);
        }

        // Ambil daftar device_id dari session
        $deviceIdList = array_column($deviceIds, 'device_id');

        if (empty($deviceIdList)) {
            session()->forget('device_list');
            return response()->json(['devices' => []]);
        }

        // Ambil data terbaru dari database
        $devices = Device::whereIn('device_id', $deviceIdList)
                         ->get(['device_id', 'name'])
                         ->toArray();

        // Jika tidak ada device yang tersisa di database, reset session
        if (empty($devices)) {
            session()->forget('device_list');
        } else {
            session(['device_list' => $devices]);
        }

        return response()->json(['devices' => session('device_list', [])]);
    }

    public function deleteDevice($id)
    {
        // Hapus device dari database
        $device = Device::where('device_id', $id)->first();

        if ($device) {
            $device->delete();

            // Ambil ulang daftar device dari session
            $deviceList = session('device_list', []);

            // Filter untuk menghapus device yang sudah dihapus dari database
            $updatedDeviceList = array_filter($deviceList, function ($d) use ($id) {
                return $d['device_id'] != $id;
            });

            // Perbarui session setelah penghapusan
            session(['device_list' => array_values($updatedDeviceList)]);

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil dihapus!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Device tidak ditemukan!'
        ]);
    }
}
