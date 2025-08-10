<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Daftar Pemesanan</h4>
                <?php if ($user['status'] != 'supir') : ?>
                    <a href="<?= base_url('pemesanan/create') ?>" class="btn btn-light rounded-3 shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Pemesanan
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <?php if (session()->getFlashdata('alert')): ?>
    <div class="alert alert-warning">
        <?= session()->getFlashdata('alert') ?>
    </div>
<?php endif; ?>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama Pemesan</th>
                            <th>Rute</th>
                            <th>Kendaraan</th>
                            <th>Tanggal</th>
                            <th>Jumlah Penumpang</th>
                            <th>Total</th>
                            <th>Status</th>
                            <?php if ($user['status'] != 'supir') : ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($pemesanan as $p) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $p['nama_user'] ?></td>
                                <td><?= $p['asal'] ?> - <?= $p['tujuan'] ?></td>
                                <td>
                                    <?= $p['namakendaraan'] ?? '-' ?><br>
                                    <small class="text-muted"><?= $p['nopolisi_kendaraan'] ?? '-' ?></small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
                                <td><?= $p['jumlah_orang'] ?></td>
                                <td>Rp <?= number_format($p['total'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    switch ($p['status']) {
                                        case 'sudah berangkat':
                                            $badgeClass = 'bg-success';
                                            break;
                                        case 'pembayaran sudah di konfirmasi':
                                            $badgeClass = 'bg-primary';
                                            break;
                                        case 'sudah bayar belum konfirmasi':
                                            $badgeClass = 'bg-info';
                                            break;
                                        case 'belum bayar':
                                            $badgeClass = 'bg-warning';
                                            break;
                                        default:
                                            $badgeClass = 'bg-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= ucfirst($p['status']) ?>
                                    </span>
                                </td>
                                <?php if ($user['status'] != 'supir') : ?>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php if ($user['status'] == 'admin' || ($p['status'] != 'pembayaran sudah di konfirmasi' && $p['status'] != 'sudah berangkat')) : ?>
                                                <!-- Edit Button -->
                                                <a href="<?= base_url('pemesanan/edit/' . $p['idpemesanan']) ?>" class="btn btn-sm btn-primary rounded-3" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <form action="<?= base_url('pemesanan/delete/' . $p['idpemesanan']) ?>" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if ($p['status'] === 'pembayaran sudah di konfirmasi' || $p['status'] === 'sudah berangkat') : ?>
                                                <!-- Print Ticket Button -->
                                                <a href="<?= base_url('laporan/tiket/' . $p['idpemesanan']) ?>" class="btn btn-sm btn-info rounded-3" title="Cetak Tiket">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </a>
                                                
                                                <?php if ($user['status'] != 'admin') : ?>
                                                    <span class="badge bg-secondary align-self-center" title="Pemesanan terkunci">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>