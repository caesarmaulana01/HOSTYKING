<?php
$page = "Jadwal Dokter";
session_start();
include 'auth/connect.php';
include "part/head.php";

if (!isset($_GET['id'])) {
    header("Location: data_dokter.php");
    exit;
}

$id_dokter = $_GET['id'];

// Ambil data dokter
$query_dokter = "SELECT * FROM data_dokter WHERE id = '$id_dokter'";
$result_dokter = mysqli_query($conn, $query_dokter);
$dokter = mysqli_fetch_array($result_dokter);

// Ambil jadwal dokter
$query_jadwal = "SELECT jadwal.hari, jadwal.jam_mulai, jadwal.jam_selesai, jadwal_dokter.status 
                 FROM jadwal_dokter
                 LEFT JOIN jadwal ON jadwal_dokter.id_jadwal = jadwal.id
                 WHERE jadwal_dokter.id_dokter = '$id_dokter'
                 ORDER BY FIELD(jadwal.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')";
$result_jadwal = mysqli_query($conn, $query_jadwal);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $page; ?></title>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <?php include 'part/navbar_pasien.php'; ?>
            <?php include 'part/sidebar_pasien.php'; ?>

            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Jadwal Dokter - <?php echo ucwords($dokter['nama_dokter']); ?></h1>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Jadwal Praktek</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="table-1">
                                                <thead>
                                                    <tr>
                                                        <th>Hari</th>
                                                        <th>Jam Praktek</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($row = mysqli_fetch_array($result_jadwal)) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row['hari']; ?></td>
                                                            <td><?php echo $row['jam_mulai'] . ' - ' . $row['jam_selesai']; ?></td>
                                                            <td>
                                                                <?php
                                                                if ($row['status'] == 0) {
                                                                    echo '<div class="badge badge-pill badge-success"><i class="ion-checkmark-round"></i> Tersedia</div>';
                                                                } else {
                                                                    echo '<div class="badge badge-pill badge-danger"><i class="ion-close"></i> Tidak Tersedia</div>';
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <a href="data_dokter_pasien.php" class="btn btn-secondary">Kembali</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include 'part/footer.php'; ?>
        </div>
    </div>
    <?php include "part/all-js.php"; ?>
</body>

</html>
