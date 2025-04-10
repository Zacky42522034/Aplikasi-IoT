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
            <a href="index.html" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
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
            <a href="/pairings" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="link" class="mr-3 h-5 w-5"></i>
              <span>Pairing Perangkat</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="#" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="bar-chart-2" class="mr-3 h-5 w-5"></i>
              <span>Analisis</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="#" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
              <i data-feather="settings" class="mr-3 h-5 w-5"></i>
              <span>Pengaturan</span>
            </a>
          </li>
        </ul>
      </nav>
      <div class="p-4 border-t border-indigo-600">
        <a href="Auth/index.html" class="flex items-center text-white">
          <img src="Assets/user.png" alt="Profile" class="rounded-full h-8 w-8 mr-2" />
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
            <a href="devices.html" class="text-indigo-600 hover:text-indigo-800 flex items-center">
              <i data-feather="arrow-left" class="mr-2"></i>
              Kembali ke Daftar Perangkat
            </a>
          </div>

          <!-- Device Info -->
          <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-2xl font-bold text-gray-800">Sensor Suhu Ruang Tamu</h2>
              <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">Online</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div>
                <p class="text-sm text-gray-500">ID Perangkat</p>
                <p class="text-lg font-semibold">SEN-TH-001</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Lokasi</p>
                <p class="text-lg font-semibold">Ruang Tamu</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Tipe</p>
                <p class="text-lg font-semibold">Sensor Suhu & Kelembaban</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Terakhir Update</p>
                <p class="text-lg font-semibold">1 menit yang lalu</p>
              </div>
            </div>
          </div>

          <!-- Realtime Data -->
          <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Data Realtime</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Suhu Saat Ini</p>
                <p class="text-2xl font-bold">27°C</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Kelembaban Saat Ini</p>
                <p class="text-2xl font-bold">65%</p>
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
                  <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700">10:00 AM</td>
                    <td class="px-4 py-3 text-sm text-gray-700">26°C</td>
                    <td class="px-4 py-3 text-sm text-gray-700">64%</td>
                    <td class="px-4 py-3 text-sm text-green-700">Normal</td>
                  </tr>
                  <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700">09:30 AM</td>
                    <td class="px-4 py-3 text-sm text-gray-700">25°C</td>
                    <td class="px-4 py-3 text-sm text-gray-700">63%</td>
                    <td class="px-4 py-3 text-sm text-green-700">Normal</td>
                  </tr>
                  <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700">09:00 AM</td>
                    <td class="px-4 py-3 text-sm text-gray-700">24°C</td>
                    <td class="px-4 py-3 text-sm text-gray-700">62%</td>
                    <td class="px-4 py-3 text-sm text-green-700">Normal</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
  <script src="styles.js"></script>
  <!--<script>
        // Initialize Data History Chart
        const dataHistoryCtx = document.getElementById('dataHistoryChart').getContext('2d');
        new Chart(dataHistoryCtx, {
          type: 'line',
          data: {
            labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
            datasets: [{
              label: 'Suhu (°C)',
              data: [24, 23, 25, 27, 28, 27, 26, 25],
              borderColor: 'rgba(79, 70, 229, 1)',
              backgroundColor: 'rgba(79, 70, 229, 0.1)',
              tension: 0.4
            }, {
              label: 'Kelembaban (%)',
              data: [60, 62, 60, 65, 58, 57, 59, 61],
              borderColor: 'rgba(37, 99, 235, 1)',
              backgroundColor: 'rgba(37, 99, 235, 0.1)',
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              }
            },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
  </script> -->
</body>
</html>