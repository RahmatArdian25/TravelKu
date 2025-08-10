<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .kop {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 80px;
            height: 80px;
            border: 1px solid black;
            border-radius: 50%;
            line-height: 80px;
            text-align: center;
            font-weight: bold;
        }

        .kop .info {
            margin-left: 100px;
        }

        .kop .info h2 {
            color: red;
            margin: 0;
            font-size: 18px;
        }

        .kop .info p {
            margin: 3px 0;
        }

        hr {
            border: 1px solid black;
            margin: 0;
        }

        .title {
            text-align: center;
            margin: 30px 0 20px;
            font-weight: bold;
            font-size: 18px;
        }

        .date-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .footer {
            width: 90%;
            margin: 50px auto 0;
            text-align: right;
        }

        .footer p {
            margin: 5px 0;
        }

        .no-data {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="kop">
    <div class="info">
        <h2>CV Ngalau Minang Maimbau</h2>
        <p>Padang : Jln Andalas No 85 Telp (075) 37785</p>
        <p>Batusangkar : Jln H. Agus Salim No 08 / 08383446954</p>
        <p>Provinsi Sumatra Barat</p>
    </div>
</div>

<hr>

<div class="title">
    LAPORAN JADWAL KEBERANGKATAN
</div>

<div class="date-info">
    Tanggal: <?= date('d F Y', strtotime($tanggal)) ?>
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA SUPIR</th>
            <th>KENDARAAN</th>
            <th>ASAL</th>
            <th>TUJUAN</th>
            <th>HARGA</th>
            <th>JAM BERANGKAT</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalHarga = 0;
        foreach ($jadwal as $i => $row): 
            $totalHarga += $row['harga'];
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($row['nama_user']) ?></td>
            <td><?= esc($row['namakendaraan']) ?></td>
            <td><?= esc($row['asal']) ?></td>
            <td><?= esc($row['tujuan']) ?></td>
            <td class="text-end">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td><?= date('H:i', strtotime($row['jam'])) ?></td>
        </tr>
        <?php endforeach ?>
        
        <?php if (!empty($jadwal)): ?>
        <tr class="total-row">
            <td colspan="5" class="text-end">TOTAL KESELURUHAN</td>
            <td class="text-end">Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
            <td></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if (empty($jadwal)): ?>
    <p class="no-data">
        Tidak ada data jadwal berangkat untuk tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>.
    </p>
<?php endif; ?>

<div class="footer">
    <p>Padang, <?= date('d-m-Y') ?></p>
    <br><br><br>
    <p>Hormat Kami,</p>
    <p>Pimpinan</p>
</div>

</body>
</html>