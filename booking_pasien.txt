<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Booking Fasilitas";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";

  @$nama = $_POST['nama'];
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$nama' OR id='$nama'");
  $cekrow = mysqli_num_rows($cek);
  $tokne = mysqli_fetch_array($cek);
  $tglnow = date('Y-m-d');
  
  if (isset($_POST['jalan1'])) {
    if ($cekrow == 0) {
      mysqli_query($conn, "INSERT INTO pasien (nama_pasien, tinggi_badan, berat_badan) VALUES ('$nama', '0', '0')");
      echo '<script> location.reload(); </script>';
    } else {
      echo '<script>
				setTimeout(function() {
          swal({
						title: "Pasien Telah Terdaftar!",
						text: "Pasien yang bernama ' . ucwords($tokne['nama_pasien']) . ' sudah terdaftar, silahkan lanjutkan ke menu selanjutnya",
						icon: "success"
						});
					}, 500);
			</script>';
    }
  }

  if (isset($_POST['jalan2'])) {
    $idpasien = $_POST['id'];
    $namamu = $_POST['nama'];
    @$tgl = $_POST['tgl'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $alam = $_POST['alamat'];

    mysqli_query($conn, "UPDATE pasien SET alamat='$alam', tgl_lahir='$tgl', berat_badan='$berat', tinggi_badan='$tinggi' WHERE nama_pasien='$namamu'");
    mysqli_query($conn, "INSERT INTO booking (id_pasien, nama_pasien) VALUES ('$idpasien', '$namamu')");
  }

  if (isset($_POST['jalan3'])) {
    @$idpegawai = $_POST['id'];
    $namedokter = $_POST['dokter'];
    $jadwal = $_POST['jadwal'];
    $dokter = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$idpegawai'");
    $datadokter = mysqli_fetch_array($dokter);
    $namadokter = $datadokter['nama_pegawai'];
  }

  if (isset($_POST['submitfoto'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];
    $biaya = $_POST['biaya'];
    $cekriwayat = mysqli_query($conn, "SELECT * FROM `riwayat_penyakit` WHERE penyakit='$penyakit' AND id_pasien='$idpasien' ORDER BY id DESC LIMIT 1");
    $datapasien = mysqli_fetch_array($cekriwayat);
    $idpas = $datapasien['id_pasien'];
    $idpeny = $datapasien['id'];

    if (count($_FILES['upload']['name']) > 0) {
      for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
        $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
        if ($tmpFilePath != "") {
          $filePath = "assets/img/uploads/" . date('d-m-Y-H-i-s') . '-' . $_FILES['upload']['name'][$i];
          if (move_uploaded_file($tmpFilePath, $filePath)) {
            $split = count($_FILES['upload']['tmp_name']);
            $sql = mysqli_query($conn, "INSERT INTO foto_rotgen (id_pasien, id_penyakit, biaya, directory) VALUES ('$idpas', '$idpeny','$biaya', '$filePath')");
          }
        }
      }
    }
    echo '<script>
				setTimeout(function() {
					swal({
						title: "Foto terupload!",
						text: "' . $split . ' Foto Telah Berhasil Diupload",
						icon: "success"
						});
					}, 500);
			</script>';
  }

  if (isset($_POST['pilih_dokter'])) {
    $id_dokter = $_POST['dokter'];
    $tanggal = $_POST['tanggal'];
    $pukul = $_POST['pukul'];
    $dokter = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$id_dokter'");
    $datadokter = mysqli_fetch_array($dokter);
    $namadokter = $datadokter['nama_pegawai'];
    $last = mysqli_query($conn, "SELECT * FROM booking ORDER BY id DESC LIMIT 1");
    $id = mysqli_fetch_array($last);
    $id_book = $id['id'];
    mysqli_query($conn, "UPDATE booking SET dokter_pilih='$namadokter', tanggal='$tanggal', pukul='$pukul' WHERE id='$id_book'");
    echo '<script>
				setTimeout(function() {
					swal({
						title: "Dokter Dipilih!",
						text: "Dokter ' . $namadokter . ' Berhasil Dipilih",
						icon: "success"
						});
					}, 500);
			</script>';
  }

  if (isset($_POST['pesanobat'])) {
    $fasilitas = $_POST['fasilitas'];
    $last = mysqli_query($conn, "SELECT * FROM booking ORDER BY id DESC LIMIT 1");
    $id = mysqli_fetch_array($last);
    $id_book = $id['id'];
    mysqli_query($conn, "UPDATE booking SET fasilitas='$fasilitas' WHERE id='$id_book'");

    if (isset($_POST["obat"])) {
      foreach ($_POST['obat'] as $obat) {
        mysqli_query($conn, "INSERT INTO riwayat_obat (id_penyakit, id_pasien, id_obat, jumlah) VALUES ('$idpeny', '$idpas', '$obat', '$jum')");
        mysqli_query($conn, "UPDATE obat SET stok=(stok - $jum) WHERE id='$obat'");
      }
    }
    echo '<script>
				setTimeout(function() {
					swal({
						title: "Fasilitas Dipilih!",
						text: "Fasilitas berhasil dipilih",
						icon: "success"
						});
					}, 500);
			</script>';
  }

  if (isset($_POST['print'])) {
    $tolologi = mysqli_query($conn, "SELECT * FROM booking ORDER BY id DESC LIMIT 1");
    $lol = mysqli_fetch_array($tolologi);
    $id_book = $lol['id_pasien'];

    $tolologi2 = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$id_book'");
    $lol2 = mysqli_fetch_array($tolologi2);
    $idpasien = $lol2['id'];
  }
  ?>
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
            <h1><?php echo $page; ?></h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Masukkan / Lengkapi Data Diri dan Booking!</h4>
                  </div>
                  <div class="card-body">
                    <div class="row mt-4">
                      <div class="col-12 col-lg-8 offset-lg-1">
                        <div class="wizard-steps">
                          <div class="wizard-step wizard-step-active">
                            <div class="wizard-step-icon">
                              <i class="far fa-user"></i>
                            </div>
                            <div class="wizard-step-label">
                              Nama Anda
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan1']) || isset($_POST['jalan2']) || isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['pilih_dokter']) || isset($_POST['pesanobat']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-server"></i>
                            </div>
                            <div class="wizard-step-label">
                              Informasi Umum
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan2']) || isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['pilih_dokter']) || isset($_POST['pesanobat']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="wizard-step-label">
                              Pilih Jadwal Pemeriksaan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['pilih_dokter']) || isset($_POST['pesanobat']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-briefcase-medical"></i>
                            </div>
                            <div class="wizard-step-label">
                              Fasilitas yang Dibutuhkan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-print"></i>
                            </div>
                            <div class="wizard-step-label">
                              Cetak Formulir
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <form class="wizard-content mt-2 needs-validation" novalidate="" method="POST" autocomplete="off" enctype="multipart/form-data">
                      <div class="wizard-pane">
                        <?php if (empty($_POST)) { ?>

                          <!-- PART 1 -->

                          <div class="form-group row align-items-center">
                             <label class="col-md-4 text-md-right text-left">Nama Lengkap</label>
                            <div class="col-lg-4 col-md-6">
                              <input id="myInput" type="text" class="form-control" name="nama" placeholder="Masukkan nama anda">
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-lg-4 col-md-6 text-right">
                              <button class="btn btn-icon icon-right btn-primary" name="jalan1">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                            </div>
                          </div>
                        <?php }
                        if (isset($_POST['jalan1'])) { ?>

                          <!-- PART 2 -->

                          <div class="form-group row align-items-center">
                            <label class="col-md-4 text-md-right text-left">Nama Lengkap</label>
                            <div class="col-lg-4 col-md-6">
                              <input type="hidden" name="id" class="form-control" required="" value="<?php echo $tokne['id']; ?>">
                              <input type="hidden" name="nama" class="form-control" required="" value="<?php echo $tokne['nama_pasien']; ?>">
                              <input type="text" class="form-control" required="" value="<?php echo $tokne['nama_pasien']; ?>" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left">Tanggal lahir</label>
                            <div class="col-lg-4 col-md-6">
                              <input type="text" class="form-control datepicker" name="tgl" required="">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left col-form-label">Tinggi Badan</label>
                            <div class="input-group col-sm-6 col-lg-4">
                              <input type="number" class="form-control" name="tinggi" required="" value="<?php echo $tokne['tinggi_badan']; ?>">
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  cm
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left col-form-label">Berat Badan</label>
                            <div class="input-group col-sm-6 col-lg-4">
                              <input type="number" class="form-control" name="berat" required="" value="<?php echo $tokne['berat_badan']; ?>">
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  Kg
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left">Alamat</label>
                            <div class="col-lg-4 col-md-6">
                              <textarea type="number" class="form-control" name="alamat" required=""><?php echo $tokne['alamat']; ?></textarea>
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-lg-4 col-md-6 text-right">
                              <button class="btn btn-icon icon-right btn-primary" name="jalan2">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                            </div>
                          </div>
                        <?php }
                        if (isset($_POST['jalan2'])) { ?>

                          <!-- PART 3 -->

                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-striped" id="table-1">
                                <thead>
                                  <tr>
                                    <th>Nama Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Pukul</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <td>
                                        <select class="form-control select2" name="dokter">
                                          <?php
                                              $dokter = mysqli_query($conn, "SELECT * FROM pegawai");
                                              while ($namadokter = mysqli_fetch_array($dokter)) {
                                                if ($namadokter['pekerjaan'] == 1){
                                                  echo "<option value='" . $namadokter['id'] . "'>" . $namadokter['nama_pegawai'] . "</option>";
                                                }
                                              }
                                              ?>
                                          </select>
                                      </td>
                                        <td><input type="date" class="form-control datepicker" name="tanggal" required="">
                                      </td>
                                      <td>
                                        <input type="time" class="form-control" name="pukul">
                                      <td>
                                        <button class="btn btn-primary btn-action mr-1" name="pilih_dokter">Pilih <i class="fas fa-arrow-right"></i></button>
                                      </td>
                          </tr>
                        </tbody>
                      </table>
                          <?php }
                        if (isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['pilih_dokter']) || isset($_POST['pesanobat'])) { ?>

                            <!-- PART 4 -->

                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-4">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                  <li class="nav-item">
                                    <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">Pilih Fasilitas</a>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-12 col-sm-12 col-md-8">
                                <div class="tab-content no-padding" id="myTab2Content">
                                  <div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                    <div class="form-group row mb-4">
                                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Fasilitas yang dibutuhkan</label>
                                      <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="fasilitas">
                                          <option value="Foto Rontgen">Foto Rontgen</option>
                                          <option value="Ruang Inap">Ruang Inap</option>
                                          <option value="Check Up">Check Up</option>
                                        </select>
                                        
                                      </div>
                                    </div>
                                        <div class="form-group row">
                                        <div class="col-md-6"></div>
                                          <div class="col-lg-4 col-md-6 text-right">
                                            <input type="submit" class="btn btn-icon icon-right btn-primary" name="pesanobat" value="Pilih Fasilitas">
                                            <input type="submit" class="btn btn-icon icon-right btn-success" name="print" value="Selesai">
                                          </div>
                                        </div>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                </div>
                              </div>
                            </div>
                          <?php } ?>
                          </div>
                    </form>
                    <?php if (isset($_POST['print'])) { ?>

                      <!-- PART 5 -->
                      <div class="wizard-pane text-center">
                      <form method="POST" action="print_pasien.php" target="_blank">
                        <input type="hidden" name="id" value="<?php echo $idpasien; ?>">
                        <input type="hidden" name="id_book" value="<?php echo $id_book; ?>">
                        <div class="btn-group">
                          <a href="booking_pasien.php"class="btn btn-info" title="Ke Menu Utama" data-toggle="tooltip">Ke Menu Utama</a>
                          <button type="submit" class="btn btn-primary" name="printone" title="Print" data-toggle="tooltip"><i class="fas fa-print"></i> Cetak Formulir</button>
                        </div>
                      </form>
                      </div>
                    <?php } ?>
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
  <?php include "part/all-js.php";
  include "part/autocomplete.php"; ?>
</body>

</html>