<!DOCTYPE html>
<html>
<head>
    <title>Surat Jalan</title>
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

        .header {
            text-align: center;
            margin: 20px 0;
        }

        .content {
            margin: 30px 0;
            padding: 0 20px;
        }

        .footer {
            margin-top: 50px;
            padding: 0 20px;
        }

        .signature {
            margin-top: 80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        td {
            vertical-align: top;
        }

        .border-top {
            border-top: 1px solid #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        p {
            margin: 8px 0;
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

<div class="header">
    <h2>SURAT JALAN</h2>
    <p>Nomor: <?= $nomor_surat ?></p>
</div>

<div class="content">
    <table>
        <tr>
            <td width="50%">
                <p><strong>NAMA SUPIR:</strong> <?= $jadwal['nama_user'] ?></p>
                <p><strong>NOMOR HP SUPIR:</strong> <?= $jadwal['nohp'] ?></p>
            </td>
            <td width="50%" class="text-right">
                <p><strong>TANGGAL:</strong> <?= date('d F Y', strtotime($jadwal['tanggal'])) ?></p>
            </td>
        </tr>
    </table>
    
    <p><strong>KENDARAAN:</strong></p>
    <p><?= $jadwal['namakendaraan'] ?> (<?= $jadwal['nopolisi_kendaraan'] ?>)</p>
    
    <p><strong>RUTE PERJALANAN:</strong></p>
    <p><?= $jadwal['asal'] ?> - <?= $jadwal['tujuan'] ?></p>
    <p>JAM BERANGKAT: <?= $jadwal['jam'] ?></p>
    
    <?php if (!empty($penumpang)): ?>
    <p><strong>DAFTAR PENUMPANG:</strong></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penumpang</th>
                <th>Jenis Kelamin</th>
                <th>Nomor Kursi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach($penumpang as $p): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $p['namapenumpang'] ?></td>
                <td><?= $p['jeniskelamin'] ?></td>
                <td><?= $p['nomorkursi'] ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Total Penumpang: <?= count($penumpang) ?> orang</p>
    <?php else: ?>
    <p><strong>TIDAK ADA DAFTAR PENUMPANG</strong></p>
    <?php endif; ?>
</div>

<div class="footer">
    <table>
        <tr>
            <td width="50%" class="text-center">
                <p>MENGETAHUI,</p>
                <div class="signature"></div>
                <p>Manager Operasional</p>
            </td>
            <td width="50%" class="text-center">
                <p>SUPIR,</p>
                <div class="signature">(<?= $jadwal['nama_user'] ?>)</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>