<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Riwayat Pemeriksaan";

  include 'auth/connect.php';
  include "part/head.php";
  
  session_start();
  $sessionid = $_SESSION['id_pasien'];
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$sessionid'");
  $pasien = mysqli_fetch_array($cek);
  $idid = $pasien['id'];
  $nama_pasien = $pasien['nama_pasien'];

  

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
            <h1><?php echo $page; ?></h1>
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
                          <input type="hidden" name="id" value="<?php echo $nama_pasien; ?>">
                          <?php
                          $cekrekam = mysqli_num_rows($rekam);
                          if ($cekrekam == 0) {
                            echo '';
                          } else {
                            echo '<button type="submit" class="btn btn-primary" name="printall">Print Semua</button> &emsp;';
                          } ?>
                          <a href="data_booking_pasien.php?step=pilih_dokter" class="btn btn-primary">Ajukan Booking!</a>
                        </form>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="gallery">
                        <table class="table table-striped table-sm">
                          <tbody>
                            <tr>
                              <th scope="row">Nama Lengkap</th>
                              <td> : <?php echo ucwords($nama_pasien); ?></td>
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
                      <h4>Catatan Riwayat Penyakit Pasien</h4>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-1">
                          <thead>
                            <tr>
                              <th>Tanggal Berobat</th>
                              <th>Penyakit</th>
                              <th>Hasil Pemeriksaan</th>
                              <th>TIndakan</th>
                              <th>Foto Medis</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
  <?php
  $sql = mysqli_query($conn, "
      SELECT rp.id AS id_penyakit, rp.tgl, rp.penyakit, rp.hasil_pemeriksaan, rp.id_rawatinap, 
             (SELECT GROUP_CONCAT(DISTINCT td.nama_tindakan SEPARATOR ', ') 
              FROM riwayat_tindakan rt
              LEFT JOIN tindakan_dokter td ON rt.id_tindakan = td.id
              WHERE rt.id_penyakit = rp.id) AS daftar_tindakan,
             (SELECT COALESCE(SUM(harga), 0) FROM riwayat_tindakan WHERE id_penyakit = rp.id) AS total_harga,
             (SELECT COUNT(id) FROM foto_medis WHERE id_penyakit = rp.id) AS jumlah_foto
      FROM riwayat_penyakit rp
      WHERE rp.id_pasien='$idid'
      ORDER BY rp.tgl ASC
  ");

  while ($row = mysqli_fetch_array($sql)) {
      $id_penyakit = $row['id_penyakit'];
      $daftar_tindakan = $row['daftar_tindakan'] ? ucwords($row['daftar_tindakan']) : "Tidak ada tindakan";
      $total_harga = $row['total_harga'];
      $jumlah_foto = $row['jumlah_foto'];
  ?>
      <tr>
          <td><?php echo ucwords(tgl_indo($row['tgl'])); ?></td>
          <td><?php echo ucwords($row['penyakit']); ?></td>
          <td><?php echo $row['hasil_pemeriksaan']; ?></td>

          <td><?php echo $daftar_tindakan; ?></td>
          <td>
              <?php if ($jumlah_foto == 0) { ?>
                  Tidak ada foto
              <?php } else { ?>
                  <form action="detail_foto_pasien.php" method="POST">
                      <input type="hidden" name="id" value="<?php echo $idid; ?>">
                      <input type="hidden" name="idriwayat" value="<?php echo $id_penyakit; ?>">
                      <button type="submit" title="Detail Foto Rotgen Pasien" data-toggle="tooltip" id="btn-link">
                          <i class="fas fa-info-circle text-info"></i> <?php echo $jumlah_foto; ?> Foto
                      </button>
                  </form>
              <?php } ?>
          </td>
          <td>
            <div class="btn-group">
              <form method="POST" action="print.php" target="_blank">
              <input type="hidden" name="id" value="<?php echo $nama_pasien; ?>">
                  <input type="hidden" name="idriwayat" value="<?php echo $id_penyakit; ?>">
                      <button type="submit" class="btn btn-info" name="detail" title="Detail" data-toggle="tooltip">
                          <i class="fas fa-info"></i>
                      </button>

              </form>
              <form method="POST" action="print.php" target="_blank">
              <input type="hidden" name="id" value="<?php echo $nama_pasien; ?>">
                  <input type="hidden" name="idriwayat" value="<?php echo $id_penyakit; ?>">
                  
                      <button type="submit" class="btn btn-primary" name="printone" title="Print" data-toggle="tooltip">
                          <i class="fas fa-print"></i>
                      </button>

                  </div>

              </form>
              </div> 
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

        </section>
      </div>

      <?php include 'part/footer.php'; ?>
    </div>
  </div>
  <?php include "part/all-js.php"; ?>
</body>

</html>