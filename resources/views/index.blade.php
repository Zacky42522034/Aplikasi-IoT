<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="container mt-5">
    <h3>Welcome</h3>
    <a type="button" class="btn btn-primary mb-3" href="/devices">Add Device</a>

    @if(session('device_list'))
        <a type="button" class="btn btn-danger mb-3" id="delete-selected">Hapus Device</a>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}'
            });
        </script>
    @endif

    @php
    $devices = session('device_list', []);
@endphp

@if(!empty($devices))
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @foreach($devices as $device)
                @php
                    // Pastikan device memiliki key yang diharapkan
                    $deviceId = $device['device_id'] ?? null;
                    $deviceName = $device['name'] ?? 'Unknown Device';
                @endphp

                @if($deviceId)
                    <div class="col d-flex justify-content-center">
                        <div class="card shadow-sm" style="width: 18rem;">
                            <div class="card-body text-center">
                                <div class="text-start">
                                    <input type="radio" name="selected_device" value="{{ $deviceId }}">
                                </div>
                                <h5 class="card-title">{{ $deviceName }}</h5>
                                <p class="card-text">Device ID: {{ $deviceId }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif


    <script>
        document.getElementById("delete-selected")?.addEventListener("click", function () {
            let selectedDevice = document.querySelector('input[name="selected_device"]:checked');
            
            if (!selectedDevice) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Device',
                    text: 'Silakan pilih device yang ingin dihapus.'
                });
                return;
            }

            let deviceId = selectedDevice.value;

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Device ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/delete-device/${deviceId}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            _method: "DELETE"
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: data.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal!",
                                text: data.message
                            });
                        }
                    }).catch(error => {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan!",
                            text: "Gagal menghapus device."
                        });
                    });
                }
            });
        });
    </script>

<script>
    function checkSession() {
        fetch('/check-session', {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.devices || data.devices.length === 0) {
                console.log("Session kosong, reload dalam 3 detik...");
                setTimeout(() => {
                    location.reload();
                }, 3000); // Delay 3 detik sebelum reload
            }
        })
        .catch(error => console.error("Gagal memeriksa session:", error));
    }

    // Cek session setiap 10 detik
    setInterval(checkSession, 10000);

    // Panggil checkSession saat halaman dimuat pertama kali
    document.addEventListener("DOMContentLoaded", checkSession);
</script>



</body>
</html>
