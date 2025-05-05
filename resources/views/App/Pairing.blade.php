<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pairing Perangkat - IoT Dashboard</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="res/style/styles.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
  <style>
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
        position: fixed;
        z-index: 1000;
        height: 100vh;
        overflow-y: auto;
        width: 80%;
        max-width: 300px;
      }
      
      .sidebar.open {
        transform: translateX(0);
      }
    }
    /* Overlay latar belakang saat sidebar terbuka */
    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }
    
    .sidebar-overlay.open {
      display: block;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">
  <div id="sidebar-overlay" class="sidebar-overlay"></div>
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar bg-indigo-700 text-white w-64 md:flex flex-col">
      <div class="p-4 flex items-center border-b border-indigo-600">
        <i data-feather="cpu" class="mr-2"></i>
        <h1 class="text-xl font-bold">IoT Dashboard</h1>
      </div>
      <nav class="flex-1 overflow-y-auto py-4">
        <ul>
          <li class="mb-1">
            <a href="/dashboard" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="grid" class="mr-3 h-5 w-5"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="/devices" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="hard-drive" class="mr-3 h-5 w-5"></i>
              <span>Perangkat</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="/pairings" class="flex items-center py-2 px-4 text-white bg-indigo-800 rounded-lg">
              <i data-feather="link" class="mr-3 h-5 w-5"></i>
              <span>Pairing Perangkat</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="/analisis" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="bar-chart-2" class="mr-3 h-5 w-5"></i>
              <span>Analisis</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="/pengaturan" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="settings" class="mr-3 h-5 w-5"></i>
              <span>Pengaturan</span>
            </a>
          </li>
        </ul>
      </nav>
      <div class="p-4 border-t border-indigo-600">
        <a href="#" class="flex items-center text-white">
          <img src="{{ asset('IOTDashboard/Assets/user.png') }}" alt="Profile" class="rounded-full h-8 w-8 mr-2" />
          <span>{{ auth()->user()->name }}</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navbar -->
      <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between p-4">
          <!-- Mobile Menu Toggle -->
          <button id="menu-toggle" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
          </button>
          
          <div class="flex items-center">
            <div class="relative">
                <form action="/devices" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari Perangkat" 
                        class="w-64 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        value="{{ request('search') }}" />
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-400">
                        <i data-feather="search"></i>
                    </button>
                </form>
            </div>
        </div>
          
          <div class="flex items-center space-x-4">
            <a id="infoApp" class="text-gray-600 hover:text-gray-800">
              <i data-feather="help-circle"></i>
            </a>
            <a id="logoutButton" class="text-gray-600 hover:text-gray-800">
              <i data-feather="log-out"></i>
            </a>
          </div>
        </div>
      </header>
      
      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 bg-gray-100">
        <div id="pairing-section">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pairing Perangkat Baru</h2>
          </div>
          
          <div class="bg-white p-6 rounded-lg shadow">
            {{-- <div class="mb-6">
              <h3 class="text-lg font-semibold mb-4">Pilih Metode Pairing</h3>
              <div class="flex flex-wrap gap-4">
                <button class="flex items-center justify-center bg-indigo-50 border border-indigo-200 text-indigo-700 p-4 rounded-lg w-full sm:w-48 hover:bg-indigo-100 transition">
                  <i data-feather="wifi" class="mr-2"></i>
                  <span>Wi-Fi</span>
                </button>
                <button class="flex items-center justify-center bg-indigo-50 border border-indigo-200 text-indigo-700 p-4 rounded-lg w-full sm:w-48 hover:bg-indigo-100 transition">
                  <i data-feather="bluetooth" class="mr-2"></i>
                  <span>Bluetooth</span>
                </button>
                <button class="flex items-center justify-center bg-indigo-50 border border-indigo-200 text-indigo-700 p-4 rounded-lg w-full sm:w-48 hover:bg-indigo-100 transition">
                  <i data-feather="smartphone" class="mr-2"></i>
                  <span>QR Code</span>
                </button>
                <button class="flex items-center justify-center bg-indigo-50 border border-indigo-200 text-indigo-700 p-4 rounded-lg w-full sm:w-48 hover:bg-indigo-100 transition">
                  <i data-feather="link" class="mr-2"></i>
                  <span>Manual</span>
                </button>
              </div>
            </div> --}}
            
            <div class="mb-6">
              <h3 class="text-lg font-semibold mb-4">Tambah Perangkat Manual</h3>
              <form action="/pairs" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perangkat</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: Sensor Suhu Kamar" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Perangkat</label>
                    <input type="text" name="device_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: SEN-TH-002" />
                  </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: SmartHome" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: Dapur" />
                  </div>
                  <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat IP (opsional)</label>
                    <input type="text" name="IP_Address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: 192.168.1.100" />
                  </div>
                </div>
                
                
                
                
                
                <div class="flex justify-end">
                  <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-300 transition">Batal</button>
                  <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Tambah Perangkat</button>
                </div>
              </form>
            </div>
        </div>
      </main>
    </div>
  </div>
  @if(session('success') || session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session("success")) !!}
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: {!! json_encode(session("error")) !!}
                });
            @endif
        });
    </script>
@endif

  

  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
  <script>
    feather.replace();

// Mobile Menu Toggle
document.getElementById('menu-toggle').addEventListener('click', function() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('sidebar-overlay').classList.toggle('open');
});

document.getElementById('sidebar-overlay').addEventListener('click', function() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('open');
});
  </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('infoApp').addEventListener('click', function () {
    Swal.fire({
      title: 'Tentang Aplikasi',
      html: `
        <strong>Nama Aplikasi:</strong> Sistem Pemantauan IoT<br>
        <strong>Versi:</strong> 1.0<br>
        <strong>Dibuat oleh:</strong> Tim Pengembang PBL RPL<br>
        <strong>Deskripsi:</strong> Aplikasi ini digunakan untuk memantau perangkat IoT secara real-time, 
        termasuk data suhu, kelembaban, dan status perangkat.
      `,
      icon: 'info',
      confirmButtonText: 'Tutup'
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('logoutButton').addEventListener('click', function (e) {
    e.preventDefault(); // Cegah aksi langsung

    Swal.fire({
      title: 'Yakin ingin keluar?',
      text: "Kamu akan keluar dari aplikasi.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Logout',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirect ke /logout
        window.location.href = '/logout';
      }
    });
  });
</script>

</body>
</html>