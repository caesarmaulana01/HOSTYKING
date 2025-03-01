<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Dashboard Admin";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  include 'part_func/tgl_ind.php';

  $admin = mysqli_query($conn, "SELECT * FROM data_admin");
  $jumlahadmin = mysqli_num_rows($admin);
  $pasien = mysqli_query($conn, "SELECT * FROM pasien");
  $jumpasien = mysqli_num_rows($pasien);
  $rawat_inap = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE id_pasien IS NOT NULL");
  $jumrawatinap = mysqli_num_rows($rawat_inap);
  $dokter = mysqli_query($conn, "SELECT * FROM data_dokter");
  $jumlahdokter = mysqli_num_rows($dokter);
  $booking = mysqli_query($conn, "SELECT * FROM booking");
  $jumlahbooking = mysqli_num_rows($booking);
  ?>
  <style>
    #link-no {
      text-decoration: none;
    }
  </style>
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
            <h1>Dashboard Admin</h1>
          </div>
          <div class="row">
            <!-- Jumlah Pegawai -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="data_admin_admin.php" class="card-link">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Pegawai</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $jumlahadmin; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Jumlah Pasien -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="pasien_admin.php" class="card-link">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-user-injured"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Pasien</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $jumpasien; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Jumlah Pasien Rawat Inap -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="riwayat_inap_admin.php" class="card-link">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Pasien Rawat Inap</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $jumrawatinap; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Jumlah Dokter -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="data_dokter_admin.php" class="card-link">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-diagnoses"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Dokter</h4>
                            </div>
                            <div class="card-body">
                                <?php echo $jumlahdokter; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <style>
            /* Tambahkan efek hover agar card terlihat seperti tombol */
            .card-link {
                text-decoration: none; /* Hilangkan garis bawah */
                color: inherit; /* Warna tetap sesuai */
            }

            .card-link:hover .card {
                transform: scale(1.05);
                transition: 0.3s ease-in-out;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            }
        </style>
          <div class="row">
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Status Ruang Rawat Inap</h4>
                  <div class="card-header-action">
                    <a href="ruang_inap_admin.php">Detail</a>
                  </div>
                </div>
                <div class="card-body">
                  <?php
                  $sqlruangan = mysqli_query($conn, "SELECT * FROM ruang_inap ORDER BY nama_ruang ASC");
                  while ($showruangan = mysqli_fetch_array($sqlruangan)) {
                    $defpasien = $showruangan['id_pasien'];
                  ?>
                    <ul class="list-unstyled list-unstyled-border">
                      <li class="media">
                        <div class="media-body">
                          <?php
                          if ($showruangan["status"] == "0") {
                            echo '<div class="badge badge-pill badge-success mb-1 float-right">';
                            echo '<i class="ion-checkmark-round"></i> Tersedia';
                          } elseif ($showruangan["status"] == "1") {
                            echo '<div class="badge badge-pill badge-danger mb-1 float-right">';
                            echo '<i class="ion-close"></i> Dipakai';
                          } else {
                            echo '<div class="badge badge-pill badge-warning mb-1 float-right">';
                            echo '<i class="ion-gear-b"></i>  Dalam Perbaikan';
                          } ?>
                        </div>
                        <h6 class="media-title"><a href="#">Ruang <?php echo $showruangan["nama_ruang"]; ?></a></h6>
                        <div class="text-small text-muted">
                          <?php
                          if ($showruangan["status"] == "0") {
                            echo 'Tersedia';
                          } elseif ($showruangan["status"] == "1") {
                            $sqlnama = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$defpasien'");
                            $namapasien = mysqli_fetch_array($sqlnama);
                            echo 'Sdr. ' . ucwords($namapasien["nama_pasien"]);
                            echo '<div class="bullet"></div> <span class="text-primary">Sejak ' . tgl_indo($showruangan["tgl_masuk"]) . '</span></div>';
                          } else {
                            echo '<div class="text-small text-muted">Tidak Tersedia</div>';
                          } ?>
                        </div>
                      </li>
                    </ul>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Menu Utama</h4>
                </div>
                <div class="card-body">
                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-primary text-white">
                        <i class="fas fa-bed"></i>
                      </div>
                      <div class="card-body">
                        <h4>Rawat Inap</h4>
                        <a href="ruang_inap_admin.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-danger text-white">
                        <i class="fas fa-skull"></i>
                      </div>
                      <div class="card-body">
                        <h4>Foto Rontgen</h4>
                        <a href="rontgen_admin.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-warning text-white">
                        <i class="fas fa-heartbeat"></i>
                      </div>
                      <div class="card-body">
                        <h4>Foto USG</h4>
                        <a href="usg_admin.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-success text-white">
                        <i class="fas fa-briefcase-medical"></i>
                      </div>
                      <div class="card-body">
                        <h4>Data Obat</h4>
                        <a href="obat_admin.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
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