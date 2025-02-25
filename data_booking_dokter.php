<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Pasien Booking";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  include "part_func/tgl_ind.php";
  include "part_func/umur.php";

  if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $tgl = $_POST['tgl'];

    $up2 = mysqli_query($conn, "UPDATE pasien SET nama_pasien='$nama', tgl_lahir='$tgl', berat_badan='$berat', tinggi_badan='$tinggi' WHERE id='$id'");
    echo '<script>
				setTimeout(function() {
					swal({
					title: "Data Diubah",
					text: "Data Pasien berhasil diubah!",
					icon: "success"
					});
					}, 500);
				</script>';
  }
  ?>
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
            <h1><?php echo $page; ?></h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Pasien yang Telah Booking</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                          <thead>
                              <tr>
                                  <th class="text-center">#</th>
                                  <th>Nama</th>
                                  <th>Tanggal Lahir</th>
                                  <th>Usia</th>
                                  <th>Tanggal Booking</th>
                                  <th>Hari</th>
                                  <th>Jam</th>
                                  <th>Keluhan</th>
                                  <th class="text-center">Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                                $id_dokter = $_SESSION['id_dokter'];

                                $sql = mysqli_query($conn, "SELECT p.id, p.nama_pasien, p.tgl_lahir, b.tanggal, b.keluhan, j.hari, j.jam_mulai, j.jam_selesai 
                                    FROM pasien p
                                    JOIN jadwal_dokter jd ON p.id = jd.id_pasien
                                    JOIN booking b ON p.id = b.id_pasien
                                    JOIN jadwal j ON jd.id_jadwal = j.id
                                    WHERE jd.status = 1 AND jd.id_dokter = '$id_dokter'");

                              $i = 0;
                              while ($row = mysqli_fetch_array($sql)) {
                                  $idpasien = $row['id'];
                                  $i++;
                              ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      <th><?php echo ucwords($row['nama_pasien']); ?></th>
                                      <td><?php echo ($row['tgl_lahir'] == "0" || $row['tgl_lahir'] == "") ? "Data belum di input" : tgl_indo($row['tgl_lahir']); ?></td>
                                      <td><?php echo ($row['tgl_lahir'] == "0" || $row['tgl_lahir'] == "") ? "Data belum di input" : umur($row['tgl_lahir']); ?></td>
                                      <td><?php echo $row['tanggal']; ?></td>
                                      <td><?php echo $row['hari']; ?></td>
                                      <td><?php echo $row['jam_mulai'] . " - " . $row['jam_selesai']; ?></td>
                                      <td><?php echo $row['keluhan']; ?></td>
                                      <td align="center">
                                        <div class="btn-group">
                                          <form method="POST" action="hasil_pemeriksaan_dokter.php">
                                              <input type="hidden" name="jalan1" value="pilih_pasien">
                                              <input type="hidden" name="id_pasien" value="<?php echo $row['id']; ?>">
                                              
                                              <button type="submit" class="btn btn-primary btn-action mr-1" title="Tindakan" data-toggle="tooltip">
                                                  <i class="fas fa-briefcase-medical"></i>
                                              </button>
                                          </form>
                                          <form method="POST" action="detail_pasien_dokter.php">
                                              <input type="hidden" name="id" value="<?php echo $row['nama_pasien']; ?>">
                                              <button type="submit" class="btn btn-info btn-action mr-1" title="Detail Pasien" data-toggle="tooltip" name="submit">
                                                  <i class="fas fa-info-circle"></i>
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
          </div>
        </section>
      </div>
      <?php include 'part/footer.php'; ?>
    </div>
  </div>
  <?php include "part/all-js.php"; ?>

</body>

</html>