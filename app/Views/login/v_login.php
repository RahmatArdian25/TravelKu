<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - CV Ngalau Minang Maimbau </title>

  <!-- AdminLTE + Bootstrap CSS -->
  <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

  <!-- Custom CSS -->
  <style>
    :root {
      --primary: #2c786c;
      --secondary: #004445;
      --accent: #f8b400;
      --light: #faf5e4;
    }
    
    body {
      background: linear-gradient(135deg, var(--secondary), var(--primary));
      background-attachment: fixed;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-page {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px 0;
    }
    
    .login-box {
      width: 400px;
    }
    
    .login-logo {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 20px;
    }
    
    .login-logo b {
      color: var(--secondary);
    }
    
    .login-card {
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border: none;
      overflow: hidden;
    }
    
    .login-card-header {
      background: white;
      border-bottom: none;
      padding: 30px 20px 20px;
      text-align: center;
    }
    
    .login-card-body {
      padding: 30px;
      background: #f9f9f9;
    }
    
    .login-box-msg {
      margin: 0 0 25px;
      padding: 0;
      font-size: 1.1rem;
      color: #555;
    }
    
    .input-group {
      margin-bottom: 20px;
    }
    
    .form-control {
      height: 45px;
      border-radius: 8px;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(44, 120, 108, 0.25);
    }
    
    .input-group-text {
      background: white;
      border-radius: 0 8px 8px 0;
      border-left: none;
    }
    
    .input-group-prepend .input-group-text {
      border-radius: 8px 0 0 8px;
      border-right: none;
    }
    
    .btn-login {
      background-color: var(--primary);
      color: white;
      border: none;
      height: 45px;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.3s;
    }
    
    .btn-login:hover {
      background-color: var(--secondary);
      transform: translateY(-2px);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .register-link {
      color: var(--primary);
      transition: all 0.3s;
    }
    
    .register-link:hover {
      color: var(--secondary);
      text-decoration: none;
    }
    
    /* Modal Styles */
    .modal-content {
      border-radius: 15px;
      overflow: hidden;
      border: none;
    }
    
    .modal-header {
      background: var(--primary);
      color: white;
    }
    
    .modal-title {
      font-weight: 600;
    }
    
    .close {
      color: white;
      opacity: 1;
    }
    
    .btn-register {
      background-color: var(--primary);
      color: white;
      border: none;
      height: 45px;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.3s;
    }
    
    .btn-register:hover {
      background-color: var(--secondary);
    }
    
    /* Alert Styles */
    .alert {
      border-radius: 8px;
      border: none;
    }
    
    /* Floating Animation */
    .login-card {
      animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 576px) {
      .login-box {
        width: 90%;
      }
      
      .login-card-body {
        padding: 20px;
      }
    }

    /* Home link style */
    .home-link {
      display: flex;
      align-items: center;
      color: var(--primary);
      transition: all 0.3s;
    }
    
    .home-link:hover {
      color: var(--secondary);
      text-decoration: none;
    }
    
    .home-link i {
      margin-right: 5px;
    }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <!-- Flash Messages -->
  <?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= session()->getFlashdata('success') ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <div class="card login-card">
    <div class="card-header login-card-header">
      <a href="#" class="login-logo text-dark"><b>CV Ngalau Minang Maimbau</b></a>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silakan Masuk</p>

      <form action="/Login/ceklogin" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="d-flex flex-column">
              <a href="#" class="register-link mb-2" data-toggle="modal" data-target="#modalakun">Belum punya akun?</a>
              <a href="<?= base_url('/') ?>" class="home-link">
                <i class="fas fa-home"></i> Beranda
              </a>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-login btn-block">Login</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL Registrasi -->
<div class="modal fade" id="modalakun" tabindex="-1" role="dialog" aria-labelledby="modalakunLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalakunLabel">Registrasi Akun</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= site_url('Login/save') ?>" method="post">
          <div class="form-group">
            <input type="text" class="form-control" name="nama_user" placeholder="Nama Lengkap" required>
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="nohp" placeholder="No. HP" required>
          </div>
          <button type="submit" class="btn btn-register btn-block">Daftar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- JS Resources -->
<script src="<?= base_url() ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>/assets/adminlte/dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function() {
    // Add animation to modal when shown
    $('#modalakun').on('show.bs.modal', function () {
      $(this).find('.modal-content').css({
        'transform': 'scale(0.8)',
        'transition': 'transform 0.3s ease-out'
      });
      
      setTimeout(() => {
        $(this).find('.modal-content').css('transform', 'scale(1)');
      }, 10);
    });
    
    // Add animation to input focus
    $('input').on('focus', function() {
      $(this).parent().find('.input-group-text').css('border-color', '#2c786c');
    });
    
    $('input').on('blur', function() {
      $(this).parent().find('.input-group-text').css('border-color', '#ced4da');
    });
  });
</script>
</body>
</html>