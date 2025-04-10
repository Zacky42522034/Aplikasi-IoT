<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Device</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h3 class="mb-4">Add Device</h3>
        <form method="POST" action="/process">
            @csrf
            <div class="mb-3">
                <label for="deviceName" class="form-label">Nama Device</label>
                <input type="text" id="deviceName" name="name" class="form-control" placeholder="Masukkan Nama Device" required>
            </div>
            <div class="mb-3">
                <label for="clientId" class="form-label">Client ID</label>
                <input type="text" id="clientId" name="device_id" class="form-control" placeholder="Masukkan Client ID" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- SweetAlert Notification -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session("error") }}'
                });
            @endif
        });
    </script>
</body>
</html>
