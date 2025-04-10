<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UdpListener extends Command
{
    protected $signature = 'udp:listen';
    protected $description = 'Listen for incoming UDP packets';

    public function handle()
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_bind($socket, '0.0.0.0', 8888);

        $this->info('Listening for UDP packets on port 8888...');

        while (true) {
            $buffer = '';
            $from = '';
            $port = 0;
            socket_recvfrom($socket, $buffer, 1024, 0, $from, $port);

            $this->info("Received: $buffer from $from:$port");

            // Simpan ke database
            DB::table('devices')->insert([
                'device_id' => trim($buffer),
                'ip_address' => $from,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        socket_close($socket);
    }
}
