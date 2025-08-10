<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow rounded-4 border-success">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> <?= esc($title) ?></h4>
        </div>
        <div class="card-body">
            <form method="get" action="" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="<?= base_url("laporan/jadwal/pdf?tanggal=$tanggal") ?>" target="_blank" class="btn btn-danger rounded-pill">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="tableJadwal" class="table table-bordered table-striped table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Supir</th>
                            <th>Kendaraan</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Harga</th>
                            <th>Jadwal Berangkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= esc($row['nama_user']) ?></td>
                                <td><?= esc($row['namakendaraan']) ?></td>
                                <td><?= esc($row['asal']) ?></td>
                                <td><?= esc($row['tujuan']) ?></td>
                                <td class="text-end"><?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= date('H:i', strtotime($row['jam'])) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($jadwal)): ?>
                <div class="alert alert-info rounded-3 text-center mt-3">
                    Tidak ada data jadwal berangkat untuk tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tableJadwal').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>

<?= $this->endSection() ?>