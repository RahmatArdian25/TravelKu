<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow rounded-4 border-success">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-route"></i> <?= esc($title) ?></h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="<?= base_url("laporan/rute/pdf") ?>" target="_blank" class="btn btn-danger rounded-pill">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <div class="table-responsive">
                <table id="tableRute" class="table table-bordered table-striped table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Harga</th>
                            <th>Jadwal Keberangkatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rute as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= esc($row['asal']) ?></td>
                                <td><?= esc($row['tujuan']) ?></td>
                                <td><?= 'Rp ' . number_format($row['harga'], 0, ',', '.') ?></td>
                                <td><?= esc($row['jadwalberangkat']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($rute)): ?>
                <div class="alert alert-info rounded-3 text-center mt-3">
                    Tidak ada data rute yang ditemukan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tableRute').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>

<?= $this->endSection() ?>