<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perangkat - IoT Dashboard</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="res/style/styles.css" rel="stylesheet">
  <style>
    /* Grid untuk perangkat - lebih responsif */
    .grid-devices {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 1rem;
    }
    
    /* Perbaikan untuk layar kecil */
    @media (max-width: 640px) {
      .grid-devices {
        grid-template-columns: 1fr;
      }
    }
    
    /* Perbaikan untuk tablet */
    @media (min-width: 641px) and (max-width: 768px) {
      .grid-devices {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    .device-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .device-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Perbaikan sidebar */
    @media (max-width: 768px) {
      .sidebar {
        position: fixed; 
        z-index: 50;
        height: 100vh;
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
      }
      
      .sidebar.open {
        transform: translateX(0);
      }
      
      /* Overlay background saat sidebar terbuka */
      .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
      }
      
      .sidebar-overlay.active {
        display: block;
      }
    }
    
    /* Penyesuaian tabel untuk mobile */
    @media (max-width: 640px) {
      .responsive-table {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      
      /* Card view untuk tabel di mobile */
      .mobile-card-view tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
      }
      
      .mobile-card-view tbody td {
        display: flex;
        justify-content: space-between;
        text-align: right;
        padding: 0.5rem 0;
        border: none;
      }
      
      .mobile-card-view tbody td:before {
        content: attr(data-label);
        font-weight: 600;
        text-align: left;
      }
      
      .mobile-card-view thead {
        display: none;
      }
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex h-screen overflow-hidden">
    <!-- Overlay background untuk mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    
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
            <a href="/devices" class="flex items-center py-2 px-4 text-white bg-indigo-800 rounded-lg">
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
              <input type="text" placeholder="Cari perangkat..." class="w-64 sm:w-64 xs:w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
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
        <div id="devices-section">
          <div class="flex flex-wrap justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">Manajemen Perangkat</h2>
            <div class="flex">
              <a href="/pairings" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center">
                <i data-feather="plus" class="mr-2 h-4 w-4"></i>
                Tambah Perangkat
              </a>
            </div>
          </div>
          
          <!-- Filters and Sorting -->
          <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
              
              <form action="/devices" method="GET" class="flex flex-row gap-4 mb-4 flex-wrap">

                {{-- Filter Status Perangkat --}}
                <div class="w-[600px]">
                    <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status Perangkat
                    </label>
                    <select name="status" id="filter-status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                    </select>
                </div>
            
                {{-- Filter Lokasi Perangkat --}}
                <div class="w-[300px]">
                    <label for="filter-location" class="block text-sm font-medium text-gray-700 mb-1">
                        Lokasi
                    </label>
                    <select name="lokasi" id="filter-location"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">Semua Lokasi</option>
                        @foreach ($lokasiList as $lokasi)
                            <option value="{{ $lokasi }}" {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                                {{ $lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                {{-- Urutkan --}}
                <div class="w-[300px]">
                    <label for="sort-by" class="block text-sm font-medium text-gray-700 mb-1">
                        Urutkan
                    </label>
                    <select name="sort" id="sort-by"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">Pilih Urutan</option>
                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                        <option value="last-active" {{ request('sort') == 'last-active' ? 'selected' : '' }}>Terakhir Aktif</option>
                    </select>
                </div>
            
                {{-- Tombol Clear --}}
                <div class="flex items-end">
                    <a href="/devices"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center">
                        Clear
                    </a>
                </div>
            
            </form>
            
           
            
            
            </div>
          </div>
          
          <!-- Devices List -->
          <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-4 border-b border-gray-200 flex flex-wrap justify-between items-center">
              <h3 class="text-lg font-medium mb-2 sm:mb-0">Daftar Perangkat</h3>
              <span class="text-sm text-gray-500">  Menampilkan {{ $devices->count() }} dari total {{ $totalDevices }} perangkat</span>
            </div>
            
            <div class="responsive-table">
              <table class="w-full divide-y divide-gray-200 mobile-card-view">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perangkat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Perangkat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Aktif</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <!-- Device Row 1 -->
                  @php
                  use Carbon\Carbon;
              @endphp
              
              @foreach ($devices as $device)
              <tr>
                  <td class="px-6 py-4 whitespace-nowrap" data-label="Perangkat">
                      <div class="flex items-center">
                          <div class="bg-green-100 p-2 rounded-full">
                              <i data-feather="thermometer" class="text-green-600 h-5 w-5"></i>
                          </div>
                          <div class="ml-3">
                              <div class="text-sm font-medium text-gray-900">{{ $device->name }}</div>
                              <div class="text-xs text-gray-500">{{ $device->category }}</div>
                          </div>
                      </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap" data-label="ID Perangkat">
                      <div class="text-sm text-gray-900">{{ $device->device_id }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap" data-label="Lokasi">
                      <div class="text-sm text-gray-900">{{ $device->lokasi }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap" data-label="Status">
                      @php
                          $latestData = $device->latestPairedData;
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
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                              Online
                          </span>
                      @else
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                              Offline
                          </span>
                      @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="Terakhir Aktif">
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
                                  $lastActive = "Terakhir aktif kemarin";
                              } elseif ($diffInDays < 7) {
                                  $lastActive = "Terakhir aktif {$diffInDays} hari yang lalu";
                              } elseif ($diffInDays < 30) {
                                  $lastActive = "Terakhir aktif {$diffInWeeks} minggu yang lalu";
                              } else {
                                  $lastActive = $lastUpdated->format('d-m-Y');
                              }
                          } else {
                              $lastActive = "Belum Pernah Aktif";
                          }
                      @endphp
                      {{ $lastActive }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" data-label="Tindakan">
                      <a href="/detailDevices/{{ $device->id }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                      <a href="#" class="text-gray-600 hover:text-gray-900">Edit</a>
                  </td>
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
    // Inisialisasi ikon Feather
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
      
      // Toggle sidebar untuk mobile
      const menuToggle = document.getElementById('menu-toggle');
      const sidebar = document.getElementById('sidebar');
      const sidebarOverlay = document.getElementById('sidebar-overlay');
      
      if (menuToggle && sidebar && sidebarOverlay) {
        menuToggle.addEventListener('click', function() {
          sidebar.classList.toggle('open');
          sidebarOverlay.classList.toggle('active');
        });
        
        sidebarOverlay.addEventListener('click', function() {
          sidebar.classList.remove('open');
          sidebarOverlay.classList.remove('active');
        });
      }
    });
  </script>
</body>
</html>