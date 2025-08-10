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

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        tfoot td {
            font-weight: bold;
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
    LAPORAN TAHUNAN PEMESANAN TIKET
</div>

<div class="subtitle">
    Tahun: <?= esc($tahun) ?>
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>BULAN</th>
            <th>TOTAL PEMESANAN</th>
            <th>TOTAL PENDAPATAN</th>
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
                <td><?= $monthNum ?></td>
                <td><?= $monthName ?></td>
                <td><?= $totalPemesanan ?></td>
                <td class="text-end">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="2" class="text-center">TOTAL</td>
            <td><?= $grandTotalPemesanan ?></td>
            <td class="text-end">Rp <?= number_format($grandTotalPendapatan, 0, ',', '.') ?></td>
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