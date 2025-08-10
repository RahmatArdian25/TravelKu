<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow rounded-4 border-success">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> <?= esc($title) ?></h4>
        </div>
        <div class="card-body">
            <form method="get" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <select name="tahun" class="form-select rounded-pill">
                            <?php foreach ($years as $year): ?>
                                <option value="<?= $year ?>" <?= $year == $tahun ? 'selected' : '' ?>>
                                    <?= $year ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success rounded-pill">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-7 text-end">
                        <a href="<?= base_url("laporan/pemesanan-tahunan/pdf?tahun={$tahun}") ?>" target="_blank" class="btn btn-danger rounded-pill">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="tablePemesananTahunan" class="table table-bordered table-striped table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Total Pemesanan</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $months = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        
                        $grandTotalPemesanan = 0;
                        $grandTotalPendapatan = 0;
                        
                        foreach ($months as $monthNum => $monthName): 
                            $monthData = array_filter($pemesananTahunan, function($item) use ($monthNum) {
                                return date('m', strtotime($item['tanggal'])) == $monthNum;
                            });
                            
                            $totalPemesanan = count($monthData);
                            $totalPendapatan = array_sum(array_column($monthData, 'total'));
                            
                            $grandTotalPemesanan += $totalPemesanan;
                            $grandTotalPendapatan += $totalPendapatan;
                        ?>
                            <tr>
                                <td class="text-center"><?= $monthNum ?></td>
                                <td><?= $monthName ?></td>
                                <td class="text-center"><?= $totalPemesanan ?></td>
                                <td class="text-end">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-success">
                        <tr>
                            <th colspan="2" class="text-center">Total</th>
                            <th class="text-center"><?= $grandTotalPemesanan ?></th>
                            <th class="text-end">Rp <?= number_format($grandTotalPendapatan, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tablePemesananTahunan').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            ordering: false
        });
    });
</script>

<?= $this->endSection() ?>