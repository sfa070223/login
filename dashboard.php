
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Absensi</title>

<link rel="stylesheet" href="style.css">

<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<style>
.face-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    text-align: center;
}

video {
    width: 100%;
    max-width: 400px;
    border-radius: 12px;
    background: #222;
    transform: scaleX(-1);
}

#statusWajah {
    margin: 15px;
    font-weight: bold;
    color: #d97706;
}

button:disabled {
    background: gray;
    cursor: not-allowed;
}
</style>

</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h2>Dashboard Absensi</h2>
            <p>Sistem Absensi Sekolah</p>
        </div>

        <div>
            <span class="admin">
                <?= htmlspecialchars($_SESSION['username']); ?>
            </span>

            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="menu">
        <a href="dashboard.php">Input Absensi</a>
        <a href="simpan.php">Data Absensi</a>
    </div>

    <!-- FACE RECOGNITION -->

    <div class="card face-box">

        <h3>Verifikasi Wajah Siswa</h3>

        <video id="video" autoplay muted playsinline></video>

        <p id="statusWajah">
            Memuat sistem pengenalan wajah...
        </p>

        <button type="button" onclick="mulaiKamera()">
            AKTIFKAN KAMERA
        </button>

        <button type="button" onclick="verifikasiWajah()">
            VERIFIKASI WAJAH
        </button>

    </div>

    <!-- FORM ABSENSI -->

    <div class="card">

        <h3>Form Absensi Siswa</h3>

        <form action="simpan.php" method="POST"
              onsubmit="return cekVerifikasi()">

            <label>Nama Siswa</label>

            <input type="text" name="nama"
            placeholder="Masukkan nama siswa" required>

            <label>Kelas</label>

            <select name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="X TKJ 1">X TKJ 1</option>
                <option value="X TKJ 2">X TKJ 2</option>
                <option value="X RPL 1">X RPL 1</option>
                <option value="X RPL 2">X RPL 2</option>
                <option value="XI TKJ">XI TKJ</option>
                <option value="XI RPL">XI RPL</option>
                <option value="XII TKJ">XII TKJ</option>
                <option value="XII RPL">XII RPL</option>
            </select>

            <label>No. HP Orang Tua</label>

            <input type="tel" name="no_hp_ortu"
            placeholder="083160130534" required>

            <label>Tanggal</label>

            <input type="date" name="tanggal"
            value="<?= date('Y-m-d'); ?>" required>

            <label>Status Kehadiran</label>

            <select name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alpa">Alpa</option>
            </select>

            <label>Keterangan</label>

            <textarea name="keterangan"
            placeholder="Masukkan keterangan jika ada"
            rows="4"></textarea>

            <button type="submit" id="tombolSimpan" disabled>
                SIMPAN ABSENSI
            </button>

        </form>

    </div>

</div>

<script>

let wajahTerverifikasi = false;
let kameraAktif = false;

const video = document.getElementById("video");
const statusWajah = document.getElementById("statusWajah");
const tombolSimpan = document.getElementById("tombolSimpan");

// LOAD MODEL

async function loadModel() {

    try {

        await faceapi.nets.tinyFaceDetector.loadFromUri('./models');

        statusWajah.innerText =
        "Model berhasil dimuat. Silakan aktifkan kamera.";

    } catch (error) {

        statusWajah.innerText =
        "Gagal memuat model wajah.";

        console.error(error);
    }
}

// AKTIFKAN KAMERA

async function mulaiKamera() {

    try {

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "user"
            },
            audio: false
        });

        video.srcObject = stream;

        kameraAktif = true;

        statusWajah.innerText =
        "Kamera aktif. Silakan posisikan wajah.";

    } catch (error) {

        alert("Tidak bisa mengakses kamera. Periksa izin kamera.");

    }
}

// VERIFIKASI DETEKSI WAJAH

async function verifikasiWajah() {

    if (!kameraAktif) {

        alert("Aktifkan kamera terlebih dahulu!");
        return;

    }

    statusWajah.innerText = "Sedang mendeteksi wajah...";

    const hasil = await faceapi.detectSingleFace(
        video,
        new faceapi.TinyFaceDetectorOptions()
    );

    if (hasil) {

        wajahTerverifikasi = true;

        tombolSimpan.disabled = false;

        statusWajah.innerText =
        "Wajah terdeteksi! Silakan lanjutkan absensi.";

        statusWajah.style.color = "green";

    } else {

        wajahTerverifikasi = false;

        tombolSimpan.disabled = true;

        statusWajah.innerText =
        "Wajah tidak terdeteksi. Coba lagi.";

    }

}

// CEK SEBELUM SIMPAN

function cekVerifikasi() {

    if (!wajahTerverifikasi) {

        alert("Silakan verifikasi wajah terlebih dahulu!");

        return false;

    }

    return true;

}

loadModel();

</script>

</body>
</html>
