<?php
session_start();
session_destroy();
header("Location: login.php"); // <-- Tambahan titik koma (;) di sini
exit;
?>