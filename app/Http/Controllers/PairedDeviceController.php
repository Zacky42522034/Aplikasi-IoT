<?php

namespace App\Http\Controllers;

use App\Models\PairedData;
use Illuminate\Http\Request;
use App\Models\PairedDevices;

class PairedDeviceController extends Controller
{
    public function storePairedData(Request $request)
    {
        // Validasi input
        
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
            'data' => $request->data
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan!',
            'paired_data' => $pairedData
        ], 201);
    }
}
