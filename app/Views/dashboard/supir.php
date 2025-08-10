<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="welcome-card bg-gradient-finance p-4 text-white rounded-3 shadow">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="mb-1">Selamat Datang, Supir!</h3>
            <p class="mb-0 text-white-80">Ringkasan aktivitas hari ini</p>
          </div>
          <div class="icon-circle bg-white-20">
            <i class="fas fa-chart-line fa-lg text-white"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row mb-4">
    <div class="col-md-3 mb-3">
      <div class="finance-card bg-primary text-white p-3 shadow">
        <div class="d-flex justify-content-between">
          <div>
            <p class="mb-1">Total Pengguna</p>
            <h3 class="mb-0">1,245</h3>
          </div>
          <div class="icon-circle bg-white-10">
            <i class="fas fa-users text-white"></i>
          </div>
        </div>
        <small class="d-block mt-2 text-white-80">+12% dari bulan lalu</small>
      </div>
    </div>
    
    <div class="col-md-3 mb-3">
      <div class="finance-card bg-success text-white p-3 shadow">
        <div class="d-flex justify-content-between">
          <div>
            <p class="mb-1">Total Transaksi</p>
            <h3 class="mb-0">542</h3>
          </div>
          <div class="icon-circle bg-white-10">
            <i class="fas fa-shopping-cart text-white"></i>
          </div>
        </div>
        <small class="d-block mt-2 text-white-80">+5% dari minggu lalu</small>
      </div>
    </div>
    
    <div class="col-md-3 mb-3">
      <div class="finance-card bg-warning text-white p-3 shadow">
        <div class="d-flex justify-content-between">
          <div>
            <p class="mb-1">Pendapatan</p>
            <h3 class="mb-0">Rp12.5jt</h3>
          </div>
          <div class="icon-circle bg-white-10">
            <i class="fas fa-wallet text-white"></i>
          </div>
        </div>
        <small class="d-block mt-2 text-white-80">+8% dari kemarin</small>
      </div>
    </div>
    
    <div class="col-md-3 mb-3">
      <div class="finance-card bg-info text-white p-3 shadow">
        <div class="d-flex justify-content-between">
          <div>
            <p class="mb-1">Tugas Aktif</p>
            <h3 class="mb-0">23</h3>
          </div>
          <div class="icon-circle bg-white-10">
            <i class="fas fa-tasks text-white"></i>
          </div>
        </div>
        <small class="d-block mt-2 text-white-80">5 tugas baru hari ini</small>
      </div>
    </div>
  </div>

  <!-- Charts and Tables -->
  <div class="row">
    <div class="col-lg-8 mb-4">
      <div class="card shadow">
        <div class="card-header bg-white">
          <h5 class="mb-0">Statistik Bulanan</h5>
        </div>
        <div class="card-body">
          <canvas id="monthlyChart" height="250"></canvas>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 mb-4">
      <div class="card shadow">
        <div class="card-header bg-white">
          <h5 class="mb-0">Distribusi Kategori</h5>
        </div>
        <div class="card-body">
          <canvas id="categoryChart" height="250"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-header bg-white">
          <h5 class="mb-0">Aktivitas Terbaru</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Aktivitas</th>
                  <th>Waktu</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>#1234</td>
                  <td>John Doe</td>
                  <td>Pembelian Produk A</td>
                  <td>10 menit lalu</td>
                  <td><span class="badge bg-success">Selesai</span></td>
                </tr>
                <tr>
                  <td>#1233</td>
                  <td>Jane Smith</td>
                  <td>Registrasi Akun</td>
                  <td>25 menit lalu</td>
                  <td><span class="badge bg-success">Selesai</span></td>
                </tr>
                <tr>
                  <td>#1232</td>
                  <td>Robert Johnson</td>
                  <td>Pembayaran Invoice</td>
                  <td>1 jam lalu</td>
                  <td><span class="badge bg-warning text-dark">Pending</span></td>
                </tr>
                <tr>
                  <td>#1231</td>
                  <td>Sarah Williams</td>
                  <td>Permintaan Support</td>
                  <td>2 jam lalu</td>
                  <td><span class="badge bg-info">Diproses</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Monthly Chart
  const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
  const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
      datasets: [
        {
          label: 'Pengguna Baru',
          data: [65, 59, 80, 81, 56, 55, 40],
          borderColor: '#4e73df',
          backgroundColor: 'rgba(78, 115, 223, 0.05)',
          tension: 0.3,
          fill: true
        },
        {
          label: 'Transaksi',
          data: [28, 48, 40, 19, 86, 27, 90],
          borderColor: '#1cc88a',
          backgroundColor: 'rgba(28, 200, 138, 0.05)',
          tension: 0.3,
          fill: true
        }
      ]
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

  // Category Chart
  const categoryCtx = document.getElementById('categoryChart').getContext('2d');
  const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
      labels: ['Elektronik', 'Fashion', 'Makanan', 'Perabotan', 'Lainnya'],
      datasets: [{
        data: [35, 25, 20, 15, 5],
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        }
      }
    }
  });
</script>

<style>
  /* Custom Styles */
  .welcome-card.bg-gradient-finance {
    background: linear-gradient(135deg, #2c3e50 0%, #4b79cf 100%);
    position: relative;
    overflow: hidden;
  }
  
  .welcome-card:after {
    content: "";
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 200%;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.05)" d="M0,0 L100,0 L100,100 L0,100 Z"></path></svg>');
    opacity: 0.1;
    transform: rotate(30deg);
  }
  
  .finance-card {
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    overflow: hidden;
  }
  
  .finance-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
  }
  
  .icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .bg-white-10 {
    background-color: rgba(255,255,255,0.1) !important;
  }
  
  .bg-white-20 {
    background-color: rgba(255,255,255,0.2) !important;
  }
  
  .text-white-80 {
    color: rgba(255,255,255,0.8) !important;
  }
  
  .table-hover tbody tr:hover {
    background-color: rgba(44, 120, 108, 0.05);
  }
  
  .badge-success {
    background-color: #2c786c;
  }
</style>

<?= $this->endSection() ?>