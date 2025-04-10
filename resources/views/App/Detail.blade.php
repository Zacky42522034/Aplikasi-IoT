<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Perangkat - IoT Dashboard</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="res/style/styles.css" rel="stylesheet">
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
            <a href="pairing.html" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="link" class="mr-3 h-5 w-5"></i>
              <span>Pairing Perangkat</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="error.html" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="bar-chart-2" class="mr-3 h-5 w-5"></i>
              <span>Analisis</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="error.html" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="settings" class="mr-3 h-5 w-5"></i>
              <span>Pengaturan</span>
            </a>
          </li>
        </ul>
      </nav>
      <div class="p-4 border-t border-indigo-600">
        <a href="Auth/index.html" class="flex items-center text-white">
          <img src="{{ asset('IOTDashboard/Assets/user.png') }}" alt="Profile" class="rounded-full h-8 w-8 mr-2" />
          <span>User Profile</span>
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
              <input type="text" placeholder="Cari perangkat..." class="w-64 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <i data-feather="search" class="absolute right-3 top-2.5 text-gray-400"></i>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <button class="text-gray-600 hover:text-gray-800">
              <i data-feather="bell"></i>
            </button>
            <button class="text-gray-600 hover:text-gray-800">
              <i data-feather="help-circle"></i>
            </button>
          </div>
        </div>
      </header>
      
      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 bg-gray-100">
        <div id="device-detail-section">
          <!-- Back Button -->
          <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
              <i data-feather="arrow-left" class="mr-2"></i>
              Kembali ke Daftar Perangkat
            </a>
          </div>

          <!-- Device Info -->
          <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-2xl font-bold text-gray-800">{{ $paired->name }}</h2>

                @php
                 use Carbon\Carbon;
                          $latestData = $paired->latestPairedData;
                          $isOnline = false;
                          $lastActive = 'Belum Pernah Aktif';
              
                          if ($latestData) {
                              $lastUpdated = Carbon::parse($latestData->created_at);
                              $now = Carbon::now();
              
                              // Cek jika data terakhir masuk hari ini dan dalam 5 menit terakhir
                              if ($lastUpdated->isToday() && $lastUpdated->diffInMinutes($now) <= 5) {
                                  $isOnline = true;
                              }
              
                              // Format "Senin, 23 Maret 2025 - 12:45"
                              $lastActive = $lastUpdated->translatedFormat('l, d F Y - H:i');
                          }
                      @endphp
              
                      @if ($isOnline)
                      <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                              Online
                          </span>
                      @else
                      <span class="bg-red-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                              Offline
                          </span>
                      @endif

              </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div>
                <p class="text-sm text-gray-500">ID Perangkat</p>
                <p class="text-lg font-semibold">{{ $paired->device_id }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Lokasi</p>
                <p class="text-lg font-semibold">{{ $paired->lokasi }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Kategori</p>
                <p class="text-lg font-semibold">{{ $paired->category }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Terakhir Update</p>
                <p class="text-lg font-semibold">
                  @php
                          if ($latestData) {
                              $lastUpdated = Carbon::parse($latestData->created_at);
                              $now = Carbon::now();
                              $diffInMinutes = intval($lastUpdated->diffInMinutes($now));
                              $diffInHours = intval($lastUpdated->diffInHours($now));
                              $diffInDays = intval($lastUpdated->diffInDays($now));
                              $diffInWeeks = intval(floor($diffInDays / 7));
                              $diffInMonths = intval($lastUpdated->diffInMonths($now));
              
                              if ($lastUpdated->isToday()) {
                                  if ($diffInMinutes < 60) {
                                      $lastActive = "Aktif {$diffInMinutes} menit yang lalu";
                                  } else {
                                      $lastActive = "Aktif {$diffInHours} jam yang lalu";
                                  }
                              } elseif ($lastUpdated->isYesterday()) {
                                  $lastActive = "kemarin";
                              } elseif ($diffInDays < 7) {
                                  $lastActive = "{$diffInDays} hari yang lalu";
                              } elseif ($diffInDays < 30) {
                                  $lastActive = "{$diffInWeeks} minggu yang lalu";
                              } else {
                                  $lastActive = $lastUpdated->format('d-m-Y');
                              }
                          } else {
                              $lastActive = "Belum Pernah Aktif";
                          }
                      @endphp
                      {{ $lastActive }}
                </p>
              </div>
            </div>
          </div>

          <!-- Realtime Data -->
          <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Data Realtime</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Suhu Saat Ini</p>
                <p class="text-2xl font-bold">{{ $latestData->data1 }}</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Kelembaban Saat Ini</p>
                <p class="text-2xl font-bold">{{ $latestData->data2 }}</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-2xl font-bold text-green-600">Normal</p>
              </div>
            </div>
          </div>

          <!-- Data History Chart -->
          <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Data</h3>
            <canvas id="dataHistoryChart" height="150"></canvas>
          </div>

          <!-- Data Logs -->
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Log</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white border border-gray-200">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Waktu</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Suhu</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kelembaban</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($latest as $data)
                  <tr class="border-b border-gray-200 hover:bg-gray-50">
                      <td class="px-4 py-3 text-sm text-gray-700">
                          {{ \Carbon\Carbon::parse($data->created_at)->format('h:i A') }}
                      </td>
                      <td class="px-4 py-3 text-sm text-gray-700">{{ $data->data2 }}</td>
                      <td class="px-4 py-3 text-sm text-gray-700">{{ $data->data1 }}</td>
                      <td class="px-4 py-3 text-sm text-green-700">Normal</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
  <script>
        // Initialize Feather Icons
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

<canvas id="dataHistoryChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('dataHistoryChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!}, // Waktu
                datasets: [
                    { 
                        label: 'Suhu (°C)',
                        data: {!! json_encode($data1) !!},
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4
                    },
                    {
                        label: 'Kelembaban (%)',
                        data: {!! json_encode($data2) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>

</body>
</html>