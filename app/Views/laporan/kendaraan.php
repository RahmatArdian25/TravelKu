<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow rounded-4 border-success">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-car"></i> <?= esc($title) ?></h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="<?= base_url("laporan/kendaraan/pdf") ?>" target="_blank" class="btn btn-danger rounded-pill">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <div class="table-responsive">
                <table id="tableKendaraan" class="table table-bordered table-striped table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>No Polisi Kendaraan</th>
                            <th>Nama Kendaraan</th>
                            <th>Jumlah Kursi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kendaraan as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= esc($row['nopolisi_kendaraan']) ?></td>
                                <td><?= esc($row['namakendaraan']) ?></td>
                                <td class="text-center"><?= esc($row['jumlahkursi']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($kendaraan)): ?>
                <div class="alert alert-info rounded-3 text-center mt-3">
                    Tidak ada data kendaraan yang ditemukan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tableKendaraan').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>

<?= $this->endSection() ?>