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
                    <div class="col-md-3">
                        <select name="bulan" class="form-select rounded-pill">
                            <?php foreach ($months as $key => $month): ?>
                                <option value="<?= $key ?>" <?= $key == $bulan ? 'selected' : '' ?>>
                                    <?= $month ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="tahun" class="form-select rounded-pill">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success rounded-pill">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="<?= base_url("laporan/pemesanan/pdf?bulan={$bulan}&tahun={$tahun}") ?>" 
                           target="_blank" class="btn btn-danger rounded-pill">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="tablePemesanan" class="table table-bordered table-striped table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Rute</th>
                            <th>Nama Pemesan</th>
                            <th>Tanggal</th>
                            <th>Jumlah Orang</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pemesanan as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= esc($row['asal']) ?> - <?= esc($row['tujuan']) ?></td>
                                <td><?= esc($row['nama_user']) ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td class="text-center"><?= esc($row['jumlah_orang']) ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $row['status'] == 'sudah berangkat' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= esc($row['status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($pemesanan)): ?>
                <div class="alert alert-info rounded-3 text-center mt-3">
                    Tidak ada data pemesanan yang ditemukan untuk bulan ini.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tablePemesanan').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'print'
            ]
        });
    });
</script>

<?= $this->endSection() ?>