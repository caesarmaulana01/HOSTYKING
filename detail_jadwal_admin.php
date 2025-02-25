<!DOCTYPE html>
<html lang="en">

<head>    
    <?php
    
    session_start();
    
    include 'auth/connect.php';
    $id_dokter = $_GET['id'];

    // Ambil data dokter
    $dokter_query = mysqli_query($conn, "SELECT * FROM data_dokter WHERE id='$id_dokter'");
    $dokter = mysqli_fetch_assoc($dokter_query);

    if (!$dokter) {
        echo "Data dokter tidak ditemukan!";
        exit;
    }
    $page1 = "detjad";
    $page = "Detail Jadwal: " . ucwords($dokter['nama_dokter']);
    include "part/head.php";
    
    if (!isset($_GET['id'])) {
        echo "ID Dokter tidak ditemukan!";
        exit;
    }


    
    ?>

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            <?php
            include 'part/navbar_admin.php';
            include 'part/sidebar_admin.php';
            ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?php echo $page; ?></h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="data_dokter_admin.php">Data Dokter</a></div>
                            <div class="breadcrumb-item">Detail Jadwal: <?php echo ucwords($dokter['nama_dokter']); ?></div>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?php echo $page; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="table-1">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Hari</th>
                                                        <th>Jam Mulai</th>
                                                        <th>Jam Selesai</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $jadwal_query = mysqli_query($conn, "SELECT jadwal.*, jadwal_dokter.status 
                                                                                         FROM jadwal_dokter 
                                                                                         INNER JOIN jadwal ON jadwal_dokter.id_jadwal = jadwal.id
                                                                                         WHERE jadwal_dokter.id_dokter='$id_dokter' 
                                                                                         ORDER BY FIELD(jadwal.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')");

                                                    $no = 0;
                                                    while ($jadwal = mysqli_fetch_assoc($jadwal_query)) {
                                                        $no++;
                                                        $status = ($jadwal['status'] == 0) ? 
                                                            '<span class="badge badge-success">Tersedia</span>' : 
                                                            '<span class="badge badge-danger">Tidak Tersedia</span>';
                                                        echo "<tr>
                                                                <td>{$no}</td>
                                                                <td>{$jadwal['hari']}</td>
                                                                <td>{$jadwal['jam_mulai']}</td>
                                                                <td>{$jadwal['jam_selesai']}</td>
                                                                <td>{$status}</td>
                                                            </tr>";
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
