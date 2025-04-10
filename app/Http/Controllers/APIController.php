<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\PairedData;
use Illuminate\Http\Request;
use App\Models\PairedDevices;

class APIController extends Controller
{
    public function store(Request $request)
    {
        
        // Simpan data ke database
        $device = Device::create([ 
            'device_id' => $request->device_id,
            'IP_Address' => $request->IP_Address
        ]);
    
        // Return response JSON
        return response()->json([
            'success' => true,
            'message' => 'Device berhasil ditambahkan!',
            'data' => $device
        ], 201);
    }

    public function storePairedData(Request $request)
    {
        // Validasi input
        // dd($request->all());
        
        // Cek apakah device_id ada di paired_devices
        $pairedDevice = PairedDevices::where('device_id', $request->device_id)->first();

        if (!$pairedDevice) {
            return response()->json([
                'success' => false,
                'message' => 'Device belum dipasangkan!'
            ], 404);
        }

        // Simpan data ke paired_data
        $pairedData = PairedData::create([
            'paired_device_id' => $pairedDevice->id,
            'data1' => $request->data1,
            'data2' => $request->data2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan!',
            'paired_data' => $pairedData
        ], 201);
    }
}
