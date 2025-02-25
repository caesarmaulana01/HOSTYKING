
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page = "Pasien Ditangani";
session_start();
include 'auth/connect.php';
include "part/head.php";
include "part_func/tgl_ind.php";


$id_dokter = $_SESSION['id_dokter']; // Ambil id dokter yang sedang login

?>
    <title><?php echo $page; ?></title>
</head>
<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <?php include 'part/navbar_dokter.php'; ?>
            <?php include 'part/sidebar_dokter.php'; ?>

            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?php echo $page; ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Daftar Pasien yang Telah Ditangani</h4>
                                    </div>
                                    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped" id="table-2">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Nama Pasien</th>
                    <th>Penyakit</th>
                    <th>Hasil Pemeriksaan</th>
                    <th>Tanggal</th>
                    <th>Biaya Pengobatan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = mysqli_query($conn, "SELECT rp.*, p.nama_pasien 
                                            FROM riwayat_penyakit rp 
                                            JOIN pasien p ON rp.id_pasien = p.id 
                                            WHERE rp.id_dokter_riwayat = '$id_dokter'");
                
                $i = 0;
                if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_array($sql)) {
                        $i++;

                        // Ambil total biaya pengobatan dari riwayat_tindakan berdasarkan id_penyakit
                        $id_penyakit = $row['id'];
                        $query_harga = mysqli_query($conn, "SELECT SUM(harga) AS total_harga FROM riwayat_tindakan WHERE id_penyakit = '$id_penyakit'");
                        $data_harga = mysqli_fetch_assoc($query_harga);
                        $biaya_pengobatan = $data_harga['total_harga'] ?? 0;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo ucwords($row['nama_pasien']); ?></td>
                            <td><?php echo $row['penyakit']; ?></td>
                            <td><?php echo $row['hasil_pemeriksaan']; ?></td>
                            <td><?php echo tgl_indo($row['tgl']); ?></td>
                            <td>Rp <?php echo number_format($biaya_pengobatan, 0, ',', '.'); ?></td>
                        </tr>
                    <?php }
                } 
                ?>
            </tbody>
        </table>
    </div>
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
