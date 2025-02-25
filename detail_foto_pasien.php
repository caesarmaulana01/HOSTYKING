<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $id = $_POST['id'];
  $page1 = "detrot";
  session_start();
  include 'auth/connect.php';
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$id'");
  $pasien = mysqli_fetch_array($cek);
  $page = "Foto Medis Pasien : " . $pasien['nama_pasien'];
  include "part/head.php";

  $idid = $pasien['id'];
  $idpenyakit = $_POST['idriwayat'];

  $sqlimg = mysqli_query($conn, "SELECT * FROM foto_medis WHERE id_pasien='$idid' AND id_penyakit='$idpenyakit'");
  $penyakit = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id_pasien='$idid' AND id='$idpenyakit'");
  $echopen = mysqli_fetch_array($penyakit);
  ?>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <?php
      include 'part/navbar_pasien.php';
      include 'part/sidebar_pasien.php';
      include 'part_func/umur.php';
      include 'part_func/tgl_ind.php';
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Foto Medis Pasien</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="riwayat_pasien.php">Foto Medis</a></div>
              <div class="breadcrumb-item">Foto Medis : <?php echo ucwords($pasien['nama_pasien']); ?></div>
            </div>
          </div>

          <div class="section-body">
            <?php include 'part/info_pasien.php'; ?>

            <div class="section-body">
              <div class="row">
                <div class="col-12 col-sm-6 col-lg-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Info Pasien</h4>
                      <div class="card-header-action">
                        <form method="POST" action="print.php" target="_blank">
                          <input type="hidden" name="id" value="<?php echo ucwords($pasien['nama_pasien']); ?>">
                          <input type="hidden" name="idfoto" value="<?php echo $idpenyakit; ?>">
                          <?php
                          $cekrekam = mysqli_num_rows($rekam);
                          if ($cekrekam == 0) {
                            echo '';
                          } else {
                            echo '<button type="submit" class="btn btn-primary" name="print_foto">Print Foto</button> &emsp;';
                          } ?>
                          <a href="data_booking_pasien.php?step=pilih_dokter" class="btn btn-primary">Booking</a>
                        </form>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="gallery">
                        <table class="table table-striped table-sm">
                          <tbody>
                            <tr>
                              <th scope="row">Nama Lengkap</th>
                              <td> : <?php echo ucwords($pasien['nama_pasien']); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Tanggal Lahir</th>
                              <td> : <?php echo tgl_indo($pasien['tgl_lahir']); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Tinggi Bandan</th>
                              <td> : <?php echo $pasien['tinggi_badan'] . " cm"; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Berat Badan</th>
                              <td> : <?php echo $pasien['berat_badan'] . " kg"; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Alamat</th>
                              <td> : <?php echo $pasien['alamat']; ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Semua Foto Medis Penyakit : <?php echo $echopen['penyakit']; ?></h4>
                    </div>
                    <div class="card-body">
                      <div class="gallery gallery-md">
                        <?php
                        if (mysqli_num_rows($sqlimg) == "0") {
                          echo 'Tidak ada data';
                        } else {
                          while ($img = mysqli_fetch_array($sqlimg)) {
                            echo '<div class="gallery-item" data-image="' . $img['directory'] . '" data-title="Penyakit : ' . $echopen['penyakit'] . ' (' . tgl_indo($echopen['tgl']) . ')"></div>';
                          }
                        } ?>
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