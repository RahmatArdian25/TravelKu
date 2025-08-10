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
            margin: 30px 0 10px;
            font-weight: bold;
            font-size: 18px;
        }

        .subtitle {
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

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: black;
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
    LAPORAN PEMESANAN TIKET
</div>

<div class="subtitle">
    Bulan <?= esc($monthName) ?> <?= esc($tahun) ?>
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>RUTE</th>
            <th>NAMA PEMESAN</th>
            <th>TANGGAL</th>
            <th>JUMLAH ORANG</th>
            <th>STATUS</th>
            <th>TOTAL</th>
        </tr>
    </thead>
   <tbody>
        <?php 
        $grandTotal = 0;
        foreach ($pemesanan as $i => $row): 
            $grandTotal += $row['total'];
        ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($row['asal']) ?> - <?= esc($row['tujuan']) ?></td>
                <td><?= esc($row['nama_user']) ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <td><?= esc($row['jumlah_orang']) ?></td>
                <td>
                    <span class="badge <?= $row['status'] == 'sudah berangkat' ? 'bg-success' : 'bg-warning' ?>">
                        <?= esc(ucfirst($row['status'])) ?>
                    </span>
                </td>
                <td class="text-end">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-end">TOTAL KESELURUHAN</th>
            <th class="text-end">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>

<div class="footer">
    <p>Padang, <?= date('d-m-Y') ?></p>
    <br><br><br>
    <p>Hormat Kami,</p>
    <p>Pimpinan</p>
</div>

</body>
</html>