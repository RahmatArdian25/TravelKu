<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $user = session()->get('user'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?></h2>
    </div>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Add filter form -->
    <div class="card shadow-sm mb-4 rounded-3 border-0">
        <div class="card-body">
            <form method="get" action="<?= base_url('jadwalberangkat') ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Filter Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                           value="<?= $filter_tanggal ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <?php if (!empty($filter_tanggal)) : ?>
                        <a href="<?= base_url('jadwalberangkat') ?>" class="btn btn-outline-secondary ms-2">
                            Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Supir</th>
                            <th>Kendaraan</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Harga</th>
                            <th>Jml Penumpang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($jadwal as $j) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $j['nama_user'] ?></td>
                                <td><?= $j['namakendaraan'] ?> (<?= $j['nopolisi_kendaraan'] ?>)</td>
                                <td><?= $j['asal'] ?> - <?= $j['tujuan'] ?></td>
                                <td><?= date('d/m/Y', strtotime($j['tanggal'])) ?></td>
                                <td><?= $j['jam'] ?></td>
                                <td>Rp <?= number_format($j['harga'], 0, ',', '.') ?></td>
                                <td><?= $j['jumlah_penumpang'] ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if ($user['status'] != 'supir') : ?>
                                            <a href="<?= base_url('jadwalberangkat/edit/' . $j['idjadwal']) ?>" class="btn btn-sm btn-primary rounded-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('jadwalberangkat/delete/' . $j['idjadwal']) ?>" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('laporan/suratjalan/' . $j['idjadwal']) ?>" class="btn btn-sm btn-info rounded-3">
                                            <i class="fas fa-file-alt"></i> Surat Jalan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>