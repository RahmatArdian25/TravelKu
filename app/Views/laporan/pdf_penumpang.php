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

        .footer {
            width: 90%;
            margin: 50px auto 0;
            text-align: right;
        }

        .footer p {
            margin: 5px 0;
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
    Laporan Data Penumpang
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>E-MAIL</th>
            <th>NOHP</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($penumpang as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($row['nama_user']) ?></td>
            <td><?= esc($row['email']) ?></td>
            <td><?= esc($row['nohp']) ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="footer">
    <p>Padang, <?= date('d-m-Y') ?></p>
    <br><br><br>
    <p>Hormat Kami,</p>
    <p>Pimpinan</p>
</div>

</body>
</html>

