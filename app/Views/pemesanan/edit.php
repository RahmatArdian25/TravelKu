<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $sessionUser = session()->get('user'); ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">Edit Pemesanan</h4>
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

            <form action="<?= base_url('pemesanan/update/' . $pemesanan['idpemesanan']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="idjadwal" id="idjadwal" value="<?= $pemesanan['idjadwal'] ?? '' ?>">

                <!-- Pemesan Section -->
                <?php if ($sessionUser['status'] === 'penumpang') : ?>
                    <input type="hidden" name="id_user" value="<?= esc($sessionUser['id']) ?>">
                <?php else : ?>
                    <div class="mb-3">
                        <label for="id_user" class="form-label">Pemesan <span class="text-danger">*</span></label>
                        <select name="id_user" id="select-user" class="form-select rounded-3 <?= ($validation->hasError('id_user')) ? 'is-invalid' : '' ?>">
                            <option value="">Pilih Pemesan</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['id_user'] ?>" <?= old('id_user', $pemesanan['id_user']) == $user['id_user'] ? 'selected' : '' ?>>
                                    <?= $user['nama_user'] ?> (<?= $user['email'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('id_user') ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Rute Section -->
                <div class="mb-3">
                    <label for="idrute" class="form-label">Rute <span class="text-danger">*</span></label>
                    <select name="idrute" id="idrute" class="form-select rounded-3 <?= ($validation->hasError('idrute')) ? 'is-invalid' : '' ?>">
                        <option value="">Pilih Rute</option>
                        <?php foreach ($rutes as $rute) : ?>
                            <option value="<?= $rute['idrute'] ?>" 
                                    data-harga="<?= $rute['harga'] ?>" 
                                    data-kendaraan="<?= $rute['idkendaraan'] ?>"
                                    data-jadwalberangkat="<?= $rute['jadwalberangkat'] ?>"
                                    <?= old('idrute', $pemesanan['idrute']) == $rute['idrute'] ? 'selected' : '' ?>>
                                <?= $rute['asal'] ?> - <?= $rute['tujuan'] ?> (Rp <?= number_format($rute['harga'], 0, ',', '.') ?>) (<?= $rute['jadwalberangkat'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('idrute') ?>
                    </div>
                </div>

                <!-- Tanggal Section -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control rounded-3 <?= ($validation->hasError('tanggal')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('tanggal', $pemesanan['tanggal']) ?>" min="<?= date('Y-m-d') ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('tanggal') ?>
                    </div>
                </div>

                <!-- Jadwal Info Section -->
                <div class="mb-3">
                    <label class="form-label">Informasi Jadwal</label>
                    <div id="jadwal-info" class="form-control rounded-3 bg-light py-2">
                        <?php if (!empty($jadwal) && !empty($kendaraan)) : ?>
                            <?php 
                            $waktuMap = [
                                'pagi' => 'Pagi (10:00)',
                                'siang' => 'Siang (14:00)',
                                'malam' => 'Malam (19:00)'
                            ];
                            $waktuTampilan = $waktuMap[$jadwal['jam']] ?? $jadwal['jam'];
                            ?>
                            <strong>Jadwal Tersedia:</strong><br>
                            Kendaraan: <?= $kendaraan['namakendaraan'] ?> (<?= $kendaraan['nopolisi_kendaraan'] ?>)<br>
                            Supir: <?= !empty($supir) ? $supir['nama_user'] : 'Belum ditentukan' ?><br>
                            Waktu: <?= $waktuTampilan ?>
                        <?php else : ?>
                            Pilih rute dan tanggal untuk melihat informasi jadwal
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Section -->
                <?php if ($sessionUser['status'] === 'penumpang') : ?>
                    <input type="hidden" name="status" id="status" value="<?= $pemesanan['status'] ?>">
                <?php else : ?>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select rounded-3 <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>">
                            <option value="">Pilih Status</option>
                            <option value="belum bayar" <?= old('status', $pemesanan['status']) == 'belum bayar' ? 'selected' : '' ?>>Belum Bayar</option>
                            <option value="sudah bayar belum konfirmasi" <?= old('status', $pemesanan['status']) == 'sudah bayar belum konfirmasi' ? 'selected' : '' ?>>Sudah Bayar Belum Konfirmasi</option>
                            <option value="pembayaran sudah di konfirmasi" <?= old('status', $pemesanan['status']) == 'pembayaran sudah di konfirmasi' ? 'selected' : '' ?>>Pembayaran Sudah di Konfirmasi</option>
                            <option value="sudah berangkat" <?= old('status', $pemesanan['status']) == 'sudah berangkat' ? 'selected' : '' ?>>Sudah Berangkat</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('status') ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Bukti Pembayaran Section -->
                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" 
                        class="form-control rounded-3 <?= ($validation->hasError('bukti_pembayaran')) ? 'is-invalid' : ''?>"
                        onchange="updateStatusBasedOnFile(this)">
                    <div class="invalid-feedback">
                        <?= $validation->getError('bukti_pembayaran') ?>
                    </div>
                    <?php if (!empty($pemesanan['bukti_pembayaran'])) : ?>
                        <div class="mt-2">
                            <small>Bukti Pembayaran Saat Ini:</small>
                            <img src="<?= base_url('uploads/bukti_pembayaran/' . $pemesanan['bukti_pembayaran']) ?>" class="img-thumbnail mt-2" style="max-height: 200px;">
                            <input type="hidden" name="old_bukti_pembayaran" value="<?= $pemesanan['bukti_pembayaran'] ?>">
                        </div>
                    <?php endif; ?>
                    <small class="text-muted">Upload bukti pembayaran untuk mengubah status menjadi "Sudah Bayar Belum Konfirmasi"</small>
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
                        $penumpangData = old('penumpang', $detail_pemesanan);
                        $index = 0;
                        
                        if ($sessionUser['status'] === 'penumpang') {
                            if (empty($penumpangData[0]['namapenumpang'])) {
                                $penumpangData[0]['namapenumpang'] = $sessionUser['nama'];
                            }
                            if (empty($penumpangData[0]['jeniskelamin'])) {
                                $penumpangData[0]['jeniskelamin'] = $sessionUser['jenis_kelamin'] ?? '';
                            }
                        } elseif (old('id_user', $pemesanan['id_user'])) {
                            $selectedUser = null;
                            foreach ($users as $user) {
                                if ($user['id_user'] == old('id_user', $pemesanan['id_user'])) {
                                    $selectedUser = $user;
                                    break;
                                }
                            }
                            if ($selectedUser) {
                                if (empty($penumpangData[0]['namapenumpang'])) {
                                    $penumpangData[0]['namapenumpang'] = $selectedUser['nama_user'];
                                }
                                if (empty($penumpangData[0]['jeniskelamin'])) {
                                    $penumpangData[0]['jeniskelamin'] = $selectedUser['jenis_kelamin'] ?? '';
                                }
                            }
                        }
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
                                            <option value="Laki-laki" <?= (isset($penumpang['jeniskelamin']) && $penumpang['jeniskelamin'] == 'Laki-laki' ? 'selected' : '') ?>>Laki-laki</option>
                                            <option value="Perempuan" <?= (isset($penumpang['jeniskelamin']) && $penumpang['jeniskelamin'] == 'Perempuan' ? 'selected' : '' )?>>Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError("penumpang.$index.jeniskelamin") ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kursi Kendaraan <span class="text-danger">*</span></label>
                                        <select name="penumpang[<?= $index ?>][kursikendaraanid]" 
                                            class="form-select kursi-select rounded-3 <?= ($validation->hasError("penumpang.$index.kursikendaraanid")) ? 'is-invalid' : '' ?>" required
                                            data-selected="<?= isset($penumpang['kursikendaraanid']) ? $penumpang['kursikendaraanid'] : '' ?>">
                                            <option value="">Pilih Kursi</option>
                                            <?php if (!empty($kursi)) : ?>
                                                <?php foreach ($kursi as $k) : ?>
                                                    <?php 
                                                    $jadwalMap = [
                                                        'pagi' => 'Pagi (10:00)',
                                                        'siang' => 'Siang (14:00)',
                                                        'malam' => 'Malam (19:00)'
                                                    ];
                                                    $jadwalTampilan = $jadwalMap[$k['jadwalberangkat']] ?? $k['jadwalberangkat'];
                                                    ?>
                                                    <option value="<?= $k['idkursi'] ?>" 
                                                        <?= (isset($penumpang['kursikendaraanid']) && $penumpang['kursikendaraanid'] == $k['idkursi']) ? 'selected' : '' ?>
                                                        data-nomor="<?= $k['nomorkursi'] ?>"
                                                        data-kendaraan="<?= $k['nama_kendaraan'] ?? $kendaraan['namakendaraan'] ?? 'Kendaraan Tidak Diketahui' ?>"
                                                        data-jadwal="<?= $k['jadwalberangkat'] ?>">
                                                        Kursi <?= $k['nomorkursi'] ?> (<?= $k['nama_kendaraan'] ?? $kendaraan['namakendaraan'] ?? 'Kendaraan Tidak Diketahui' ?>) - <?= $jadwalTampilan ?>
                                                        <?= (isset($penumpang['kursikendaraanid']) && $penumpang['kursikendaraanid'] == $k['idkursi']) ? ' - Dipilih sebelumnya' : '' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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

                <!-- Jumlah Orang Section -->
                <div class="mb-3">
                    <label for="jumlah_orang" class="form-label">Jumlah Orang <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_orang" id="jumlah_orang" 
                        class="form-control rounded-3 <?= ($validation->hasError('jumlah_orang')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('jumlah_orang', $pemesanan['jumlah_orang']) ?>" readonly>
                    <div class="invalid-feedback">
                        <?= $validation->getError('jumlah_orang') ?>
                    </div>
                </div>

                <!-- Total Section -->
                <div class="mb-3">
                    <label for="total" class="form-label">Total Harga <span class="text-danger">*</span></label>
                    <input type="number" name="total" id="total" 
                        class="form-control rounded-3 <?= ($validation->hasError('total')) ? 'is-invalid' : '' ?>" 
                        value="<?= old('total', $pemesanan['total']) ?>" readonly>
                    <div class="invalid-feedback">
                        <?= $validation->getError('total') ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('pemesanan') ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    <button type="submit" class="btn btn-success rounded-3 shadow">Simpan Perubahan</button>
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
    const userSelect = document.getElementById('select-user');
    const jadwalInfo = document.getElementById('jadwal-info');
    const idjadwalInput = document.getElementById('idjadwal');
    const firstNameInput = document.getElementById('first-passenger-name');
    const firstGenderInput = document.getElementById('first-passenger-gender');
    
    // Mapping for time display
    const waktuMap = {
        'pagi': 'Pagi (10:00)',
        'siang': 'Siang (14:00)',
        'malam': 'Malam (19:00)'
    };

    // Initialize
    updateJumlahOrang();
    calculateTotal();
    if (ruteSelect.value && tanggalInput.value) {
        updateJadwalInfo().then(() => {
            initializeSelectedSeats();
        });
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
        updateJadwalInfo().then(updateKursiOptions);
    });

    // Update when date changes
    tanggalInput.addEventListener('change', function() {
        calculateTotal();
        updateJadwalInfo().then(updateKursiOptions);
        
        const userId = userSelect ? userSelect.value : null;
        if (userId) {
            checkExistingBooking(userId, this.value);
        }
    });

    // For admin view - update first passenger name when user changes
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.text && firstNameInput) {
                const userName = selectedOption.text.split('(')[0].trim();
                firstNameInput.value = userName;
            }
            
            if (tanggalInput.value) {
                checkExistingBooking(this.value, tanggalInput.value);
            }
        });
    }

    // Update kursi options when any kursi select changes
    penumpangContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('kursi-select')) {
            updateKursiOptions();
        }
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
            
            // Create time display
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

            const availableResponse = await fetch(`<?= base_url('pemesanan/getAvailableKursi') ?>?idrute=${ruteId}&tanggal=${tanggal}&exclude=<?= $pemesanan['idpemesanan'] ?>`);
            
            if (!availableResponse.ok) {
                const errorData = await availableResponse.json();
                throw new Error(errorData.error || 'Gagal mengambil data kursi tersedia');
            }
            
            const availableKursi = await availableResponse.json();

            const allKursiResponse = await fetch(`<?= base_url('pemesanan/getKursiByKendaraan/') ?>${idkendaraan}`);
            
            if (!allKursiResponse.ok) {
                const errorData = await allKursiResponse.json();
                throw new Error(errorData.error || 'Gagal mengambil data semua kursi');
            }
            
            const allKursi = await allKursiResponse.json();

            // Update all seat selects while maintaining previously selected values
            document.querySelectorAll('.kursi-select').forEach((select, index) => {
                const previousValue = selectedValues[`select-${index}`];
                updateKursiSelect(select, allKursi, availableKursi, previousValue);
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

    function updateKursiSelect(select, allKursi, availableKursi, previousValue = null) {
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
        
        const allAvailable = !availableKursi || !Array.isArray(availableKursi) || availableKursi.length === 0;
        
        if (!Array.isArray(allKursi)) {
            html = '<option value="">Data kursi tidak valid</option>';
            select.innerHTML = html;
            return;
        }
        
        allKursi.forEach(kursi => {
            if (!kursi.idkursi || !kursi.nomorkursi || !kursi.jadwalberangkat) {
                console.warn('Data kursi tidak valid:', kursi);
                return;
            }
            
            const isAvailable = allAvailable || 
                              (Array.isArray(availableKursi) && availableKursi.some(ak => ak.idkursi == kursi.idkursi));
            
            // Check if this seat is already selected by another passenger
            const isSelectedByOther = selectedKursiValues.includes(kursi.idkursi.toString());
            
            // If seat is already selected by another passenger and not the current selected value, skip it
            if (isSelectedByOther && selectedValue != kursi.idkursi) {
                return;
            }
            
            const isSelected = selectedValue == kursi.idkursi;
            const selectedClass = isSelected ? 'text-success fw-bold' : '';
            const kendaraanNama = kursi.nama_kendaraan || 'Kendaraan Tidak Diketahui';
            
            // Format waktu untuk tampilan
            const waktuTampilan = waktuMap[kursi.jadwalberangkat] || kursi.jadwalberangkat;
            
            html += `
                <option value="${kursi.idkursi}" 
                    class="${selectedClass}"
                    ${isSelected ? 'selected' : ''}
                    ${(!isAvailable && !isSelected) || (isSelectedByOther && !isSelected) ? 'disabled' : ''}>
                    Kursi ${kursi.nomorkursi} (${kendaraanNama}) - ${waktuTampilan}
                    ${!isAvailable && !isSelected ? ' - Sudah dipesan' : ''}
                    ${isSelectedByOther && !isSelected ? ' - Sudah dipilih penumpang lain' : ''}
                    ${isSelected ? ' - Dipilih sebelumnya' : ''}
                </option>
            `;
        });
        
        select.innerHTML = html;
        
        // If previous value exists but not in options, add it back
        if (selectedValue && !select.querySelector(`option[value="${selectedValue}"]`)) {
            const selectedKursi = allKursi.find(k => k.idkursi == selectedValue);
            const nomorKursi = selectedKursi ? selectedKursi.nomorkursi : 'Tidak diketahui';
            const kendaraanNama = selectedKursi ? (selectedKursi.nama_kendaraan || 'Kendaraan Tidak Diketahui') : 'Kendaraan Tidak Diketahui';
            const waktuTampilan = selectedKursi ? (waktuMap[selectedKursi.jadwalberangkat] || selectedKursi.jadwalberangkat) : 'Tidak diketahui';
            
            const option = document.createElement('option');
            option.value = selectedValue;
            option.textContent = `Kursi ${nomorKursi} (${kendaraanNama}) - ${waktuTampilan} - Tidak tersedia`;
            option.selected = true;
            option.className = 'text-danger fw-bold';
            option.disabled = true;
            select.insertBefore(option, select.firstChild.nextSibling);
        }
    }

    function initializeSelectedSeats() {
        document.querySelectorAll('.kursi-select').forEach(select => {
            const selectedValue = select.dataset.selected;
            if (selectedValue) {
                select.value = selectedValue;
                
                const selectedOption = select.querySelector(`option[value="${selectedValue}"]`);
                if (selectedOption) {
                    selectedOption.classList.add('text-success', 'fw-bold');
                    selectedOption.textContent += ' (Dipilih sebelumnya)';
                }
            }
        });
    }

    async function checkExistingBooking(userId, tanggal) {
        try {
            const response = await fetch(`<?= base_url('pemesanan/checkExistingBooking') ?>?user_id=${userId}&tanggal=${tanggal}&exclude=<?= $pemesanan['idpemesanan'] ?>`);
            const data = await response.json();
            
            if (data.exists) {
                alert('Anda sudah memiliki pemesanan lain untuk tanggal ini. Silakan pilih tanggal lain.');
                tanggalInput.value = '<?= $pemesanan['tanggal'] ?>';
            }
        } catch (error) {
            console.error('Error checking existing booking:', error);
        }
    }
});

function updateStatusBasedOnFile(input) {
    const statusField = document.getElementById('status');
    if (input.files && input.files.length > 0) {
        statusField.value = 'sudah bayar belum konfirmasi';
        
        if (!document.getElementById('status-backup')) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'status';
            hiddenInput.id = 'status-backup';
            hiddenInput.value = 'sudah bayar belum konfirmasi';
            input.parentNode.appendChild(hiddenInput);
        }
    } else {
        const hiddenInput = document.getElementById('status-backup');
        if (hiddenInput) {
            hiddenInput.remove();
        }
    }
}
</script>

<?= $this->endSection() ?>