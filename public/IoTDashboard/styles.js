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
  // Device Status Chart
  new Chart(deviceStatusCtx, {
    type: 'doughnut',
    data: {
      labels: ['Online', 'Offline', 'Maintenance'],
      datasets: [{
        data: [10, 2, 0],
        backgroundColor: [
          'rgba(34, 197, 94, 0.8)',
          'rgba(239, 68, 68, 0.8)',
          'rgba(234, 179, 8, 0.8)'
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