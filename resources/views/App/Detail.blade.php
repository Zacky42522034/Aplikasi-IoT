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
            <a href="/pairings" class="flex items-center py-2 px-4 text-white hover:bg-indigo-800 rounded-lg">
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
            <a id="logoutButton" class="text-gray-600 hover:text-gray-800" ">
              <i data-feather="log-out"></i>
            </a>
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
          
              <span id="status-indicator">
                  @php
                      use Carbon\Carbon;
                      $latestData = $paired->latestPairedData;
                      $isOnline = false;
                      $lastActive = 'Belum Pernah Aktif';
          
                      if ($latestData) {
                          $lastUpdated = Carbon::parse($latestData->created_at);
                          $now = Carbon::now();
          
                          if ($lastUpdated->isToday() && $lastUpdated->diffInMinutes($now) <= 5) {
                              $isOnline = true;
                          }
          
                          $lastActive = $lastUpdated->translatedFormat('l, d F Y - H:i');
                      }
                  @endphp
          
                  @if ($isOnline)
                      <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                              Online
                          </span>
                      </span>
                  @else
                      <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                              Offline
                          </span>
                      </span>
                  @endif
              </span>
          </div>
          
          <!-- ✅ Tambahkan script ini di bawah -->
          <script>
              setInterval(() => {
                  fetch(window.location.href, {
                      headers: {
                          'X-Requested-With': 'XMLHttpRequest'
                      }
                  })
                  .then(res => res.text())
                  .then(html => {
                      const parser = new DOMParser();
                      const doc = parser.parseFromString(html, 'text/html');
                      const newStatus = doc.querySelector('#`');
                      if (newStatus) {
                          document.getElementById('status-indicator').innerHTML = newStatus.innerHTML;
                      }
                  });
              }, 5000); // Update setiap 5 detik
          </script>
          
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
                <p id="last-update" class="text-lg font-semibold">
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
            <script>
              setInterval(() => {
                  fetch(window.location.href, {
                      headers: {
                          'X-Requested-With': 'XMLHttpRequest'
                      }
                  })
                  .then(res => res.text())
                  .then(html => {
                      const parser = new DOMParser();
                      const doc = parser.parseFromString(html, 'text/html');
          
                      const newStatus = doc.querySelector('#status-indicator');
                      const newUpdate = doc.querySelector('#last-update');
          
                      if (newStatus) {
                          document.getElementById('status-indicator').innerHTML = newStatus.innerHTML;
                      }
                      if (newUpdate) {
                          document.getElementById('last-update').innerHTML = newUpdate.innerHTML;
                      }
                  });
              }, 5000); // setiap 5 detik
          </script>
                      

              
            </div>
          </div>

          <!-- Realtime Data -->
          <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Data Realtime</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center gap-1">
                  <p id="label-suhu-{{ $paired->id }}" class="text-sm text-gray-600">
                      {{ session('label-suhu-'.$paired->id) ?? 'Suhu Saat Ini' }}
                  </p>
                  <button onclick="ubahLabel('{{ $paired->id }}', 'suhu')" class="text-gray-600 hover:text-blue-700 p-0 m-0" style="line-height: 0;">
                      <i data-feather="edit-2" style="width:14px; height:14px;"></i>
                  </button>
              </div>
              
                <p id="data1" class="text-2xl font-bold">{{ $latestData?->data1 ?? '-' }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                  <div class="flex items-center gap-1">
                <p id="label-kelembaban-{{ $paired->id }}" class="text-sm text-gray-500">
                    {{ session('label-kelembaban-'.$paired->id) ?? 'Kelembaban Saat Ini' }}
                </p>
                <!-- Tombol Edit untuk Kelembapan -->
                <button onclick="ubahLabel({{ $paired->id }}, 'kelembaban')" class="text-gray-600 hover:text-blue-700 p-0 m-0" style="line-height: 0;">
                    <i data-feather="edit-2" style="width:14px; height:14px;"></i>
                </button>
            </div>

                <p id="data2" class="text-2xl font-bold">{{ $latestData?->data2 ?? '-' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
              <p class="text-sm text-gray-500">Status</p>
              <div id="status-data">
                  @if (optional($latestData)->data2 !== null)
                      @if ($latestData->data2 > 70)
                          <p class="text-2xl font-bold text-green-600">Normal</p>
                      @else
                          <p class="text-2xl font-bold text-red-600">Tidak Normal</p>
                      @endif
                  @else
                      <p class="text-2xl font-bold text-gray-400">Belum Ada Data</p>
                  @endif
              </div>
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
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">Riwayat Log</h3>
        
                {{-- Filter Status di kanan --}}
                <form method="GET" class="flex items-end gap-3">
                  <div class="w-[200px]">
                      <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1 text-right">
                          Filter Status
                      </label>
                      <select name="status" id="status-filter"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              onchange="this.form.submit()">
                          <option value="">Semua Status</option>
                          <option value="Normal" {{ request('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                          <option value="Tidak Normal" {{ request('status') == 'Tidak Normal' ? 'selected' : '' }}>Tidak Normal</option>
                      </select>
                  </div>
              
                  <div class="mb-[6px]">
                      <a href="{{ request()->url() }}" 
                         class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                          Clear
                      </a>
                  </div>
              </form>
              
              
            </div>
        
            {{-- Tabel Riwayat --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Waktu</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">
                              <span id="label-suhu-tabel-{{ $paired->id }}">{{ session('label-suhu-'.$paired->id) ?? 'Suhu Saat Ini' }}</span>
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">
                              <span id="label-kelembaban-tabel-{{ $paired->id }}">{{ session('label-kelembaban-'.$paired->id) ?? 'Kelembaban Saat Ini' }}</span>
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="realtime-log-table">
                        @foreach ($latest as $data)
                            @php
                                // Tentukan status berdasarkan data2
                                $status = $data->data2 > 70 ? 'Normal' : 'Tidak Normal';
        
                                // Filter jika status tidak sesuai dengan pilihan
                                $filterStatus = request('status');
                                if ($filterStatus && $filterStatus !== $status) {
                                    continue; // Skip data yang tidak sesuai
                                }
                            @endphp
        
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">
                                  {{ \Carbon\Carbon::parse($data->created_at)->setTimezone('Asia/Makassar')->format('H.i A') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $data->data2 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $data->data1 }}</td>
                                <td class="px-4 py-3 text-sm {{ $status == 'Normal' ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $status }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>                
                </table>
                <div class="mt-4 flex justify-end items-center space-x-2">
                  
              <div class="mt-4 flex justify-end">
                {{ $latest->links() }}
            </div>
            
              
              
            </div>
        </div>
        
              <template id="json-latest-log" style="display:none">
                {!! json_encode($latest) !!}
              </template>
              
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>

  <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function ubahLabel(deviceId, type) {
    const elementId = `label-${type}-${deviceId}`;
    const tableElementId = `label-${type}-tabel-${deviceId}`; // ID untuk label tabel
    const currentLabel = document.getElementById(elementId).innerText;

    Swal.fire({
        title: `Ubah Label ${type.charAt(0).toUpperCase() + type.slice(1)}`,
        input: 'text',
        inputLabel: 'Masukkan label baru',
        inputValue: currentLabel,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value.trim()) {
                return 'Label tidak boleh kosong!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const newLabel = result.value;
            // Update label perangkat
            document.getElementById(elementId).innerText = newLabel;
            // Update label tabel
            document.getElementById(tableElementId).innerText = newLabel;
            // Simpan perubahan di localStorage
            localStorage.setItem(elementId, newLabel);
            localStorage.setItem(tableElementId, newLabel); // Simpan label tabel di localStorage
        }
    });
}

// Saat halaman diload, ambil label dari localStorage
window.onload = function () {
    const deviceId = "{{ $paired->id }}"; // dari controller
    ['suhu', 'kelembaban'].forEach((type) => {
        const key = `label-${type}-${deviceId}`;
        const tableKey = `label-${type}-tabel-${deviceId}`;
        
        const value = localStorage.getItem(key);
        if (value) {
            const element = document.getElementById(key);
            if (element) {
                element.innerText = value;
            }
        }

        const tableValue = localStorage.getItem(tableKey);
        if (tableValue) {
            const tableElement = document.getElementById(tableKey);
            if (tableElement) {
                tableElement.innerText = tableValue;
            }
        }
    });
};
  </script>
  
  


  
    

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
  let dataChart;
  
  // Fungsi ambil label dari localStorage
  function getLabel(type, deviceId, defaultLabel) {
      const key = `label-${type}-${deviceId}`;
      return localStorage.getItem(key) || defaultLabel;
  }
  
  document.addEventListener("DOMContentLoaded", function () {
      const deviceId = "{{ $paired->id }}";
      const ctx = document.getElementById('dataHistoryChart').getContext('2d');
  
      dataChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: {!! json_encode($labels) !!},
              datasets: [
                  {
                      label: getLabel('suhu', deviceId, 'Suhu (°C)'),
                      data: {!! json_encode($data1) !!},
                      borderColor: 'rgba(255, 99, 132, 1)',
                      backgroundColor: 'rgba(255, 99, 132, 0.2)',
                      tension: 0.4
                  },
                  {
                      label: getLabel('kelembaban', deviceId, 'Kelembaban (%)'),
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
  
      // Real-time chart update
      setInterval(() => {
          fetch(window.location.href, {
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(res => res.text())
          .then(html => {
              const parser = new DOMParser();
              const doc = parser.parseFromString(html, 'text/html');
  
              // Pastikan elemen json tersedia
              const labelEl = doc.querySelector('#json-labels');
              const data1El = doc.querySelector('#json-data1');
              const data2El = doc.querySelector('#json-data2');
  
              if (!labelEl || !data1El || !data2El) {
                  console.warn("Elemen JSON tidak ditemukan dalam respons HTML.");
                  return;
              }
  
              const newLabels = JSON.parse(labelEl.textContent);
              const newData1 = JSON.parse(data1El.textContent);
              const newData2 = JSON.parse(data2El.textContent);
  
              // Update chart
              dataChart.data.labels = newLabels;
              dataChart.data.datasets[0].data = newData1;
              dataChart.data.datasets[1].data = newData2;
  
              // Update label berdasarkan localStorage
              dataChart.data.datasets[0].label = getLabel('suhu', deviceId, 'Suhu (°C)');
              dataChart.data.datasets[1].label = getLabel('kelembaban', deviceId, 'Kelembaban (%)');
  
              dataChart.update();
          })
          .catch(err => console.error("Gagal memuat data:", err));
      }, 2000);
  });
  </script>
  


  
  
<pre id="json-labels" style="display:none;">{!! json_encode($labels) !!}</pre>
<pre id="json-data1" style="display:none;">{!! json_encode($data1) !!}</pre>
<pre id="json-data2" style="display:none;">{!! json_encode($data2) !!}</pre>

<script>
  setInterval(() => {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const status = doc.querySelector('#status-data');
        const update = doc.querySelector('#last-update');
        const suhu = doc.querySelector('#data1');
        const kelembaban = doc.querySelector('#data2');

        if (status) {
            document.getElementById('status-data').innerHTML = status.innerHTML;
        }
        if (update) {
            document.getElementById('last-update').innerHTML = update.innerHTML;
        }
        if (suhu) {
            document.getElementById('data1').innerHTML = suhu.innerHTML;
        }
        if (kelembaban) {
            document.getElementById('data2').innerHTML = kelembaban.innerHTML;
        }
    });
}, 1000);


</script>

{{-- <script>
  function updateLogTable() {
      fetch(window.location.href, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(res => res.text())
      .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');

          const jsonLog = JSON.parse(doc.querySelector('#json-latest-log').innerHTML);
          const tableBody = document.getElementById('realtime-log-table');

          let rows = '';
          jsonLog.forEach(entry => {
              const waktu = new Date(entry.created_at).toLocaleTimeString('id-ID', {
                  timeZone: 'Asia/Jakarta',
                  hour: '2-digit',
                  minute: '2-digit',
                  hour12: true
              });
              rows += `
                  <tr class="border-b border-gray-200 hover:bg-gray-50">
                      <td class="px-4 py-3 text-sm text-gray-700">${waktu}</td>
                      <td class="px-4 py-3 text-sm text-gray-700">${entry.data2}</td>
                      <td class="px-4 py-3 text-sm text-gray-700">${entry.data1}</td>
                      <td class="px-4 py-3 text-sm text-green-700">Normal</td>
                  </tr>
              `;
          });

          tableBody.innerHTML = rows;
      });
  }

  setInterval(updateLogTable, 2000);
</script> --}}
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