<?php
session_start();

// Cek jika ada session yang sesuai dan unset session masing-masing
if (isset($_SESSION['id_dokter'])) {
    unset($_SESSION['id_dokter']);
} elseif (isset($_SESSION['id_perawat'])) {
    unset($_SESSION['id_perawat']);
} elseif (isset($_SESSION['id_pasien'])) {
    unset($_SESSION['id_pasien']);
}

// Hancurkan session dan redirect ke halaman login
session_destroy();
header('location:index.php');
?>