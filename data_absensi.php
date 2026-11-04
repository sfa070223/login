<?php
session_start();

// DIPERBAIKI: Menggunakan !isset dan nama fungsi yang benar
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$file = "data/absensi.txt";
$data = [];

// Membaca file
if (file_exists($file)) {
    $data = file(
        $file,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- DIPERBAIKI: Menggunakan UTF-8 standar dan tag link yang benar -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h2>Data Absensi</h2>
            <p>Daftar kehadiran siswa</p>
        </div>
        <div>
            <span class="admin">
                <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
            </span>
            <a href="logout.php" class="logout">logout</a>
        </div>
    </div>

    <!-- MENU -->
    <div class="menu">
        <a href="dashboard.php">Input Absensi</a>
        <a href="simpan.php">Data Absensi</a>
    </div>

    <!-- DIPERBAIKI: success menggunakan double 's' -->
    <?php if (isset($_GET['success'])) { ?>
        <div class="success">
            Data absensi berhasil disimpan!
        </div>
    <?php } ?>

    <!-- TABEL -->
    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <!-- DIPERBAIKI: Mengubah thbody menjadi tbody -->
                <tbody>
                <?php
                if (count($data) > 0) {
                    $no = 1;
                    foreach ($data as $baris) {
                        $sisi = explode("|", $baris);

                        if (count($sisi) >= 5) {
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <!-- DIPERBAIKI: Kurung tutup htmlspecialchars -->
                            <td><?= htmlspecialchars($sisi[0]); ?></td>
                            <td><?= htmlspecialchars($sisi[1]); ?></td>
                            <!-- DIPERBAIKI: Kurung tutup htmlspecialchars -->
                            <td><?= htmlspecialchars($sisi[2]); ?></td>
                            <td>
                                <?php
                                if ($sisi[3] == "hadir") {
                                    echo '<span class="hadir">Hadir</span>';
                                } elseif ($sisi[3] == "izin") {
                                    echo '<span class="izin">Izin</span>';
                                } elseif ($sisi[3] == "sakit") {
                                    // DIPERBAIKI: Penulisan </span> yang benar
                                    echo '<span class="sakit">Sakit</span>';
                                } else {
                                    // DIPERBAIKI: Mengubah elseif kosong menjadi else
                                    echo '<span class="alpa">Alpa</span>';
                                }
                                ?>
                            </td>
                            <!-- DIPERBAIKI: Perbaikan kurung pada $sisi[4] -->
                            <td><?= htmlspecialchars($sisi[4]); ?></td>
                        </tr>
                <?php
                        }
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="6">Belum ada data absensi.</td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</div><!-- Penutup container -->

</body>
</html>