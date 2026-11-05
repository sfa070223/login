
<?php
session_start();

// 1. Cek apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 2. Mengambil data dari form
$nama       = $_POST['nama'] ?? '';
$kelas      = $_POST['kelas'] ?? '';
$tanggal    = $_POST['tanggal'] ?? '';
$status     = $_POST['status'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$no_hp      = $_POST['no_hp_ortu'] ?? '';

// 3. Membersihkan data
$nama       = trim($nama);
$kelas      = trim($kelas);
$tanggal    = trim($tanggal);
$status     = trim($status);
$keterangan = trim($keterangan);
$no_hp      = trim($no_hp);

// 4. Validasi data
if ($nama == '' || $kelas == '' || $tanggal == '' || $status == '') {
    die("Data belum lengkap! Silakan kembali dan lengkapi form.");
}

// Jika keterangan kosong
if ($keterangan == '') {
    $keterangan = '-';
}

// 5. Membuat format data
$data = $tanggal . "|" .
        $nama . "|" .
        $kelas . "|" .
        $status . "|" .
        $keterangan . PHP_EOL;

// 6. Membuat folder data jika belum ada
$folder = __DIR__ . '/data';

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

// 7. Menyimpan data absensi
$file = $folder . '/absensi.txt';

$hasil = file_put_contents(
    $file,
    $data,
    FILE_APPEND | LOCK_EX
);

// 8. Mengecek apakah data berhasil disimpan
if ($hasil === false) {
    die("Gagal menyimpan data absensi!");
}

// 9. Membuat notifikasi WhatsApp orang tua
if ($status == 'Alpa' || $status == 'Sakit' || $status == 'Izin') {

    if ($no_hp != '') {

        // Mengubah 08 menjadi 628
        $hp_wa = preg_replace('/^0/', '62', $no_hp);

        // Menghapus karakter selain angka
        $hp_wa = preg_replace('/[^0-9]/', '', $hp_wa);

        $pesan = "INFO: Ananda $nama kelas $kelas tanggal $tanggal status $status - $keterangan";

        $_SESSION['link_wa'] = "https://wa.me/" . $hp_wa . "?text=" . urlencode($pesan);

        $_SESSION['nama_wa'] = $nama;
    }
}

// 10. Kembali ke halaman data absensi
header("Location: data_absensi.php?success=1");
exit;
?>
