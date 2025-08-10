<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV. Ngalau Minang Maimbau - Jasa Angkutan Penumpang Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .about-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        .history-section {
            padding: 60px 0;
        }
        .values-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        .contact-section {
            padding: 60px 0;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Ngalau Minang Maimbau</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#history">Sejarah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#values">Nilai Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-light ms-2">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">CV. Ngalau Minang Maimbau</h1>
            <p class="lead">Jasa Angkutan Penumpang Terpercaya di Sumatera Barat sejak 2006</p>
            <a href="#contact" class="btn btn-primary btn-lg mt-3">Hubungi Kami</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold">Tentang Perusahaan</h2>
                    <p>CV. Ngalau Minang Maimbau adalah perusahaan yang bergerak di bidang jasa angkutan penumpang yang berlokasi di Jl. Agus Salim No. 08, Simpuruik, Kota Batusangkar, Sumatera Barat.</p>
                    <p>Didirikan pada tahun 2006 oleh Ibu Wildayesti dan saat ini dikelola oleh Bapak Herison, kami telah tumbuh menjadi salah satu penyedia jasa angkutan terpercaya di wilayah Sumatera Barat.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31e8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="img-fluid rounded" alt="Bus Transport">
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section id="history" class="history-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Perjalanan Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">2006 - Pendirian</h5>
                            <p class="card-text">Perusahaan didirikan dengan menghadapi berbagai tantangan, terutama terkait dengan akses jalan yang masih terbatas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">2006-2009 - Pengembangan</h5>
                            <p class="card-text">Perusahaan melakukan berbagai langkah strategis termasuk diskusi dengan ahli transportasi dan pembinaan pengemudi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">2009 - Pengakuan</h5>
                            <p class="card-text">Mulai dikenal dan dipercaya oleh masyarakat luas dengan keunggulan ketepatan waktu pelayanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section id="values" class="values-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Komitmen Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history display-4 mb-3 text-primary"></i>
                            <h5 class="card-title">Ketepatan Waktu</h5>
                            <p class="card-text">Komitmen utama kami dalam menjamin kenyamanan penumpang.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people display-4 mb-3 text-primary"></i>
                            <h5 class="card-title">SDM Berkualitas</h5>
                            <p class="card-text">Pengemudi yang terlatih dalam komunikasi dan etika pelayanan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-heart display-4 mb-3 text-primary"></i>
                            <h5 class="card-title">Kepuasan Pelanggan</h5>
                            <p class="card-text">Pengelolaan profesional yang berorientasi pada kepuasan pelanggan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Hubungi Kami</h2>
                    <p><strong>Alamat:</strong> Jl. Agus Salim No. 08, Simpuruik, Kota Batusangkar, Sumatera Barat</p>
                    <p><strong>Pendiri:</strong> Ibu Wildayesti</p>
                    <p><strong>Pengelola:</strong> Bapak Herison</p>
                    <p><strong>Tahun Berdiri:</strong> 2006</p>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kirim Pesan</h5>
                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Nama Anda">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Email Anda">
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="3" placeholder="Pesan Anda"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 CV. Ngalau Minang Maimbau. Semua Hak Dilindungi.</p>
            <div class="mt-3">
                <a href="<?= base_url('login') ?>" class="btn btn-outline-light">Login</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>