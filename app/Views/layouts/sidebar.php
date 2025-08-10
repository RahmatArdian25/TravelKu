<?php
$user = session()->get('user'); // Sekarang akan mendapatkan array
$currentUri = uri_string();

// Handle both array and object cases
$userStatus = is_array($user) ? ($user['status'] ?? null) : ($user->status ?? null);
$userStatus = strtolower($userStatus); // Pastikan lowercase

$isAdmin = ($user && $userStatus === 'admin');
$isPimpinan = ($user && $userStatus === 'pimpinan');
$isSupir = ($user && $userStatus === 'supir');
$isPenumpang = ($user && $userStatus === 'penumpang');
?>

<div class="sidebar" data-background-color="dark">
    <!-- Logo Header -->
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="/" class="text-white ms-5 ms-lg-0 logo" aria-label="Ngalau Minang Maimbau Home">
                <b>Ngalau Minang Maimbau</b>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar" aria-label="Toggle sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler" aria-label="Toggle navigation">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more" aria-label="More options">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
               <!-- Dashboard -->
<li class="nav-item <?= ($currentUri === '') ? 'active' : '' ?>">
    <a href="/" aria-current="<?= ($currentUri === '') ? 'page' : 'false' ?>">
        <i class="fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- Master Menu (Admin Only) -->
<?php if ($isAdmin): ?>
<li class="nav-section">
    <span class="sidebar-mini-icon">
        <i class="fa fa-ellipsis-h"></i>
    </span>
    <h4 class="text-section">Master Menu</h4>
</li>

<li class="nav-item <?= ($currentUri === 'kendaraan') ? 'active' : '' ?>">
    <a href="/kendaraan" aria-current="<?= ($currentUri === 'kendaraan') ? 'page' : 'false' ?>">
        <i class="fas fa-bus"></i>
        <p>Kendaraan</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'rute') ? 'active' : '' ?>">
    <a href="/rute" aria-current="<?= ($currentUri === 'rute') ? 'page' : 'false' ?>">
        <i class="fas fa-route"></i>
        <p>Rute</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'user') ? 'active' : '' ?>">
    <a href="/user" aria-current="<?= ($currentUri === 'user') ? 'page' : 'false' ?>">
        <i class="fas fa-user-cog"></i>
        <p>User</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'kursikendaraan') ? 'active' : '' ?>">
    <a href="/kursikendaraan" aria-current="<?= ($currentUri === 'kursikendaraan') ? 'page' : 'false' ?>">
        <i class="fas fa-th-large"></i>
        <p>Kursi Kendaraan</p>
    </a>
</li>
<?php endif; ?>

<?php if ($isAdmin || $isSupir): ?>
<li class="nav-item <?= ($currentUri === 'jadwalberangkat') ? 'active' : '' ?>">
    <a href="/jadwalberangkat" aria-current="<?= ($currentUri === 'jadwalberangkat') ? 'page' : 'false' ?>">
        <i class="fas fa-calendar-check"></i>
        <p>Jadwal Berangkat</p>
    </a>
</li>
<?php endif; ?>

<!-- Transaksi -->
<li class="nav-section">
    <span class="sidebar-mini-icon">
        <i class="fa fa-ellipsis-h"></i>
    </span>
    <h4 class="text-section">Transaksi</h4>
</li>

<?php if ($isAdmin || $isPenumpang): ?>
<li class="nav-item <?= ($currentUri === 'pemesanan') ? 'active' : '' ?>">
    <a href="/pemesanan" aria-current="<?= ($currentUri === 'pemesanan') ? 'page' : 'false' ?>">
        <i class="fas fa-ticket-alt"></i>
        <p>Pesanan</p>
    </a>
</li>
<?php endif; ?>

<!-- Laporan -->
<?php if ($isAdmin || $isPimpinan): ?>
<li class="nav-section">
    <span class="sidebar-mini-icon">
        <i class="fa fa-ellipsis-h"></i>
    </span>
    <h4 class="text-section">Laporan</h4>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/penumpang') ? 'active' : '' ?>">
    <a href="/laporan/penumpang" aria-current="<?= ($currentUri === 'laporan/penumpang') ? 'page' : 'false' ?>">
        <i class="fas fa-user"></i>
        <p>Laporan Penumpang</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/supir') ? 'active' : '' ?>">
    <a href="/laporan/supir" aria-current="<?= ($currentUri === 'laporan/supir') ? 'page' : 'false' ?>">
        <i class="fas fa-id-badge"></i>
        <p>Laporan Supir</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/kendaraan') ? 'active' : '' ?>">
    <a href="/laporan/kendaraan" aria-current="<?= ($currentUri === 'laporan/kendaraan') ? 'page' : 'false' ?>">
        <i class="fas fa-truck-moving"></i>
        <p>Laporan Kendaraan</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/rute') ? 'active' : '' ?>">
    <a href="/laporan/rute" aria-current="<?= ($currentUri === 'laporan/rute') ? 'page' : 'false' ?>">
        <i class="fas fa-map-signs"></i>
        <p>Laporan Rute</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/jadwal') ? 'active' : '' ?>">
    <a href="/laporan/jadwal" aria-current="<?= ($currentUri === 'laporan/jadwal') ? 'page' : 'false' ?>">
        <i class="fas fa-calendar-alt"></i>
        <p>Laporan Jadwal</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/pemesanan-tahunan') ? 'active' : '' ?>">
    <a href="/laporan/pemesanan-tahunan" aria-current="<?= ($currentUri === 'laporan/pemesanan-tahunan') ? 'page' : 'false' ?>">
        <i class="fas fa-calendar-alt"></i>
        <p>Laporan Pemesanan Per Tahun</p>
    </a>
</li>

<li class="nav-item <?= ($currentUri === 'laporan/pemesanan') ? 'active' : '' ?>">
    <a href="/laporan/pemesanan" aria-current="<?= ($currentUri === 'laporan/pemesanan') ? 'page' : 'false' ?>">
        <i class="fas fa-calendar-check"></i>
        <p>Laporan Pemesanan Per Bulan</p>
    </a>
</li>

<?php endif; ?>

            </ul>
        </div>
    </div>
</div>