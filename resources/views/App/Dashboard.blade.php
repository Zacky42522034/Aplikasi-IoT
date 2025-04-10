<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IoT Dashboard</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="res/style/styles.css" rel="stylesheet">
  <style>
    /* Perbaikan Grid untuk Responsivitas */
    .grid-dashboard {
      display: grid;
      /* Perubahan: Grid template lebih responsif dengan breakpoint */
      grid-template-columns: repeat(1, 1fr); /* Default 1 kolom untuk mobile */
      gap: 1rem;
    }
    
    /* Breakpoint untuk tablet */
    @media (min-width: 640px) {
      .grid-dashboard {
        grid-template-columns: repeat(2, 1fr); /* 2 kolom untuk tablet */
      }
    }
    
    /* Breakpoint untuk desktop */
    @media (min-width: 1024px) {
      .grid-dashboard {
        grid-template-columns: repeat(4, 1fr); /* 4 kolom untuk desktop besar */
      }
    }
    
    /* Perbaikan responsivitas pada kartu perangkat */
    .device-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: 100%; /* Pastikan mengambil lebar penuh dari container */
      min-width: 280px; /* Tetapkan lebar minimum */
      max-width: 100%; /* Maksimal selebar container */
    }
    
    .device-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Perbaikan untuk area status statistik */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(1, 1fr); /* Default 1 kolom untuk mobile */
      gap: 0.75rem;
    }
    
    @media (min-width: 640px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr); /* 2 kolom untuk tablet */
      }
    }
    
    @media (min-width: 1024px) {
      .stats-grid {
        grid-template-columns: repeat(4, 1fr); /* 4 kolom untuk desktop */
      }
    }

    /* Perbaikan charts area */
    .charts-grid {
      display: grid;
      grid-template-columns: 1fr; /* 1 kolom untuk mobile */
      gap: 1.5rem;
    }
    
    @media (min-width: 1024px) {
      .charts-grid {
        grid-template-columns: repeat(2, 1fr); /* 2 kolom untuk desktop */
      }
    }

    /* Perbaikan sidebar mobile */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
        position: fixed;
        z-index: 1000;
        height: 100vh;
        overflow-y: auto;
        width: 80%; /* Sidebar lebih lebar di mobile */
        max-width: 300px; /* Maksimal lebar */
      }
    
      .sidebar.open {
        transform: translateX(0);
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
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">
  <!-- Tambahkan overlay untuk sidebar mobile -->
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
            <a href="/dashboard" class="flex items-center py-2 px-4 text-white bg-indigo-800 rounded-lg">
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
        <div id="dashboard-section">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
          </div>
          
          <!-- Stats Overview - ubah ke class stats-grid -->
          <div class="stats-grid mb-6">
            <div class="bg-white rounded-lg shadow p-4">
              <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-lg">
                  <i data-feather="hard-drive" class="text-indigo-600"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-gray-500 text-sm">Total Perangkat</h3>
                  <p class="text-2xl font-bold">{{ $totalDevices }}</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
              <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                  <i data-feather="check-circle" class="text-green-600"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-gray-500 text-sm">Online</h3>
                  <p class="text-2xl font-bold">{{ $onlineCount }} </p>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
              <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                  <i data-feather="alert-circle" class="text-red-600"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-gray-500 text-sm">Offline</h3>
                  <p class="text-2xl font-bold">{{ $offlineCount }} </p>
                </div>
              </div>
            </div>
            
            
          </div>
         
          <!-- Device Overview -->
          <h3 class="text-xl font-bold text-gray-800 mb-4">Perangkat Terbaru</h3>
          <div class="grid-dashboard mb-6">
            @foreach ($devices as $device)
            <!-- Device Card 1 -->
            <div class="device-card bg-white rounded-lg shadow p-4">
              <div class="flex justify-between items-start mb-4">
                <div class="flex items-center">
                  <div class="bg-green-100 p-2 rounded-full">
                    <i data-feather="thermometer" class="text-green-600 h-5 w-5"></i>
                  </div>
                  <div class="ml-3">
                    <h4 class="font-bold">{{ $device->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $device->device_id }}</p>
                  </div>
                </div>
                @php
                $latestData = $device->latestPairedData;
                $isOnline = false;
                $lastActive = 'Belum Pernah Aktif';
            
                if ($latestData) {
                    $lastUpdated = \Carbon\Carbon::parse($latestData->created_at);
                    $now = \Carbon\Carbon::now();
            
                    $diffInMinutes = $lastUpdated->diffInMinutes($now);
                    $diffInHours = $lastUpdated->diffInHours($now);
                    $diffInDays = $lastUpdated->diffInDays($now);
                    $diffInWeeks = floor($diffInDays / 7);
                    $diffInMonths = $lastUpdated->diffInMonths($now);
            
                    // Status: Online hanya jika terakhir update dalam <= 5 menit
                    if ($lastUpdated->isToday() && $diffInMinutes <= 5) {
                        $isOnline = true;
                    }
            
                    // Format tampilan waktu aktif terakhir
                    if ($lastUpdated->isToday()) {
                        if ($diffInMinutes < 60) {
                            $lastActive = "Aktif {$diffInMinutes} menit yang lalu";
                        } else {
                            $lastActive = "Aktif {$diffInHours} jam yang lalu";
                        }
                    } elseif ($lastUpdated->isYesterday()) {
                        $lastActive = "Aktif kemarin";
                    } elseif ($diffInDays < 7) {
                        $lastActive = "Aktif {$diffInDays} hari yang lalu";
                    } elseif ($diffInDays < 30) {
                        $lastActive = "Aktif {$diffInWeeks} minggu yang lalu";
                    } else {
                        $lastActive = "Aktif pada " . $lastUpdated->format('d-m-Y');
                    }
                }
            @endphp
            
            {{-- Tampilkan badge status --}}
            @if ($isOnline)
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Online</span>
            @else
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Offline</span>
            @endif
            
            {{-- Tampilkan waktu aktif terakhir --}}
          
            
            
            {{-- Last active info --}}
     
              
            
              </div>
              <div class="mb-4">
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium">Suhu</span>
                  <span class="text-sm font-medium">{{ $data1 }}</span>
                </div>
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium">Kelembaban</span>
                  <span class="text-sm font-medium">{{ $data2 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm font-medium">Update Terakhir</span>
                  <span class="text-sm text-gray-500">
                    @php
                    $latestData = $device->latestPairedData;
                    $isOnline = false;
                    $lastActive = 'Belum Pernah Aktif';
            
                    if ($latestData) {
                        $lastUpdated = \Carbon\Carbon::parse($latestData->created_at);
                        $now = \Carbon\Carbon::now();
            
                        $diffInMinutes = (int) $lastUpdated->diffInMinutes($now);
                        $diffInHours = $lastUpdated->diffInHours($now);
                        $diffInDays = $lastUpdated->diffInDays($now);
                        $diffInWeeks = floor($diffInDays / 7);
                        $diffInMonths = $lastUpdated->diffInMonths($now);
            
                        // Cek status online
                        if ($lastUpdated->isToday() && $diffInMinutes <= 5) {
                            $isOnline = true;
                        }
            
                        // Format waktu aktif terakhir
                        if ($lastUpdated->isToday()) {
                            $lastActive = $diffInMinutes < 60
                                ? "{$diffInMinutes} menit yang lalu"
                                : " {$diffInHours} jam yang lalu";
                        } elseif ($lastUpdated->isYesterday()) {
                            $lastActive = " kemarin";
                        } elseif ($diffInDays < 7) {
                            $lastActive = " {$diffInDays} hari yang lalu";
                        } elseif ($diffInDays < 30) {
                            $lastActive = " {$diffInWeeks} minggu yang lalu";
                        } else {
                            $lastActive = " pada " . $lastUpdated->format('d-m-Y');
                        }
                    }
                @endphp
                 {{  $lastActive }}

                  </span>
                </div>
              </div>
              <a href="/detailDevices/{{ $device->id }}" class="text-indigo-600 text-sm font-medium hover:text-indigo-700 flex items-center">
                Lihat Detail
                <i data-feather="chevron-right" class="ml-1 h-4 w-4"></i>
              </a>
            </div>
          @endforeach
            <!-- Device Card 2 -->
           
            
            <!-- Device Card 4 -->
            
          </div>
          
          <!-- Charts - ubah ke class charts-grid -->
          <div class="charts-grid mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-bold mb-4">Penggunaan Energi</h3>
              <canvas id="energyChart" height="250"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
              <h3 class="text-lg font-bold mb-4">Status Perangkat</h3>
              <canvas id="deviceStatusChart" height="250"></canvas>
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

// Initialize Charts (for Dashboard)
const energyCtx = document.getElementById('energyChart')?.getContext('2d');
const deviceStatusCtx = document.getElementById('deviceStatusChart')?.getContext('2d');

if (energyCtx) {
  // Energy Usage Chart
  new Chart(energyCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
      datasets: [{
        label: 'Penggunaan (kWh)',
        data: [65, 59, 80, 81, 56, 55, 40],
        fill: true,
        backgroundColor: 'rgba(79, 70, 229, 0.1)',
        borderColor: 'rgba(79, 70, 229, 1)',
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
}

if (deviceStatusCtx) {
    new Chart(deviceStatusCtx, {
      type: 'doughnut',
      data: {
        labels: ['Online', 'Offline'],
        datasets: [{
          data: [{{ $onlineCount }}, {{ $offlineCount }}],
          backgroundColor: [
            'rgba(34, 197, 94, 0.8)',   // Online - green
            'rgba(239, 68, 68, 0.8)'    // Offline - red
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          }
        },
        cutout: '70%'
      }
    });
  }

// Initialize Data History Chart (for Device Detail Page)
const dataHistoryCtx = document.getElementById('dataHistoryChart')?.getContext('2d');

if (dataHistoryCtx) {
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
}
  </script>
</body>
</html>