<?php
$user = session()->get('user');
$isLoggedIn = $user && $user['logged_in'] ?? false;
$namaUser = $user['nama'] ?? 'Pengguna';
?>
<div class="main-header">
     <div class="main-header-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             CV Ngalau Minang Maimbau
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
         <!-- End Logo Header -->
     </div>
       <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid d-flex justify-content-between">
            <?= $this->renderSection('breadcrumb') ?>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic d-flex align-items-center" data-bs-toggle="dropdown" href="#" role="button">
                        <div class="avatar-sm me-2">
                            <img src="<?= base_url() ?>/assets/img/user-default.jpg" alt="Profile" class="avatar-img rounded-circle">
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi, <?= esc($namaUser) ?></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <?php if ($isLoggedIn): ?>
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg mb-2">
                                            <img src="<?= base_url() ?>/assets/img/user-default.jpg" alt="Profile" class="avatar-img rounded">
                                        </div>
                                        <div class="u-text text-center">
                                            <h4 class="mb-1"><?= esc($namaUser) ?></h4>
                                            <p class="text-muted mb-0">Status: <?= esc($user['status'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                </li>
                                <li><div class="dropdown-divider"></div></li>
                                <li><a class="dropdown-item" href="<?= base_url('Login/Logout') ?>">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= base_url('login') ?>">Login</a></li>
                            <?php endif; ?>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
     <!-- End Navbar -->
 </div>