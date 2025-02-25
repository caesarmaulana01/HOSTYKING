<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $idnama = $_POST['id'];
  $page1 = "det";
  $page = "Detail Pasien : " . $idnama;
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$idnama'");
  $pasien = mysqli_fetch_array($cek);
  $idid = $pasien['id'];
  ?>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <?php
      include 'part/navbar_admin.php';
      include 'part/sidebar_admin.php';
      include 'part_func/umur.php';
      include 'part_func/tgl_ind.php';
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Detail Pasien</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="pasien_admin.php">Data Pasien</a></div>
              <div class="breadcrumb-item">Detail Pasien : <?php echo ucwords($idnama); ?></div>
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
                          <input type="hidden" name="id" value="<?php echo $idnama; ?>">
                          <?php
                          $cekrekam = mysqli_num_rows($rekam);
                          if ($cekrekam == 0) {
                            echo '';
                          } else {
                            echo '<button type="submit" class="btn btn-primary" name="printall">Print Semua</button> &emsp;';
                          } ?>
                        </form>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="gallery">
                        <table class="table table-striped table-sm">
                          <tbody>
                            <tr>
                              <th scope="row">Nama Lengkap</th>
                              <td> : <?php echo ucwords($idnama); ?></td>
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
                    <th>Dokter</th>
                    <th>Penyakit</th>
                    <th>Hasil Pemeriksaan</th>
                    <th>Tindakan</th>
                    <th>Biaya</th>
                    <th>Foto Medis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                  $sql = mysqli_query($conn, "
                  SELECT rp.id AS id_penyakit, rp.tgl, rp.penyakit, rp.hasil_pemeriksaan, 
                        rp.id_rawatinap, d.nama_dokter, d.spesialisasi,
                        GROUP_CONCAT(DISTINCT td.nama_tindakan SEPARATOR ', ') AS daftar_tindakan,
                        COUNT(DISTINCT fm.id) AS jumlah_foto,
                        (SELECT COALESCE(SUM(rt.harga), 0) 
                        FROM riwayat_tindakan rt 
                        WHERE rt.id_penyakit = rp.id) AS total_harga
                  FROM riwayat_penyakit rp
                  JOIN data_dokter d ON rp.id_dokter_riwayat = d.id
                  LEFT JOIN riwayat_tindakan rt ON rp.id = rt.id_penyakit
                  LEFT JOIN tindakan_dokter td ON rt.id_tindakan = td.id
                  LEFT JOIN foto_medis fm ON rp.id = fm.id_penyakit
                  WHERE rp.id_pasien = '$idid'
                  GROUP BY rp.id
                  ");

                // Hitung total harga dari riwayat tindakan
                // $query_total_harga = mysqli_query($conn, "
                // SELECT COALESCE(SUM(harga), 0) AS total_harga
                // FROM riwayat_tindakan
                // WHERE id_penyakit = '$id_penyakit'
                // ");
                // $data_total_harga = mysqli_fetch_assoc($query_total_harga);
                // $total_harga = $data_total_harga['total_harga'];


                while ($row = mysqli_fetch_array($sql)) {
                    $id_penyakit = $row['id_penyakit'];
                ?>
                    <tr>
                        <td><?php echo ucwords(tgl_indo($row['tgl'])); ?></td>
                        <td><?php echo ucwords($row['nama_dokter']) . " (" . $row['spesialisasi'] . ")"; ?></td>
                        <td><?php echo ucwords($row['penyakit']); ?></td>
                        <td>
                            <?php
                            echo $row['hasil_pemeriksaan'] . " - ";

                            ?>
                        </td>
                        <td><?php echo $row['daftar_tindakan'] ? ucwords($row['daftar_tindakan']) : "Tidak ada tindakan"; ?></td>
                        <td>Rp. <?php echo number_format($row['total_harga'], 0, '.', '.'); ?></td>
                        <td>
                            <?php if ($row['jumlah_foto'] == 0) { ?>
                                Tidak ada foto
                            <?php } else { ?>
                                <form action="detail_rotgen_admin.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $idid; ?>">
                                    <input type="hidden" name="idriwayat" value="<?php echo $id_penyakit ?>">
                                    <button type="submit" title="Detail Foto Rotgen Pasien" data-toggle="tooltip" id="btn-link">
                                        <i class="fas fa-info-circle text-info"></i> <?php echo $row['jumlah_foto']; ?> Foto
                                    </button>
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <form method="POST" action="print.php" target="_blank">
                              <input type="hidden" name="id" value="<?php echo $idnama; ?>">                            
                              <input type="hidden" name="idriwayat" value="<?php echo $id_penyakit; ?>">                            
                                <button type="submit" class="btn btn-info" name="detail" title="Detail" data-toggle="tooltip">
                                  <i class="fas fa-info"></i>
                                </button>                                
                            </form>
                            <form method="POST" action="print_foto.php" target="_blank">
                              <button type="submit" class="btn btn-primary" name="printfoto" title="Print Foto Medis" data-toggle="tooltip">
                                <i class="fas fa-print"></i>
                              </button>
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