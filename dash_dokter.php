<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Dashboard Dokter";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  include 'part_func/tgl_ind.php';


    // Ambil ID dokter yang sedang login
  $id_dokter = $_SESSION['id_dokter']; 

  // Jumlah Pasien Hari Ini
  $tgl_hari_ini = date('Y-m-d');
  $pasien_hari_ini = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking WHERE tanggal = '$tgl_hari_ini' AND dokter_pilih = '$id_dokter'");
  $data_pasien_hari_ini = mysqli_fetch_assoc($pasien_hari_ini)['total'];

  // Pasien yang Telah Ditangani
  $pasien_ditangani = mysqli_query($conn, "SELECT COUNT(*) as total FROM riwayat_penyakit WHERE tgl = '$tgl_hari_ini'");
  $data_pasien_ditangani = mysqli_fetch_assoc($pasien_ditangani)['total'];

  // Jumlah Jadwal Praktek Dokter
  $jadwal_praktek = mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal_dokter WHERE id_dokter = '$id_dokter'");
  $data_jadwal_praktek = mysqli_fetch_assoc($jadwal_praktek)['total'];

  // Jumlah Booking Konsultasi Dokter
  $jumlah_booking = mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal_dokter WHERE id_dokter = '$id_dokter' AND status = 1");
  $data_jumlah_booking = mysqli_fetch_assoc($jumlah_booking)['total'];

  
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
      include 'part/navbar_dokter.php';
      include 'part/sidebar_dokter.php';
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard Dokter</h1>
          </div>
          <div class="row">
            <!-- Jumlah Pasien Hari Ini -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="data_booking_dokter.php" class="card-link">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fas fa-user-check"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Pasien Hari Ini</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $data_pasien_hari_ini; ?>
                  </div>
                </div>
              </div>
              </a>
            </div>

            <!-- Pasien yang Telah Ditangani -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="pasien_ditangani.php" class="card-link">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-user-md"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Pasien Ditangani</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $data_pasien_ditangani; ?>
                  </div>
                </div>
              </div>
              </a>
            </div>

            <!-- Jumlah Jadwal Praktek -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="jadwal_praktek_dokter.php" class="card-link">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Jadwal Praktek</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $data_jadwal_praktek; ?>
                  </div>
                </div>
              </div>
              </a>
            </div>

            <!-- Jumlah Booking Konsultasi -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="data_booking_dokter.php" class="card-link">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Booking Konsultasi</h4>
                  </div>
                  <div class="card-body">
                    <?php echo $data_jumlah_booking; ?>
                  </div>
                </div>
              </div>
            </div>
            </a>
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
                  <h4>Jadwal Konsultasi & Status Booking</h4>
                  <div class="card-header-action">
                    <a href="data_booking_dokter.php">Detail</a>
                  </div>
                </div>
                <div class="card-body">

                  <?php
                  $id_dokter = $_SESSION['id_dokter'];

                  // Ambil semua jadwal dokter
                  $sqljadwal = mysqli_query($conn, "
                      SELECT jd.id, jd.status, jd.id_pasien, j.hari, j.jam_mulai, j.jam_selesai, b.nama_pasien, b.keluhan
                      FROM jadwal_dokter jd
                      JOIN jadwal j ON jd.id_jadwal = j.id
                      LEFT JOIN booking b ON jd.id_pasien = b.id_pasien
                      WHERE jd.id_dokter = '$id_dokter'
                      ORDER BY 
                          FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), 
                          j.jam_mulai
                  ");

                  $jadwal_by_hari = [];
                  while ($jadwal = mysqli_fetch_assoc($sqljadwal)) {
                      $jadwal_by_hari[$jadwal['hari']][] = $jadwal;
                  }

                  if (!empty($jadwal_by_hari)) {
                  ?>
                      <!-- Tombol Hari (Teks, bukan button) -->
                      <div class="d-flex flex-wrap mb-3">
                          <?php $first = true; foreach ($jadwal_by_hari as $hari => $jadwals) { ?>
                              <span class="hari-btn mr-3 mb-2 <?php echo $first ? 'active' : ''; ?>" 
                                    data-target="collapse-<?php echo strtolower($hari); ?>">
                                  <?php echo $hari; ?>
                              </span>
                          <?php $first = false; } ?>
                      </div>

                      <!-- Accordion Jadwal -->
                      <div class="accordion">
                          <?php $first = true; foreach ($jadwal_by_hari as $hari => $jadwals) { ?>
                              <div class="collapse <?php echo $first ? 'show' : ''; ?>" id="collapse-<?php echo strtolower($hari); ?>">

                                  <?php foreach ($jadwals as $jadwal) { ?>
                                      <div class="media mb-3">
                                          <div class="media-body">
                                              <?php if ($jadwal['status'] == 1) { ?>
                                                  <div class="badge badge-success mb-1 float-right">
                                                      <i class="ion-checkmark-round"></i> Terisi
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="badge badge-warning mb-1 float-right">
                                                      <i class="ion-alert"></i> Kosong
                                                  </div>
                                              <?php } ?>

                                              <h6 class="media-title">
                                                  <?php echo $jadwal['jam_mulai'] . ' - ' . $jadwal['jam_selesai']; ?>
                                              </h6>

                                              <div class="text-small text-muted">
                                                  <?php
                                                  if ($jadwal['status'] == 1) {
                                                      echo 'Pasien: <strong>' . ucwords($jadwal['nama_pasien']) . '</strong>';
                                                      echo '<div class="bullet"></div> Keluhan: ' . $jadwal['keluhan'];
                                                  } else {
                                                      echo 'Belum ada pasien yang booking di jadwal ini.';
                                                  }
                                                  ?>
                                              </div>
                                          </div>
                                      </div>
                                  <?php } ?>
                              </div>
                          <?php $first = false; } ?>
                      </div>
                  <?php
                  } else {
                      echo '<p class="text-muted">Belum ada jadwal yang tersedia.</p>';
                  }
                  ?>
              </div>

              <!-- CSS untuk mengubah tombol menjadi teks -->
              <style>
                  .hari-btn {
                      cursor: pointer; 
                      color: #333;
                      padding: 5px 10px;
                      border-radius: 5px;
                      transition: all 0.3s;
                  }
                  .hari-btn.active {
                      color: white;
                      background-color: #007bff;
                  }

              </style>


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
                        <i class="fas fa-calendar-check"></i>
                      </div>
                      <div class="card-body">
                        <h4>Pasien Booking</h4>
                        <a href="data_booking_dokter.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-success text-white">
                        <i class="fas fa-user-md"></i>
                      </div>
                      <div class="card-body">
                        <h4>Pasien Ditangani</h4>
                        <a href="pasien_ditangani.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-warning text-white">
                        <i class="fas fa-clock"></i>
                      </div>
                      <div class="card-body">
                        <h4>Jadwal Praktek</h4>
                        <a href="jadwal_praktek_dokter.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="card card-large-icons">
                      <div class="card-icon bg-danger text-white">
                        <i class="fas fa-file-medical-alt"></i>
                      </div>
                      <div class="card-body">
                        <h4>Hasil Pemeriksaan</h4>
                        <a href="hasil_pemeriksaan_dokter.php" class="card-cta">Detail <i class="fas fa-chevron-right"></i></a>
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

<script>
    $(document).ready(function() {
        $(".hari-btn").click(function() {
            var target = $(this).attr("data-target");

            // Sembunyikan semua jadwal, lalu tampilkan yang diklik
            $(".collapse").slideUp();
            $("#" + target).slideDown();

            // Ubah tampilan tombol aktif
            $(".hari-btn").removeClass("active");
            $(this).addClass("active");
        });
    });
</script>
</body>

</html>