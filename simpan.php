<?php
session_start();

// DIPERBAIKI: Menggunakan !isset dan nama fungsi yang benar
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Mengambil data dari form
$nama = $_POST['nama'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$status = $_POST['status'] ?? '';
// DIPERBAIKI: Mengubah $_['keterangan'] menjadi $_POST['keterangan']
$keterangan = $_POST['keterangan'] ?? '';
$no_hp = $_POST['no_hp_ortu'] ??'';

// Membersihkan data
$nama = trim($nama);
$kelas = trim($kelas);
$keterangan = trim($keterangan);

// Jika keterangan kosong
if ($keterangan == "") {
    $keterangan = "-";
}

// Membuat format data
$data = 
    $tanggal . "|" .
    $nama . "|" .
    $kelas . "|" .
    $status . "|" .
    $keterangan . PHP_EOL; // Perhatikan: PHP_EOL sudah otomatis membuat baris baru, jadi tidak perlu tanda "|" di ujung sebelum PHP_EOL agar tidak membuat kolom kosong ekstra.

// Pastikan folder 'data' ada
if (!is_dir('data')) {
    mkdir('data', 0777, true);
}

// Menyimpan data
file_put_contents(
    "data/absensi.txt",
    $data,
    FILE_APPEND
);
// 48. KODINGAN NOTIFIKASI ORTU - TARO DISINI
if ($status == 'Alpa' || $status == 'Sakit' || $status == 'Izin') {
    $hp_wa = preg_replace('/^0/', '62', $no_hp);
    $pesan = "INFO: Ananda $nama kelas $kelas tgl $tanggal status $status - $keterangan";
    $_SESSION['link_wa'] = "https://wa.me/$hp_wa?text=" . urlencode($pesan);
    $_SESSION['nama_wa'] = $nama;
}

// Kembali ke data absensi (DIPERBAIKI: success menggunakan double 's')
header("Location: data_absensi.php?success=1");
exit;
?>