
<?php
session_start();

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// Login Manual
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "12345") {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Absensi Sekolah</title>

    <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">

<div class="login-box">

    <img src="logo.jpg" class="logo" alt="Logo Sekolah">

    <h2>ABSENSI SEKOLAH</h2>

    <p>Silahkan login untuk masuk</p>

    <?php if ($error !== "") { ?>
        <div class="error">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php } ?>

    <!-- FORM LOGIN -->

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            placeholder="Masukkan username"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Masukkan password"
            required
        >

        <button type="submit" name="login">
            LOGIN
        </button>

    </form>

    <div class="info-login" style="margin-top: 15px;">
        Username: <b>admin</b><br>
        Password: <b>12345</b>
    </div>

</div>

</body>
</html>
