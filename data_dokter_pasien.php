<?php
$page = "Data Dokter";
session_start();
include 'auth/connect.php';
include "part/head.php";
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
                        <h1><?php echo $page; ?></h1>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Daftar Dokter</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="table-1">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama Dokter</th>
                                                        <th>Spesialis</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT * FROM data_dokter ORDER BY id";
                                                    $result = mysqli_query($conn, $query);
                                                    $i = 0;

                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $i++;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo ucwords($row['nama_dokter']); ?></td>
                                                            <td><?php echo ucwords($row['spesialisasi']); ?></td>
                                                            <td>
                                                                <a href="jadwal_dokter.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Lihat Jadwal</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
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
