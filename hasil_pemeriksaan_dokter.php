<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Hasil Pemeriksaan";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";

  @$nama = $_POST['nama'];
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$nama' OR id='$nama'");
  $cekrow = mysqli_num_rows($cek);
  $tokne = mysqli_fetch_array($cek);
  $tglnow = date('Y-m-d');
  $jamnow = date('H:i:s');
  $id_dokter = $_SESSION['id_dokter'];

  if (isset($_POST['jalan3'])) {
    $id_pasien = $_POST['id_pasien'];
    $penyakit = $_POST['penyakit'];
    $hasil_pemeriksaan = $_POST['hasil_pemeriksaan'];

    if (isset($_SESSION['id_dokter'])) {
        $id_dokter_riwayat = $_SESSION['id_dokter'];

        // Simpan ke database
        $query = "INSERT INTO riwayat_penyakit (id_pasien, penyakit, hasil_pemeriksaan, tgl, id_rawatinap, id_dokter_riwayat) 
                  VALUES ('$id_pasien', '$penyakit', '$hasil_pemeriksaan', '$tglnow', '0', '$id_dokter_riwayat')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Ambil ID yang baru saja diinsert
            $id_riwayat_penyakit = mysqli_insert_id($conn);

            // Kirim ke halaman berikutnya dengan form hidden atau session
            $_SESSION['id_riwayat_penyakit'] = $id_riwayat_penyakit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: ID Dokter tidak ditemukan dalam session.";
    }
}


if (isset($_POST['simpan_semua'])) {
    $idpasien = $_POST['id_pasien'];
    $id_riwayat_penyakit = isset($_SESSION['id_riwayat_penyakit']) ? $_SESSION['id_riwayat_penyakit'] : null;

    $idruang = $_POST['ruang'] ?? null;
    $jum = $_POST['jumlah'] ?? 0;
    $id_dokter = $_SESSION['id_dokter'];
    $total_harga = 0;

    if (empty($_POST['obat']) && empty($_POST['jumlah']) && empty($_POST['ruang']) && $_POST['ruang'] == '' && empty($_FILES['foto_medis']['name']) && empty($_POST['labtest']) && $_POST['labtest'] == '' && empty($_POST['injeksi']) && $_POST['injeksi'] == '' && empty($_POST['infus']) && $_POST['infus'] == '' && empty($_POST['nebulizer']) && $_POST['nebulizer'] == '') {
      $tindakan = NULL;
      $detail = "Tidak ada tindakan yang diberikan";
      $status = 'selesai';

      mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, id_tindakan, status, detail) 
                                                 VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$tindakan', '$status', '$detail')");
    }



    // **2. Pemberian Obat**
    if (!empty($_POST['obat']) && !empty($_POST['jumlah'])) {

      $tindakan = 1;

      foreach ($_POST['obat'] as $index => $obat) {
          $jumlah = (int) $_POST['jumlah']; // Hanya satu jumlah karena tidak dalam array
          if ($jumlah > 0) {
              mysqli_query($conn, "INSERT INTO riwayat_obat (id_penyakit, id_pasien, id_obat, jumlah) 
                                  VALUES ('$id_riwayat_penyakit', '$idpasien', '$obat', '$jumlah')");
              mysqli_query($conn, "UPDATE obat SET stok = stok - $jumlah WHERE id='$obat'");
              $detail = "Obat: $obat, Jumlah: $jumlah";

              $query_harga_obat = mysqli_query($conn, "SELECT harga FROM obat WHERE id = '$obat'");
              $data_harga_obat = mysqli_fetch_assoc($query_harga_obat);
              $harga_obat = $data_harga_obat['harga'] ?? 0;
          
              $total_harga += ($harga_obat * $jumlah);
              $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
              WHERE id_penyakit='$id_riwayat_penyakit' 
              AND id_pasien='$idpasien' 
              AND id_tindakan='$tindakan'");
              if (mysqli_num_rows($cek_tindakan) == 0) {
                  mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
                  VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$total_harga', '$tindakan', '$detail')");
              }
          }
      }
    } 



if (!empty($_POST['ruang']) && $_POST['ruang'] !== '') {  // Hanya lanjut jika ruang valid dipilih
      $idruang = $_POST['ruang'];

      $tindakan = 3;
      $detail = "Ruangan: " . $_POST["ruang"];

      // Cek apakah pasien sudah ada dalam ruang inap
      $cek_pasien = mysqli_query($conn, "SELECT id FROM ruang_inap WHERE id_pasien='$idpasien'");
      $data_pasien = mysqli_fetch_assoc($cek_pasien);


      if ($data_pasien && $data_pasien['id'] != $idruang) {
          // Jika pasien pindah ruangan, kosongkan ruangan sebelumnya
          mysqli_query($conn, "UPDATE ruang_inap 
                              SET id_pasien=NULL, tgl_masuk=NULL, jam_masuk=NULL, status='0' 
                              WHERE id='$data_pasien[id]'");
      }

      // Masukkan pasien ke ruangan baru
      mysqli_query($conn, "UPDATE ruang_inap 
                          SET id_pasien='$idpasien', tgl_masuk='$tglnow', jam_masuk='$jamnow', status='1' 
                          WHERE id='$idruang'");

      // Update riwayat penyakit dengan ID ruang inap baru
      mysqli_query($conn, "UPDATE riwayat_penyakit 
                          SET id_rawatinap='$idruang' 
                          WHERE id='$id_riwayat_penyakit'");
      $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
          WHERE id_penyakit='$id_riwayat_penyakit' 
          AND id_pasien='$idpasien' 
          AND id_tindakan='$tindakan'");
      if (mysqli_num_rows($cek_tindakan) == 0) {
        $query_harga_ruang = mysqli_query($conn, "SELECT biaya FROM ruang_inap WHERE id = '$idruang'");
        $data_harga_ruang = mysqli_fetch_assoc($query_harga_ruang);
        // $harga_ruang = $data_harga_ruang['biaya'] ?? 0;
        $total_harga = (int) ($data_harga_ruang['biaya'] ?? 0);
        // $total_harga += $harga_ruang;
        
        mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
        VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$total_harga', '$tindakan', '$detail')");
        
      }
}

    // **4. Upload Foto Medis**
    if (!empty($_FILES['foto_medis']['name'])) {
      $fileTmpPath = $_FILES['foto_medis']['tmp_name'];

      $tindakan = 2;
      $detail = "Jenis Foto: " . $_POST["jenis_foto"];
      if (!empty($fileTmpPath)) {
          $fileName = basename($_FILES['foto_medis']['name']);
          $filePath = "assets/img/uploads/" . date('d-m-Y-H-i-s') . '-' . $fileName;
          if (move_uploaded_file($fileTmpPath, $filePath)) {
              $jenis_foto = $_POST['jenis_foto'] ?? 'Rontgen';
              mysqli_query($conn, "INSERT INTO foto_medis (id_pasien, id_penyakit, jenis, directory) 
                                  VALUES ('$idpasien', '$id_riwayat_penyakit', '$jenis_foto', '$filePath')");
          }
      }
      $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
          WHERE id_penyakit='$id_riwayat_penyakit' 
          AND id_pasien='$idpasien' 
          AND id_tindakan='$tindakan'");
      if (mysqli_num_rows($cek_tindakan) == 0) {
        $query_harga = mysqli_query($conn, "SELECT harga FROM tindakan_dokter WHERE id = '$tindakan'");
        $data_harga = mysqli_fetch_assoc($query_harga);
        $harga = $data_harga['harga'] ?? 0; // Ambil harga atau set ke 0 jika null
        
        mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
        VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$harga', '$tindakan', '$detail')");
        
    }
  }


    // 1. Pemeriksaan Laboratorium
    if (!empty($_POST['labtest']) && $_POST['labtest'] != '') {
        $tindakan = 4;
        $detail = "Jenis Pemeriksaan: " . $_POST['labtest'];
        $query_harga = mysqli_query($conn, "SELECT harga FROM tindakan_dokter WHERE id = '$tindakan'");
        $data_harga = mysqli_fetch_assoc($query_harga);
        $harga = $data_harga['harga'] ?? 0; // Ambil harga atau set ke 0 jika null
        $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
        WHERE id_penyakit='$id_riwayat_penyakit' 
        AND id_pasien='$idpasien' 
        AND id_tindakan='$tindakan'");
        if (mysqli_num_rows($cek_tindakan) == 0) {
          mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
          VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$harga', '$tindakan', '$detail')");
        }
    }

    // 2. Pemberian Suntikan
    if (!empty($_POST['injeksi']) && $_POST['injeksi'] != '') {
        $tindakan = 5;
        $detail = "Jenis Suntikan: " . $_POST['injeksi'];
        $query_harga = mysqli_query($conn, "SELECT harga FROM tindakan_dokter WHERE id = '$tindakan'");
        $data_harga = mysqli_fetch_assoc($query_harga);
        $harga = $data_harga['harga'] ?? 0; // Ambil harga atau set ke 0 jika null
        $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
        WHERE id_penyakit='$id_riwayat_penyakit' 
        AND id_pasien='$idpasien' 
        AND id_tindakan='$tindakan'");
        if (mysqli_num_rows($cek_tindakan) == 0) {
          mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
          VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$harga', '$tindakan', '$detail')");
        }
        
    }

    // 3. Pemasangan Infus
    if (!empty($_POST['infus']) && $_POST['infus'] != '') {
        $tindakan = 6;
        $detail = "Jenis Cairan Infus: " . $_POST['infus'];
        $query_harga = mysqli_query($conn, "SELECT harga FROM tindakan_dokter WHERE id = '$tindakan'");
        $data_harga = mysqli_fetch_assoc($query_harga);
        $harga = $data_harga['harga'] ?? 0; // Ambil harga atau set ke 0 jika null
        $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
        WHERE id_penyakit='$id_riwayat_penyakit' 
        AND id_pasien='$idpasien' 
        AND id_tindakan='$tindakan'");
        if (mysqli_num_rows($cek_tindakan) == 0) {
          mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
          VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$harga', '$tindakan', '$detail')");
        }
        
    }

    // 4. Nebulizer
    if (!empty($_POST['nebulizer']) && $_POST['nebulizer'] != '') {
        $tindakan = 7;
        $detail = "Jenis Obat Nebulizer: " . $_POST['nebulizer'];
        $query_harga = mysqli_query($conn, "SELECT harga FROM tindakan_dokter WHERE id = '$tindakan'");
        $data_harga = mysqli_fetch_assoc($query_harga);
        $harga = $data_harga['harga'] ?? 0; // Ambil harga atau set ke 0 jika null
        $cek_tindakan = mysqli_query($conn, "SELECT id FROM riwayat_tindakan 
        WHERE id_penyakit='$id_riwayat_penyakit' 
        AND id_pasien='$idpasien' 
        AND id_tindakan='$tindakan'");
        if (mysqli_num_rows($cek_tindakan) == 0) {
          mysqli_query($conn, "INSERT INTO riwayat_tindakan (id_penyakit, id_pasien, id_dokter, harga, id_tindakan, detail) 
          VALUES ('$id_riwayat_penyakit', '$idpasien', '$id_dokter', '$harga', '$tindakan', '$detail')");
        }
        
    }



    // **5. Hapus Data Booking**
    mysqli_query($conn, "DELETE FROM booking WHERE id_pasien='$idpasien'");

    // **6. Update Jadwal Dokter**
    mysqli_query($conn, "UPDATE jadwal_dokter SET id_pasien=NULL, status='0' WHERE id_pasien='$idpasien'");

    // **7. Konfirmasi Berhasil**
    echo '<script>
            setTimeout(function() {
                swal({
                    title: "Tindakan Berhasil!",
                    text: "Semua tindakan telah tersimpan.",
                    icon: "success"
                });
            }, 500);
          </script>';

}


  if (isset($_POST['print'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];

    $tolologi = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE penyakit='$penyakit' AND id_pasien='$idpasien' ORDER BY id DESC LIMIT 1");
    $lol = mysqli_fetch_array($tolologi);
    $tolologi2 = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$idpasien'");
    $lol2 = mysqli_fetch_array($tolologi2);
    $penyyy = $lol['id'];
    $passs = $lol2['nama_pasien'];
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
                    <h4>Masukkan Data serta Tindakan untuk Pasien</h4>
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
                              Identitas Pasien
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan1']) || isset($_POST['jalan2']) || isset($_POST['jalan3']) || isset($_FILES['foto_medis']) || isset($_POST['ruang']) || isset($_POST['jumlah']) || isset($_POST['labtest']) || isset($_POST['injeksi']) || isset($_POST['infus']) || isset($_POST['nebulizer'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-server"></i>
                            </div>
                            <div class="wizard-step-label">
                              Informasi Umum
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan2']) || isset($_POST['jalan3']) || isset($_FILES['foto_medis']) || isset($_POST['ruang']) || isset($_POST['jumlah']) || isset($_POST['labtest']) || isset($_POST['injeksi']) || isset($_POST['infus']) || isset($_POST['nebulizer'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="wizard-step-label">
                              Pemeriksaan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan3']) || isset($_FILES['foto_medis']) || isset($_POST['ruang']) || isset($_POST['jumlah']) || isset($_POST['labtest']) || isset($_POST['injeksi']) || isset($_POST['infus']) || isset($_POST['nebulizer'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-briefcase-medical"></i>
                            </div>
                            <div class="wizard-step-label">
                              Tindakan yang dilakukan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['simpan_semua'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-print"></i>
                            </div>
                            <div class="wizard-step-label">
                              Cetak Struk
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <form class="wizard-content mt-2 needs-validation" novalidate="" method="POST" autocomplete="off" enctype="multipart/form-data">
                      <div class="wizard-pane">
                        <?php if (empty($_POST) && (isset($_GET['step']) && $_GET['step'] == 'pilih_pasien')) { ?>

                          <!-- PART 1 -->
                          <form method="POST">
                            <div class="form-group row align-items-center">
                                <label class="col-md-4 text-md-right text-left">Pilih Pasien</label>
                                <div class="col-lg-4 col-md-6">
                                    <select class="form-control" name="id_pasien" required>
                                        <option value="" disabled selected>Pilih Nama Pasien</option>
                                        <?php
                                        $query = mysqli_query($conn, "SELECT DISTINCT id_pasien, nama_pasien FROM booking WHERE dokter_pilih='$id_dokter' ORDER BY nama_pasien ASC ");
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<option value='{$row['id_pasien']}'>{$row['nama_pasien']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Mohon pilih data!
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4"></div>
                                <div class="col-lg-4 col-md-6 text-right">
                                    <button class="btn btn-icon icon-right btn-primary" name="jalan1">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </form>


                        <?php }
                            if (isset($_POST['jalan1'])) {
                                $id_pasien = $_POST['id_pasien'];

                                // Ambil data pasien dari tabel pasien
                                $query = mysqli_query($conn, "SELECT * FROM pasien WHERE id = '$id_pasien'");
                                $tokne = mysqli_fetch_assoc($query);
                            ?>

                            <form method="POST">
                                <input type="hidden" name="id_pasien" value="<?php echo $tokne['id']; ?>">

                                <div class="form-group row align-items-center">
                                    <label class="col-md-4 text-md-right text-left">Nama Lengkap</label>
                                    <div class="col-lg-4 col-md-6">
                                        <input type="text" class="form-control" value="<?php echo $tokne['nama_pasien']; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 text-md-right text-left">Tanggal Lahir</label>
                                    <div class="col-lg-4 col-md-6">
                                        <input type="text" class="form-control" value="<?php echo $tokne['tgl_lahir']; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 text-md-right text-left">NIK</label>
                                    <div class="col-lg-4 col-md-6">
                                        <input type="text" class="form-control" value="<?php echo $tokne['nik']; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 text-md-right text-left">Tinggi Badan</label>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="<?php echo $tokne['tinggi_badan']; ?>" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">cm</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 text-md-right text-left">Berat Badan</label>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="<?php echo $tokne['berat_badan']; ?>" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">Kg</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 text-md-right text-left">Alamat</label>
                                    <div class="col-lg-4 col-md-6">
                                        <textarea class="form-control" readonly><?php echo $tokne['alamat']; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-lg-4 col-md-6 text-right">
                                        <button class="btn btn-icon icon-right btn-primary" name="jalan2">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                                    </div>
                                </div>
                            </form>


                        <?php }
                        if (isset($_POST['jalan2'])) { 
                          $id_pasien = $_POST['id_pasien'];
                          ?>

                          <!-- PART 3 -->
                          
                          <div class="card-body">
                            <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Penyakit</label>
                              <div class="col-sm-12 col-md-7">
                                <input type="hidden" class="form-control" name="id_pasien" required="" value="<?php echo $id_pasien; ?>">
                                <input type="text" class="form-control" name="penyakit" required="">
                                <div class="invalid-feedback">
                                  Mohon data diisi!
                                </div>
                              </div>
                            </div>
                            <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hasil Pemeriksaan</label>
                              <div class="col-sm-12 col-md-7">
                                <textarea class="summernote" name="hasil_pemeriksaan" placeholder="Wajib Diisi!" ></textarea>
                              </div>
                            </div>
                      
                            <div class="form-group row">
                              <div class="col-md-6"></div>
                              <div class="col-lg-4 col-md-6 text-right">
                                <button class="btn btn-icon icon-right btn-primary" name="jalan3">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                              </div>
                            </div>
                            <?php }
                              if (isset($_POST['jalan3'])) { ?>
                                <?php
                                // Ambil semua tindakan dokter dari database
                                $id_pasien = $_POST['id_pasien'];
                                $tindakan_dokter = mysqli_query($conn, "SELECT * FROM tindakan_dokter");
                                ?>

                                <form method="post" action="proses_tindakan.php" enctype="multipart/form-data">
                                  <input type="hidden" class="form-control" name="id_pasien" required value="<?php echo $id_pasien; ?>">
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                                <?php
                                                while ($data = mysqli_fetch_array($tindakan_dokter)) {
                                                    echo "<li class='nav-item'>
                                                            <a class='nav-link' data-toggle='tab' href='#tab$data[id]' onclick='activateTab($data[id])'>$data[nama_tindakan]</a>
                                                          </li>";
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        
                                        <div class="col-12 col-md-8">
                                            <div class="tab-content no-padding">
                                            <?php
                                              mysqli_data_seek($tindakan_dokter, 0);
                                              while ($data = mysqli_fetch_array($tindakan_dokter)) {
                                                  echo "<div class='tab-pane fade' id='tab$data[id]'>";
                                                  switch ($data['nama_tindakan']) {
                                                      case 'Pemberian Obat':
                                                        echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                        echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                          echo "<div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Obat</label>
                                                                  <div class='col-md-7'>
                                                                      <select class='form-control select2 w-100' name='obat[]' multiple='multiple'  style='width: 100%;'>";
                                                          $obat2an = mysqli_query($conn, "SELECT * FROM obat WHERE stok >= 1");
                                                          while ($obat = mysqli_fetch_array($obat2an)) {
                                                              echo "<option value='$obat[id]'>$obat[nama_obat]</option>";
                                                          }
                                                          echo "</select>
                                                                  </div>
                                                                </div>
                                                                <div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Jumlah</label>
                                                                  <div class='col-md-7'>
                                                                      <input type='number' class='form-control' name='jumlah' value='0'>
                                                                  </div>
                                                                </div>";
                                                          break;

                                                      
                                                      case 'Foto Medis':
                                                        echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                        echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                          echo "<div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Jenis Foto</label>
                                                                  <div class='col-md-7'>
                                                                      <select class='form-control w-100' name='jenis_foto'>
                                                                          <option value='Rontgen'>Rontgen</option>
                                                                          <option value='USG'>USG</option>
                                                                      </select>
                                                                  </div>
                                                                </div>
                                                                <div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Unggah Foto</label>
                                                                  <div class='col-md-7'>
                                                                      <input type='file' class='form-control' name='foto_medis'>
                                                                  </div>
                                                                </div>";
                                                          break;
                                                          case 'Rawat Inap':
                                                            echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                            echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                            echo "<div class='form-group row mb-4'>
                                                                    <label class='col-md-3'>Pilih Ruangan</label>
                                                                    <div class='col-md-7'>
                                                                        <select class='form-control w-100' name='ruang'>
                                                                            <option value=''>-- Pilih Ruangan --</option>"; // Tambahkan nilai default kosong
                                                        
                                                            $ruang = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE status='0'");
                                                            while ($namaruang = mysqli_fetch_array($ruang)) {
                                                                echo "<option value='$namaruang[id]'>$namaruang[nama_ruang]</option>";
                                                            }
                                                        
                                                            echo "</select>
                                                                    </div>
                                                                  </div>";
                                                            break;
                                                        
                                                      case 'Pemeriksaan Laboratorium':
                                                        echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                        echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                          echo "<div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Jenis Pemeriksaan</label>
                                                                  <div class='col-md-7'>
                                                                      <select class='form-control w-100' name='labtest'>
                                                                          <option value=''>-- Pilih Tes --</option>
                                                                          <option value='Darah'>Tes Darah</option>
                                                                          <option value='Urine'>Tes Urine</option>
                                                                      </select>
                                                                  </div>
                                                                </div>";
                                                          break;
                                                      case 'Pemberian Suntikan':
                                                        echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                        echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                          echo "<div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Jenis Suntikan</label>
                                                                  <div class='col-md-7'>
                                                                      <select class='form-control w-100' name='injeksi'>
                                                                          <option value=''>-- Pilih Cairan --</option>
                                                                          <option value='Antibiotik'>Antibiotik</option>
                                                                          <option value='Vitamin'>Vitamin</option>
                                                                          <option value='Vaksin'>Vaksin</option>
                                                                      </select>
                                                                  </div>
                                                                </div>";
                                                          break;
                                                      case 'Pemasangan Infus':
                                                        echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                        echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                          echo "<div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Jenis Cairan Infus</label>
                                                                  <div class='col-md-7'>
                                                                      <select class='form-control w-100' name='infus'>
                                                                          <option value=''>-- Pilih Cairan --</option>
                                                                          <option value='NaCl'>NaCl</option>
                                                                          <option value='Glukosa'>Glukosa</option>
                                                                          <option value='Ringer Laktat'>Ringer Laktat</option>
                                                                      </select>
                                                                  </div>
                                                                </div>";
                                                          break;
                                                      case 'Nebulizer':
                                                        echo "<h4 class='mb-3'>$data[nama_tindakan]</h4>";
                                                        echo "<input type='hidden' name='tindakan[]' value='$data[id]'>";
                                                          echo "<div class='form-group row mb-4'>
                                                                  <label class='col-md-3'>Jenis Obat Nebulizer</label>
                                                                  <div class='col-md-7'>
                                                                      <select class='form-control w-100' name='nebulizer'>
                                                                          <option value=''>-- Pilih Obat --</option>
                                                                          <option value='Salbutamol'>Salbutamol</option>
                                                                          <option value='Ipratropium'>Ipratropium</option>
                                                                      </select>
                                                                  </div>
                                                                </div>";
                                                          break;
                                                  }
                                                  echo "<div class='text-right mb-3'>
                                                          <button type='button' class='btn btn-danger' onclick='deactivateTab($data[id])'>Batalkan Tindakan</button>
                                                        </div>
                                                      </div>";
                                          
                                              }
                                              ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-icon icon-right btn-primary" name="simpan_semua">Selanjutnya</button>
                                        </div>
                                    </div>
                                </form>
                            </form>
                      <?php } if (isset($_POST['simpan_semua'])) { ?>

                      <!-- PART 5 -->
                      <div class="wizard-pane text-center">
                      <form method="POST" action="print.php" target="_blank">
                        <input type="hidden" name="id" value="<?php echo $passs; ?>">
                        <input type="hidden" name="idriwayat" value="<?php echo $penyyy; ?>">
                        <div class="btn-group">
                          <a href="hasil_pemeriksaan_dokter.php?step=pilih_dokter"class="btn btn-info" title="Ke Menu Utama" data-toggle="tooltip">Ke Menu Utama</a>
                          <button type="submit" class="btn btn-primary" name="printone" title="Print" data-toggle="tooltip"><i class="fas fa-print"></i> Cetak Struk Pembayaran</button>
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
<script>
function activateTab(id) {
    document.querySelector(`#tab${id}`).classList.add('show', 'active');
    document.querySelector(`[href='#tab${id}']`).classList.add('active');
}

function deactivateTab(id) {
    document.querySelector(`#tab${id}`).classList.remove('show', 'active');
    document.querySelector(`[href='#tab${id}']`).classList.remove('active');
}
</script>
</body>

</html>