<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Dashboard";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  include 'part_func/tgl_ind.php';

  // Mengambil data jumlah konsultasi pasien
  $id_pasien = $_SESSION['id_pasien'];
  $konsultasi = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id_pasien = '$id_pasien'");
  $jumkonsul = mysqli_num_rows($konsultasi);
  
  // Mengambil data jumlah rawat inap pasien
  $rawat_inap = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE id_pasien = '$id_pasien'");
  $jumrawatinap = mysqli_num_rows($rawat_inap);
  
  // Mengambil jumlah foto medis (Rontgen/USG)
  $foto_medis = mysqli_query($conn, "SELECT * FROM foto_medis WHERE id_pasien = '$id_pasien'");
  $jumlah_foto = mysqli_num_rows($foto_medis);

  $query_harga = mysqli_query($conn, "SELECT SUM(harga) AS total_harga FROM riwayat_tindakan WHERE id_pasien = '$id_pasien'");
  $data_harga = mysqli_fetch_assoc($query_harga);
  $biaya_pengobatan = $data_harga['total_harga'] ?? 0;
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
      include 'part/navbar_pasien.php';
      include 'part/sidebar_pasien.php';
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>
          <div class="row">
            <!-- Total Konsultasi -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <a href="detail_riwayat_pasien.php" class="card-link">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Konsultasi</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $jumkonsul; ?>
                        </div>
                    </div>
                </div>
              </a>
            </div>

            <!-- Total Rawat Inap -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <a href="detail_riwayat_pasien.php" class="card-link">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Rawat Inap</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $jumrawatinap; ?>
                        </div>
                    </div>
                </div>
              </a>
            </div>

            <!-- Total Biaya Pengobatan -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <a href="detail_riwayat_pasien.php" class="card-link">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Biaya Pengobatan</h4>
                        </div>
                        <div class="card-body">
                            Rp <?php echo number_format($biaya_pengobatan, 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
              </a>
            </div>

            <!-- Jumlah Foto Medis -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <a href="foto_medis_pasien.php" class="card-link">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-x-ray"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Foto Medis</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $jumlah_foto; ?>
                        </div>
                    </div>
                </div>
              </a>
            </div>
          </div>
          <style>
            .card-link {
                text-decoration: none;
                color: inherit;
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
                  <h4>Ketersediaan Dokter</h4>
                  <div class="card-header-action">
                    <a href="data_dokter_pasien.php">Detail</a>
                  </div>
                </div>
                <div class="card-body">
                  <?php
                  // Ambil daftar dokter
                  $sqdokter = mysqli_query($conn, "SELECT d.id, d.nama_dokter, d.spesialisasi
                                                  FROM data_dokter d 
                                                  ORDER BY d.nama_dokter ASC");

                  while ($showdokter = mysqli_fetch_array($sqdokter)) {
                    $id_dokter = $showdokter['id'];

                    // Cek jadwal dokter dengan join ke tabel jadwal
                    $sqjadwal = mysqli_query($conn, "SELECT j.id_pasien, j.status, jd.hari, jd.jam_mulai, jd.jam_selesai
                                                    FROM jadwal_dokter j
                                                    JOIN jadwal jd ON j.id_jadwal = jd.id
                                                    WHERE j.id_dokter = '$id_dokter'");

                    $status_dokter = "Tidak Ada Jadwal";  // Default jika tidak ada jadwal
                    $jadwal_tersedia = [];

                    while ($jadwal = mysqli_fetch_array($sqjadwal)) {
                      if ($jadwal['status'] == 0) {
                        $jadwal_tersedia[] = $jadwal['hari'] . " (" . $jadwal['jam_mulai'] . " - " . $jadwal['jam_selesai'] . ")";
                      }
                    }

                    if (!empty($jadwal_tersedia)) {
                      if (count($jadwal_tersedia) > 1) {
                        $status_dokter = "Tersedia - " . $jadwal_tersedia[0] . ", +" . (count($jadwal_tersedia) - 1);
                      } else {
                        $status_dokter = "Tersedia - " . $jadwal_tersedia[0];
                      }
                    } else {
                      $status_dokter = "Tidak Tersedia";
                    }
                  ?>
                    <ul class="list-unstyled list-unstyled-border">
                      <li class="media">
                        <div class="media-body">
                          <!-- Menentukan Status Dokter -->
                          <?php
                          if ($status_dokter == "Tidak Tersedia") {
                            echo '<div class="badge badge-pill badge-danger mb-1 float-right">';
                            echo '<i class="ion-close"></i> Tidak Tersedia';
                          } elseif (strpos($status_dokter, "Tersedia") !== false) {
                            echo '<div class="badge badge-pill badge-success mb-1 float-right">';
                            echo '<i class="ion-checkmark-round"></i> ' . $status_dokter;
                          } else {
                            echo '<div class="badge badge-pill badge-warning mb-1 float-right">';
                            echo '<i class="ion-gear-b"></i> ' . $status_dokter;
                          }
                          ?>
                          </div>

                          <!-- Menampilkan Nama Dokter & Spesialisasi -->
                          <h6 class="media-title"><a href="#"><?php echo $showdokter["nama_dokter"]; ?></a></h6>
                          <div class="text-small text-muted"><?php echo $showdokter["spesialisasi"]; ?></div>
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
                    <!-- Booking Fasilitas -->
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="card card-large-icons">
                        <div class="card-icon bg-primary text-white">
                          <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="card-body">
                          <h4>Booking Fasilitas</h4>
                          <form action="data_booking_pasien.php" method="POST" style="display: inline;">
                            <input type="hidden" name="pilih_dokter" value="true">
                            <button type="submit" class="card-cta btn btn-link p-0" style="text-decoration: none;">Detail <i class="fas fa-chevron-right"></i></button>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Riwayat Pemeriksaan -->
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="card card-large-icons">
                        <div class="card-icon bg-danger text-white">
                          <i class="fas fa-file-medical"></i>
                        </div>
                        <div class="card-body">
                          <h4>Riwayat Pemeriksaan</h4>
                          <a href="detail_riwayat_pasien.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                        </div>
                      </div>
                    </div>
                    <!-- Data Dokter -->
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="card card-large-icons">
                        <div class="card-icon bg-warning text-white">
                          <i class="fas fa-user-md"></i>
                        </div>
                        <div class="card-body">
                          <h4>Data Dokter</h4>
                          <a href="data_dokter_pasien.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                        </div>
                      </div>
                    </div>
                    <!-- Foto Medis -->
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="card card-large-icons">
                        <div class="card-icon bg-success text-white">
                          <i class="fas fa-x-ray"></i>
                        </div>
                        <div class="card-body">
                          <h4>Foto Medis</h4>
                          <a href="foto_medis_pasien.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                        </div>
                      </div>
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
