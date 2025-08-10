<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Edit Jadwal Berangkat</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif ?>

            <form action="<?= base_url('jadwalberangkat/update/' . $jadwal['idjadwal']) ?>" method="post">
                <?= csrf_field() ?>

                <!-- Supir -->
                <div class="mb-3">
                    <label for="iduser" class="form-label">Supir</label>
                  <select name="iduser" id="iduser" class="form-select <?= ($validation->hasError('iduser')) ? 'is-invalid' : '' ?>" required>
    <option value="">Pilih Supir</option>
    <?php foreach ($supir as $s) : ?>
        <option value="<?= $s['id_user'] ?>" 
            <?= (old('iduser', $jadwal['iduser']) == $s['id_user']) ? 'selected' : '' ?>
            data-jadwal="<?= esc(json_encode($s['jadwal'])) ?>">
            <?= esc($s['nama_user']) ?>
        </option>
    <?php endforeach ?>
</select>
                    <div class="invalid-feedback"><?= $validation->getError('iduser') ?></div>
                    <small class="text-muted" id="supir-schedule-info"></small>
                </div>

              <!-- Kendaraan -->
<div class="mb-3">
    <label for="idkendaraan" class="form-label">Kendaraan</label>
    <select class="form-select" disabled>
        <option value="">Pilih Kendaraan</option>
        <?php foreach ($kendaraan as $k) : ?>
            <option value="<?= $k['idkendaraan'] ?>" <?= (old('idkendaraan', $jadwal['idkendaraan']) == $k['idkendaraan']) ? 'selected' : '' ?>>
                <?= esc($k['namakendaraan']) ?> (<?= esc($k['nopolisi_kendaraan']) ?>)
            </option>
        <?php endforeach ?>
    </select>
    <input type="hidden" name="idkendaraan" value="<?= old('idkendaraan', $jadwal['idkendaraan']) ?>">
</div>

<!-- Rute -->
<div class="mb-3">
    <label for="idrute" class="form-label">Rute</label>
    <select class="form-select" disabled>
        <option value="">Pilih Rute</option>
        <?php foreach ($rute as $r) : ?>
            <option value="<?= $r['idrute'] ?>" 
                <?= (old('idrute', $jadwal['idrute']) == $r['idrute']) ? 'selected' : '' ?>>
                <?= esc($r['asal']) ?> - <?= esc($r['tujuan']) ?>
            </option>
        <?php endforeach ?>
    </select>
    <input type="hidden" name="idrute" value="<?= old('idrute', $jadwal['idrute']) ?>">
</div>

<!-- Tanggal -->
<div class="mb-3">
    <label for="tanggal" class="form-label">Tanggal</label>
    <input type="date" name="tanggal" class="form-control" 
        value="<?= old('tanggal', $jadwal['tanggal']) ?>" readonly>
</div>

<!-- Jam -->
<div class="mb-3">
    <label for="jam" class="form-label">Jam</label>
    <input type="time" name="jam" class="form-control" 
        value="<?= old('jam', $jadwal['jam']) ?>" readonly>
</div>


                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('jadwalberangkat') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const supirSelect = document.getElementById('iduser');
    const ruteSelect = document.getElementById('idrute');
    const tanggalInput = document.getElementById('tanggal');
    const jamInput = document.getElementById('jam');
    const infoElement = document.getElementById('supir-schedule-info');

    // Function to update driver schedule info
    function updateSupirInfo() {
        const selectedOption = supirSelect.options[supirSelect.selectedIndex];
        if (selectedOption.value) {
            const jadwalSupir = JSON.parse(selectedOption.getAttribute('data-jadwal'));
            let info = 'Jadwal supir: ';
            
            if (jadwalSupir && jadwalSupir.length > 0) {
                jadwalSupir.forEach(jadwal => {
                    info += `${jadwal.tanggal} (${jadwal.jam}), `;
                });
                info = info.slice(0, -2); // Remove last comma
            } else {
                info += 'Tidak ada jadwal';
            }
            
            infoElement.textContent = info;
        } else {
            infoElement.textContent = '';
        }
    }

    // Function to set jam based on rute's jadwalberangkat
    function setJamBasedOnRute() {
        const selectedRute = ruteSelect.options[ruteSelect.selectedIndex];
        if (selectedRute.value) {
            const jadwalBerangkat = selectedRute.getAttribute('data-jadwal');
            
            // Set jam based on jadwalberangkat
            if (jadwalBerangkat === 'pagi') {
                jamInput.value = '10:00';
            } else if (jadwalBerangkat === 'siang') {
                jamInput.value = '14:00';
            } else if (jadwalBerangkat === 'malam') {
                jamInput.value = '19:00';
            }
        }
    }

    // Event listeners
    supirSelect.addEventListener('change', updateSupirInfo);
    ruteSelect.addEventListener('change', setJamBasedOnRute);

    // Initialize
    updateSupirInfo();
    setJamBasedOnRute();
});
</script>

<?= $this->endSection() ?>