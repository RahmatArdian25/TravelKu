<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $sessionUser = session()->get('user'); ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Tambah Pemesanan Baru</h4>
        </div>
        <div class="card-body bg-light rounded-bottom-4">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger">
                    <?= esc(session('error')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('pemesanan/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="idjadwal" id="idjadwal" value="">

                <!-- Pemesan -->
                <?php if ($sessionUser['status'] === 'penumpang') : ?>
                    <input type="hidden" name="id_user" value="<?= esc($sessionUser['id']) ?>">
                <?php else : ?>
                    <div class="mb-3">
                        <label for="id_user" class="form-label">Pemesan <span class="text-danger">*</span></label>
                        <select name="id_user" id="select-user" class="form-select rounded-3 <?= ($validation->hasError('id_user')) ? 'is-invalid' : '' ?>">
                            <option value="">Pilih Pemesan</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['id_user'] ?>" <?= old('id_user') == $user['id_user'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?> (<?= $user['email'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('id_user') ?: 'Pemesan wajib dipilih' ?>
                        </div>
                    </div>
                <?php endif; ?>

                 <!-- Tanggal -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control rounded-3 <?= ($validation->hasError('tanggal')) ? 'is-invalid' : '' ?>" 
                        value="<?= esc($tanggal) ?>" min="<?= date('Y-m-d') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('tanggal') ?: 'Tanggal wajib diisi dan harus valid' ?>
                    </div>
                </div>

                <!-- Rute -->
                <div class="mb-3">
                    <label for="idrute" class="form-label">Rute <span class="text-danger">*</span></label>
                    <select name="idrute" id="idrute" class="form-select rounded-3 <?= ($validation->hasError('idrute')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Rute</option>
                        <?php foreach ($rutes as $rute) : ?>
                            <option value="<?= $rute['idrute'] ?>" 
                                    data-harga="<?= $rute['harga'] ?>" 
                                    data-kendaraan="<?= $rute['idkendaraan'] ?>"
                                    data-jadwalberangkat="<?= $rute['jadwalberangkat'] ?>"
                                    <?= old('idrute') == $rute['idrute'] ? 'selected' : '' ?>>
                                <?= $rute['asal'] ?> - <?= $rute['tujuan'] ?> (Rp <?= number_format($rute['harga'], 0, ',', '.') ?>)(<?= $rute['jadwalberangkat'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('idrute') ?: 'Rute wajib dipilih' ?>
                    </div>
                </div>

               
                <!-- Jadwal Info -->
                <div class="mb-3">
                    <label class="form-label">Informasi Jadwal</label>
                    <div id="jadwal-info" class="form-control rounded-3 bg-light py-2">
                        Pilih rute dan tanggal untuk melihat informasi jadwal
                    </div>
                </div>

                <!-- Status -->
                <input type="hidden" name="status" id="status" value="belum bayar">

                <!-- Bukti Pembayaran -->
                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" 
                        class="form-control rounded-3 <?= ($validation->hasError('bukti_pembayaran')) ? 'is-invalid' : '' ?>"
                        onchange="updateStatusBasedOnFile(this)">
                    <div class="invalid-feedback">
                        <?= $validation->getError('bukti_pembayaran') ?>
                    </div>
                    <p><small class="text-muted">Transfer Pembayaran ke Rekening BRI : 0669 0104 0802 509 Ardian atau ke Dana : 0831 8710 3434</small></p>
                    <!-- <p><small class="text-muted">Upload bukti pembayaran untuk mengubah status menjadi "Sudah Bayar Belum Konfirmasi"</small></p> -->
                    <!-- <small class="fs-6 text-danger">Amankan kursi Anda"</small> -->
                    <!-- <p><small class="fs-6 text-danger">Lakukan pembayaran dalam 10 menit. Jika lewat batas waktu, pesanan akan otomatis dibatalkan"</small></p> -->
                     
                    <span class="badge rounded-pill text-bg-danger fs-6">Amankan kursi Anda Lakukan pembayaran dalam 10 menit. Jika lewat batas waktu, pesanan akan otomatis dibatalkan</span>
                    
                </div>

                <!-- Penumpang Section -->
                <div class="mb-4">
                    <h5 class="mb-3">Data Penumpang</h5>
                    <?php if (session()->has('errors.penumpang')) : ?>
                        <div class="alert alert-danger py-2">
                            <?= esc(session('errors.penumpang')) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('errors.kursi')) : ?>
                        <div class="alert alert-danger py-2">
                            <?= esc(session('errors.kursi')) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div id="penumpang-container">
                        <?php 
                        $penumpangData = old('penumpang', [['namapenumpang' => '', 'jeniskelamin' => '', 'kursikendaraanid' => '']]);
                        $index = 0;
                        ?>
                        
                        <?php foreach ($penumpangData as $index => $penumpang) : ?>
                            <div class="penumpang-item card mb-3 p-3">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nama Penumpang <span class="text-danger">*</span></label>
                                        <input type="text" name="penumpang[<?= $index ?>][namapenumpang]" 
                                            class="form-control rounded-3 <?= ($validation->hasError("penumpang.$index.namapenumpang")) ? 'is-invalid' : '' ?>" 
                                            value="<?= esc($penumpang['namapenumpang'] ?? '') ?>" required
                                            <?= $index === 0 ? 'id="first-passenger-name"' : '' ?>>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError("penumpang.$index.namapenumpang") ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="penumpang[<?= $index ?>][jeniskelamin]" 
                                            class="form-select rounded-3 <?= ($validation->hasError("penumpang.$index.jeniskelamin")) ? 'is-invalid' : '' ?>" required
                                            <?= $index === 0 ? 'id="first-passenger-gender"' : '' ?>>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" <?= ($penumpang['jeniskelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="Perempuan" <?= ($penumpang['jeniskelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError("penumpang.$index.jeniskelamin") ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kursi Kendaraan <span class="text-danger">*</span></label>
                                        <select name="penumpang[<?= $index ?>][kursikendaraanid]" 
                                            class="form-select kursi-select rounded-3 <?= ($validation->hasError("penumpang.$index.kursikendaraanid")) ? 'is-invalid' : '' ?>" required>
                                            <option value="">Pilih Kursi</option>
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError("penumpang.$index.kursikendaraanid") ?>
                                        </div>
                                    </div>
                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger hapus-penumpang rounded-3" <?= $index === 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="tambah-penumpang" class="btn btn-sm btn-outline-success rounded-3 mt-2">
                        <i class="fas fa-plus me-1"></i> Tambah Penumpang
                    </button>
                </div>

                <!-- Jumlah Orang -->
                <div class="mb-3">
                    <label for="jumlah_orang" class="form-label">Jumlah Orang <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_orang" id="jumlah_orang" 
                        class="form-control rounded-3 <?= ($validation->hasError('jumlah_orang')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('jumlah_orang', count($penumpangData)) ?>" readonly>
                    <div class="invalid-feedback">
                        <?= $validation->getError('jumlah_orang') ?>
                    </div>
                </div>

                <!-- Total -->
                <div class="mb-3">
                    <label for="total" class="form-label">Total Harga <span class="text-danger">*</span></label>
                    <input type="number" name="total" id="total" 
                        class="form-control rounded-3 <?= ($validation->hasError('total')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('total') ?>" readonly>
                    <div class="invalid-feedback">
                        <?= $validation->getError('total') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('pemesanan') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="penumpang-template">
    <div class="penumpang-item card mb-3 p-3">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nama Penumpang <span class="text-danger">*</span></label>
                <input type="text" name="penumpang[][namapenumpang]" class="form-control rounded-3" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="penumpang[][jeniskelamin]" class="form-select rounded-3" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Kursi Kendaraan <span class="text-danger">*</span></label>
                <select name="penumpang[][kursikendaraanid]" class="form-select kursi-select rounded-3" required>
                    <option value="">Pilih Kursi</option>
                </select>
            </div>
            <div class="col-md-1 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-danger hapus-penumpang rounded-3">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const penumpangContainer = document.getElementById('penumpang-container');
    const tambahPenumpangBtn = document.getElementById('tambah-penumpang');
    const penumpangTemplate = document.getElementById('penumpang-template');
    const jumlahOrangInput = document.getElementById('jumlah_orang');
    const totalInput = document.getElementById('total');
    const ruteSelect = document.getElementById('idrute');
    const tanggalInput = document.getElementById('tanggal');
    const jadwalInfo = document.getElementById('jadwal-info');
    const idjadwalInput = document.getElementById('idjadwal');
 document.getElementById('tanggal').addEventListener('change', async function () {
    const selectedDate = this.value;
    const ruteSelect = document.getElementById('idrute');

    // Kosongkan isi rute dan tambahkan loading
    ruteSelect.innerHTML = '<option>Memuat rute...</option>';

    try {
        const response = await fetch(`<?= base_url('pemesanan/getAvailableRutes') ?>?tanggal=${selectedDate}`);
        const data = await response.json();

        ruteSelect.innerHTML = ''; // kosongkan lagi
        if (data.length === 0) {
            ruteSelect.innerHTML = '<option value="">Tidak ada rute tersedia</option>';
            return;
        }

        data.forEach(rute => {
            const option = document.createElement('option');
            option.value = rute.idrute;
            option.textContent = `${rute.asal} - ${rute.tujuan} (Rp ${parseInt(rute.harga).toLocaleString('id-ID')}) (${rute.jadwalberangkat}, Sisa Kursi: ${rute.kursi_tersedia})`;

            // Set atribut data tambahan
            option.dataset.harga = rute.harga;
            option.dataset.kendaraan = rute.idkendaraan;
            option.dataset.jadwalberangkat = rute.jadwalberangkat;

            ruteSelect.appendChild(option);
        });

    } catch (error) {
        ruteSelect.innerHTML = '<option value="">Gagal memuat rute</option>';
        console.error('Gagal fetch rute:', error);
    }
});
    // Initialize
    updateJumlahOrang();
    calculateTotal();
    if (ruteSelect.value && tanggalInput.value) {
        updateJadwalInfo();
        updateKursiOptions();
    }

    // Add penumpang field
    tambahPenumpangBtn.addEventListener('click', addPenumpangField);

    // Remove penumpang field
    penumpangContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('hapus-penumpang') && !e.target.disabled) {
            e.target.closest('.penumpang-item').remove();
            updateJumlahOrang();
            calculateTotal();
            updateKursiOptions();
        }
    });

    // Calculate total when rute changes
    ruteSelect.addEventListener('change', function() {
        calculateTotal();
        updateJadwalInfo();
        updateKursiOptions();
    });

    // Update when date changes
    tanggalInput.addEventListener('change', function() {
        calculateTotal();
        updateJadwalInfo();
        updateKursiOptions();
    });

    function addPenumpangField() {
        const clone = penumpangTemplate.content.cloneNode(true);
        const lastIndex = penumpangContainer.querySelectorAll('.penumpang-item').length;
        
        const inputs = clone.querySelectorAll('[name^="penumpang["]');
        inputs.forEach(input => {
            const name = input.getAttribute('name')
                .replace('[]', `[${lastIndex}]`);
            input.setAttribute('name', name);
        });
        
        penumpangContainer.appendChild(clone);
        updateJumlahOrang();
        calculateTotal();
        updateKursiOptions();
    }

    function updateJumlahOrang() {
        const jumlah = document.querySelectorAll('.penumpang-item').length;
        jumlahOrangInput.value = jumlah;
    }

    function calculateTotal() {
        const hargaPerOrang = ruteSelect.options[ruteSelect.selectedIndex]?.dataset.harga || 0;
        const jumlahOrang = document.querySelectorAll('.penumpang-item').length;
        totalInput.value = hargaPerOrang * jumlahOrang;
    }

    async function updateJadwalInfo() {
        const ruteId = ruteSelect.value;
        const tanggal = tanggalInput.value;
        
        if (!ruteId || !tanggal) {
            jadwalInfo.textContent = 'Pilih rute dan tanggal untuk melihat informasi jadwal';
            return;
        }
        
        try {
            const response = await fetch(`<?= base_url('pemesanan/checkJadwalExist') ?>?idrute=${ruteId}&tanggal=${tanggal}`);
            const data = await response.json();
            
            // Get jadwalberangkat from selected route
            const selectedRute = ruteSelect.options[ruteSelect.selectedIndex];
            const jadwalBerangkat = selectedRute.dataset.jadwalberangkat || 'pagi';
            
            // Determine time based on jadwalberangkat
            let jam = '10:00'; // Default for pagi
            if (jadwalBerangkat === 'siang') {
                jam = '14:00';
            } else if (jadwalBerangkat === 'malam') {
                jam = '19:00';
            }
            
            // Create time display mapping
            const waktuMap = {
                'pagi': 'Pagi (10:00)',
                'siang': 'Siang (14:00)',
                'malam': 'Malam (19:00)'
            };
            const waktuTampilan = waktuMap[jadwalBerangkat] || 'Pagi (10:00)';
            
            if (data.exists) {
                jadwalInfo.innerHTML = `
                    <strong>Jadwal Tersedia:</strong><br>
                    Kendaraan: ${data.kendaraan}<br>
                    Supir: ${data.supir}<br>
                    Waktu: ${waktuTampilan}
                `;
                idjadwalInput.value = data.idjadwal;
            } else {
                const kendaraan = ruteSelect.options[ruteSelect.selectedIndex]?.dataset.kendaraan || 'Belum dipilih';
                jadwalInfo.innerHTML = `
                    <strong>Jadwal Baru Akan Dibuat:</strong><br>
                    Kendaraan: ${kendaraan}<br>
                    Tanggal: ${tanggal}<br>
                    Waktu: ${waktuTampilan}
                `;
                idjadwalInput.value = '';
            }
        } catch (error) {
            console.error('Error updating jadwal info:', error);
            jadwalInfo.textContent = 'Gagal memuat informasi jadwal';
        }
    }

   async function updateKursiOptions() {
    const ruteId = ruteSelect.value;
    const tanggal = tanggalInput.value;
    
    if (!ruteId || !tanggal) {
        document.querySelectorAll('.kursi-select').forEach(select => {
            select.innerHTML = '<option value="">Pilih rute dan tanggal terlebih dahulu</option>';
        });
        return;
    }
    
    try {
        // Save previously selected values
        const selectedValues = {};
        document.querySelectorAll('.kursi-select').forEach((select, index) => {
            selectedValues[`select-${index}`] = select.value;
        });

        // Show loading
        document.querySelectorAll('.kursi-select').forEach(select => {
            select.innerHTML = '<option value="">Memuat kursi...</option>';
            select.disabled = true;
        });

        const selectedRute = ruteSelect.options[ruteSelect.selectedIndex];
        const idkendaraan = selectedRute.dataset.kendaraan;
        
        if (!idkendaraan) {
            throw new Error('Kendaraan tidak ditemukan untuk rute ini');
        }

        // Ambil kursi yang tersedia
        const availableResponse = await fetch(`<?= base_url('pemesanan/getAvailableKursi') ?>?idrute=${ruteId}&tanggal=${tanggal}`);
        
        if (!availableResponse.ok) {
            const errorData = await availableResponse.json();
            throw new Error(errorData.error || 'Gagal mengambil data kursi tersedia');
        }
        
        const availableKursi = await availableResponse.json();

        // Update all seat selects while maintaining previously selected values
        document.querySelectorAll('.kursi-select').forEach((select, index) => {
            const previousValue = selectedValues[`select-${index}`];
            updateKursiSelect(select, availableKursi, previousValue);
            select.disabled = false;
        });
    } catch (error) {
        console.error('Error:', error);
        document.querySelectorAll('.kursi-select').forEach(select => {
            select.innerHTML = `<option value="">Error: ${error.message}</option>`;
            select.disabled = false;
        });
    }
}

function updateKursiSelect(select, availableKursi, previousValue = null) {
    // Get all seat values already selected in the form
    const selectedKursiValues = [];
    document.querySelectorAll('.kursi-select').forEach(otherSelect => {
        if (otherSelect !== select && otherSelect.value) {
            selectedKursiValues.push(otherSelect.value);
        }
    });

    // Use previousValue if available, otherwise use current value
    const selectedValue = previousValue !== null ? previousValue : select.value;
    
    let html = '<option value="">Pilih Kursi</option>';
    
    if (!Array.isArray(availableKursi) || availableKursi.length === 0) {
        html = '<option value="">Tidak ada kursi tersedia</option>';
        select.innerHTML = html;
        return;
    }
    
    availableKursi.forEach(kursi => {
        if (!kursi.idkursi || !kursi.nomorkursi || !kursi.nama_kendaraan) {
            console.warn('Data kursi tidak valid:', kursi);
            return;
        }
        
        // Check if this seat is already selected by another passenger
        const isSelectedByOther = selectedKursiValues.includes(kursi.idkursi.toString());
        
        // If seat is already selected by another passenger, don't show it
        if (isSelectedByOther) {
            return;
        }
        
        html += `
            <option value="${kursi.idkursi}" 
                ${selectedValue == kursi.idkursi ? 'selected' : ''}>
                Kursi ${kursi.nomorkursi} (${kursi.nama_kendaraan})
            </option>
        `;
    });
    
    select.innerHTML = html;
    
    // If there was a previous value, set it back
    if (previousValue !== null) {
        select.value = previousValue;
    }
}
});

function updateStatusBasedOnFile(input) {
    const statusField = document.getElementById('status');
    if (input.files && input.files.length > 0) {
        statusField.value = 'sudah bayar belum konfirmasi';
    } else {
        statusField.value = 'belum bayar';
    }
}
</script>

<?= $this->endSection() ?>