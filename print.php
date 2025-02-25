<?php
$idnama = $_POST['id'];
$page1 = "det";
$page = "Detail Pasien : " . $idnama;
session_start();
include 'auth/connect.php';
include "part/head.php";
include 'part_func/umur.php';
include 'part_func/tgl_ind.php';

//All SQL Syntax
$cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$idnama'");
$pasien = mysqli_fetch_array($cek);
$idid = $pasien['id'];

if (isset($_POST['print_foto'])) {
  $idfoto = $_POST['idfoto'];
  $sqlimg = mysqli_query($conn, "SELECT * FROM foto_medis WHERE id_pasien='$idid' AND id_penyakit='$idfoto'");
  $penyakit = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id_pasien='$idid' AND id='$idfoto'");
  $echopen = mysqli_fetch_array($penyakit);
}
?>

<div class="section-body">
  <?php if (isset($_POST['print_foto'])) { ?>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="gallery gallery-md">
              <?php
              if (mysqli_num_rows($sqlimg) == "0") {
                echo 'Tidak ada data';
              } else {
                while ($img = mysqli_fetch_array($sqlimg)) {
                  $dirimg = $img['directory'];

                  echo '<img src="' . $dirimg . '" width="100%" style="margin-bottom: 200px;">';
                }
              } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <div class="row">
      <div class="col-12 col-sm-6 col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4>Info Pasien</h4>
            <div class="card-header-action">
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
                    <th scope="row">Tinggi Badan</th>
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
                            <th>Tindakan</th>
                            <th>Foto Medis</th>
                            <th>Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                      // Menentukan query berdasarkan kondisi percabangan
                      if (isset($_POST['printall'])) {
                        $riwayatpenyakit = mysqli_query($conn, "
                            SELECT rp.id AS id_penyakit, rp.tgl, rp.penyakit, rp.hasil_pemeriksaan, rp.id_rawatinap, 
                                  d.nama_dokter, d.spesialisasi,
                                  (SELECT GROUP_CONCAT(DISTINCT td.nama_tindakan SEPARATOR ', ') 
                                    FROM riwayat_tindakan rt
                                    LEFT JOIN tindakan_dokter td ON rt.id_tindakan = td.id
                                    WHERE rt.id_penyakit = rp.id) AS daftar_tindakan,
                                  (SELECT COALESCE(SUM(harga), 0) FROM riwayat_tindakan WHERE id_penyakit = rp.id) AS total_harga,
                                  (SELECT COUNT(id) FROM foto_medis WHERE id_penyakit = rp.id) AS jumlah_foto
                            FROM riwayat_penyakit rp
                            JOIN data_dokter d ON rp.id_dokter_riwayat = d.id
                            WHERE rp.id_pasien='$idid'
                            ORDER BY rp.tgl ASC
                        ");
                      } elseif (isset($_POST['printone']) || isset($_POST['detail'])) {
                        $idriwayat = $_POST['idriwayat'];
                        $riwayatpenyakit = mysqli_query($conn, "
                            SELECT rp.id AS id_penyakit, rp.tgl, rp.penyakit, rp.hasil_pemeriksaan, rp.id_rawatinap, 
                                  d.nama_dokter, d.spesialisasi,
                                  (SELECT GROUP_CONCAT(DISTINCT td.nama_tindakan SEPARATOR ', ') 
                                    FROM riwayat_tindakan rt
                                    LEFT JOIN tindakan_dokter td ON rt.id_tindakan = td.id
                                    WHERE rt.id_penyakit = rp.id) AS daftar_tindakan,
                                  (SELECT COALESCE(SUM(harga), 0) FROM riwayat_tindakan WHERE id_penyakit = rp.id) AS total_harga,
                                  (SELECT COUNT(id) FROM foto_medis WHERE id_penyakit = rp.id) AS jumlah_foto
                            FROM riwayat_penyakit rp
                            JOIN data_dokter d ON rp.id_dokter_riwayat = d.id
                            WHERE rp.id_pasien='$idid' AND rp.id='$idriwayat'
                        ");
                      }

                      // Loop melalui hasil query
                      while ($row = mysqli_fetch_array($riwayatpenyakit)) {
                        $id_penyakit = $row['id_penyakit'];
                        $daftar_tindakan = $row['daftar_tindakan'] ? ucwords($row['daftar_tindakan']) : "Tidak ada tindakan";
                        $total_harga = $row['total_harga'];
                        $jumlah_foto = $row['jumlah_foto'];
                      ?>
                        <tr>
                            <td><?php echo ucwords(tgl_indo($row['tgl'])); ?></td>
                            <td><?php echo ucwords($row['nama_dokter']) . " (" . $row['spesialisasi'] . ")"; ?></td>
                            <td><?php echo ucwords($row['penyakit']); ?></td>
                            <td><?php echo $daftar_tindakan; ?></td>
                            <td>
                                <?php if ($jumlah_foto == 0) { ?>
                                    Tidak ada foto
                                <?php } else { ?>
                                    <form action="detail_foto_pasien.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $idid; ?>">
                                        <input type="hidden" name="idriwayat" value="<?php echo $id_penyakit; ?>">
                                        <button type="submit" title="Detail Foto Medis Pasien" data-toggle="tooltip" id="btn-link">
                                            <i class="fas fa-info-circle text-info"></i> <?php echo $jumlah_foto; ?> Foto
                                        </button>
                                    </form>
                                <?php } ?>
                            </td>
                            <td><strong>Rp. <?php echo number_format($total_harga, 0, '.', '.'); ?></strong></td>
                        </tr>
                      <?php
                      }
                      ?>

                </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total Harga yang Dibayar</strong></td>
                            <td><strong>Rp. <?php echo number_format($total_harga, 0, '.', '.'); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


          </div>
        </div>
      </div>
    </div>

  <?php }
  if (!isset($_POST['detail'])) {
    if (!isset($_POST['print_foto'])) {
      echo '<footer class="main-footer">
    <div class="footer-left">
      Struk ini dicetak pada tanggal ' . tgl_indo(date('Y-m-d')) . '
    </div>
  </footer>';
    }
    echo '<script> window.print(); </script>';
  } ?>