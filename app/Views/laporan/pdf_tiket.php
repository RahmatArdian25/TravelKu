<style>
    .ticket {
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 20px;
        max-width: 300px;
        margin: 0 auto;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
    }
    .ticket-header {
        background-color: #28a745;
        color: white;
        padding: 10px;
        text-align: center;
        border-radius: 5px 5px 0 0;
        margin: -20px -20px 20px -20px;
    }
    .ticket-body {
        padding: 10px 0;
    }
    .ticket-info {
        margin-bottom: 10px;
    }
    .ticket-info strong {
        display: inline-block;
        width: 120px;
    }
    .ticket-divider {
        border-top: 1px dashed #28a745;
        margin: 15px 0;
    }
    .ticket-footer {
        text-align: center;
        font-style: italic;
        color: #6c757d;
    }
    .penumpang-list {
        margin-top: 15px;
    }
    .penumpang-item {
        background-color: #e9ecef;
        padding: 8px;
        border-radius: 5px;
        margin-bottom: 5px;
    }
    .info-box {
        background-color: #e9ecef;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .info-box-title {
        font-weight: bold;
        margin-bottom: 5px;
    }
</style>

<div class="ticket">
    <div class="ticket-header">
        <h3>Tiket Perjalanan</h3>
        <h5><?= $rute['asal'] ?> - <?= $rute['tujuan'] ?></h5>
    </div>
    
    <div class="ticket-body">
        <div class="ticket-info">
            <strong>No. Tiket:</strong> <?= str_pad($pemesanan['idpemesanan'], 6, '0', STR_PAD_LEFT) ?>
        </div>
        <div class="ticket-info">
            <strong>Nama Pemesan:</strong> <?= $user['nama_user'] ?>
        </div>
        <div class="ticket-info">
            <strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($pemesanan['tanggal'])) ?>
        </div>
        <div class="ticket-info">
            <strong>Jam:</strong> <?= $jadwal ? date('H:i', strtotime($jadwal['jam'])) : '-' ?>
        </div>
        <div class="ticket-info">
            <strong>Jumlah Penumpang:</strong> <?= $pemesanan['jumlah_orang'] ?>
        </div>
        <div class="ticket-info">
            <strong>Total:</strong> Rp <?= number_format($pemesanan['total'], 0, ',', '.') ?>
        </div>
        <div class="ticket-info">
            <strong>Status:</strong> 
            <?php
            $badgeClass = '';
            switch ($pemesanan['status']) {
                case 'sudah berangkat':
                    $badgeClass = 'bg-success';
                    break;
                case 'pembayaran sudah di konfirmasi':
                    $badgeClass = 'bg-primary';
                    break;
                case 'sudah bayar belum konfirmasi':
                    $badgeClass = 'bg-info';
                    break;
                case 'belum bayar':
                    $badgeClass = 'bg-warning';
                    break;
                default:
                    $badgeClass = 'bg-secondary';
            }
            ?>
            <span class="badge <?= $badgeClass ?>">
                <?= ucfirst($pemesanan['status']) ?>
            </span>
        </div>
        
        <div class="ticket-divider"></div>
        
        <!-- Kendaraan Information -->
        <div class="info-box">
            <div class="info-box-title">Informasi Kendaraan</div>
            <?php if ($kendaraan): ?>
                <div><strong>Nama:</strong> <?= $kendaraan['namakendaraan'] ?></div>
                <div><strong>Plat:</strong> <?= $kendaraan['nopolisi_kendaraan'] ?></div>
            <?php else: ?>
                <div>Informasi kendaraan tidak tersedia</div>
            <?php endif; ?>
        </div>
        
        <!-- Supir Information -->
        <div class="info-box">
            <div class="info-box-title">Informasi Supir</div>
            <?php if ($supir): ?>
                <div><strong>Nama:</strong> <?= $supir['nama_user'] ?></div>
                <div><strong>No. HP:</strong> <?= $supir['nohp'] ?></div>
            <?php else: ?>
                <div>Informasi supir tidak tersedia</div>
            <?php endif; ?>
        </div>
        
        <div class="ticket-divider"></div>
        
        <h5>Daftar Penumpang</h5>
      
<div class="penumpang-list">
    <?php foreach ($detail_pemesanan as $item): ?>
        <div class="penumpang-item">
            <strong><?= $item['detail']['namapenumpang'] ?></strong><br>
            <small>
                <?= $item['detail']['jeniskelamin'] ?> 
                <?php if ($item['kursi']): ?>
                    | Kursi: <?= $item['kursi']['nomorkursi'] ?>
                <?php endif; ?>
            </small>
        </div>
    <?php endforeach; ?>
</div>
    </div>
    
    <div class="ticket-divider"></div>
    
    <div class="ticket-footer">
        <p>Terima kasih telah menggunakan layanan kami</p>
        <p>Selamat Jalan!</p>
    </div>
</div>