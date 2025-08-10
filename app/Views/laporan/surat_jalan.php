<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?></h2>
        <a href="<?= base_url('laporan/suratjalan/pdf/'.$jadwal['idjadwal']) ?>" class="btn btn-success rounded-3">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="text-center mb-4">
                <h3>SURAT JALAN</h3>
                <p>Nomor: <?= 'SJ-'.date('Ymd').'-'.$jadwal['idjadwal'] ?></p>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Nama Supir:</strong> <?= $jadwal['nama_user'] ?></p>
                    <p><strong>Nomor HP Supir:</strong> <?= $jadwal['nohp'] ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Tanggal:</strong> <?= date('d F Y', strtotime($jadwal['tanggal'])) ?></p>
                </div>
            </div>
            
            <div class="mb-4">
                <p><strong>Kendaraan:</strong></p>
                <p><?= $jadwal['namakendaraan'] ?> (<?= $jadwal['nopolisi_kendaraan'] ?>)</p>
            </div>
            
            <div class="mb-4">
                <p><strong>Rute Perjalanan:</strong></p>
                <p><?= $jadwal['asal'] ?> - <?= $jadwal['tujuan'] ?></p>
                <p>Jam Berangkat: <?= $jadwal['jam'] ?></p>
            </div>
            
            <div class="mb-4">
                <p><strong>Daftar Penumpang:</strong></p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penumpang</th>
                                <th>Jenis Kelamin</th>
                                <th>Nomor Kursi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach($penumpang as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $p['namapenumpang'] ?></td>
                                <td><?= $p['jeniskelamin'] ?></td>
                                <td><?= $p['nomorkursi'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p>Total Penumpang: <?= count($penumpang) ?> orang</p>
            </div>
            
            <div class="mt-5 pt-4">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <p>Mengetahui,</p>
                        <p class="mt-5">(___________________)</p>
                        <p>Manager Operasional</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <p>Supir,</p>
                        <p class="mt-5">(<?= $jadwal['nama_user'] ?>)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

