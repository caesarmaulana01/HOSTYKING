<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Booking Fasilitas";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";

  // Ambil data pasien berdasarkan input nama atau ID
  @$nama = $_POST['nama'];
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$nama' OR id='$nama'");
  $cekrow = mysqli_num_rows($cek);
  $tokne = mysqli_fetch_assoc($cek);
  $tglnow = date('Y-m-d');
  $id_pasien = $_SESSION['id_pasien'];
  $query = "SELECT * FROM booking WHERE id_pasien = '$id_pasien'";
  $result = mysqli_query($conn, $query);
  $booking = mysqli_fetch_assoc($result);

  // Proses pembatalan booking
  if (isset($_POST['batal_booking'])) {
      
    $id_booking = $_POST['id_booking'];

    // Ambil data jadwal terkait booking sebelum dihapus
    $query_jadwal = "SELECT id_jadwal FROM booking WHERE id = '$id_booking'";
    $result_jadwal = mysqli_query($conn, $query_jadwal);
    
    if ($result_jadwal && mysqli_num_rows($result_jadwal) > 0) {
        $jadwal = mysqli_fetch_assoc($result_jadwal);
        $id_jadwal = $jadwal['id_jadwal'];

        // Hapus booking dari tabel booking
        $query_delete = "DELETE FROM booking WHERE id = '$id_booking'";
        mysqli_query($conn, $query_delete);

        // Update jadwal dokter: set status = 0 dan id_pasien = NULL
        $query_update_jadwal = "UPDATE jadwal_dokter 
                                SET status = 0, id_pasien = NULL 
                                WHERE id_jadwal = '$id_jadwal'";
        mysqli_query($conn, $query_update_jadwal);

        // Tampilkan notifikasi sukses
        echo '<script>
        setTimeout(function() {
            swal({
                title: "Batal Booking",
                text: "Booking Berhasil Dibatalkan!",
                icon: "success"
            }).then(function() {
                window.location.href = "data_booking_pasien.php?step=pilih_dokter";
            });
        }, 500);
        </script>';
    }
  }


  // Pilih dokter
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pilih_dokter'])) {
      $_SESSION['pilih_dokter'] = true;
  }

  // Notifikasi setelah memilih dokter
  if (isset($_POST['pilih_jadwal'])) {
      unset($_GET['step']);
      $iddokter = $_POST['id_dokter'];
      $_SESSION['id_dokter'] = $iddokter;
      $dokter = mysqli_query($conn, "SELECT * FROM data_dokter WHERE id='$iddokter'");
      $jadwaldokter = mysqli_fetch_array($dokter);
      $namadokter = $jadwaldokter['nama_dokter'];

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

  // STEP 3: KONFIRMASI JADWAL
  if (isset($_POST['konfirmasi_jadwal'])) {
      unset($_GET['step']);
      $_SESSION['id_jadwal'] = $_POST['id_jadwal'];
      $_SESSION['tanggal_jadwal'] = $_POST['tanggal_jadwal'];
  }
  function formatKeluhan($keluhan) {
      // Hapus tag HTML yang tidak diperlukan, kecuali <li>
      $keluhan = strip_tags($keluhan, '<li>');

      // Hapus atribut dalam tag <li>
      $keluhan = preg_replace('/<li[^>]*>/', '<li>', $keluhan);

      // Jika sudah dalam format <li>, cukup bungkus dengan <ul>
      if (strpos($keluhan, '<li>') !== false) {
          $keluhan = "<ul>" . $keluhan . "</ul>";
      } else {
          // Jika tidak ada <li>, pecah berdasarkan titik atau baris baru
          $keluhan = preg_replace("/[\r\n]+/", ". ", $keluhan); // Gabungkan baris baru jadi satu kalimat
          $kalimat = preg_split('/\.\s+/', trim($keluhan), -1, PREG_SPLIT_NO_EMPTY);

          if (count($kalimat) > 1) {
              $keluhan = "<ul><li>" . implode("</li><li>", $kalimat) . "</li></ul>";
          } else {
              $keluhan = "<p>" . $keluhan . "</p>"; // Jika hanya satu kalimat, tetap dalam <p>
          }
      }

      return $keluhan;
  }



  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['print'])) {


    unset($_GET['step']);
    $_SESSION['keluhan'] = $_POST['keluhan'];

    $id_pasien = $_SESSION['id_pasien'];
    $id_dokter = $_SESSION['id_dokter'];
    $id_jadwal = $_SESSION['id_jadwal'];
    $tanggal_jadwal = $_SESSION['tanggal_jadwal'];
    $keluhan = formatKeluhan($_SESSION['keluhan']);


    $dokter = mysqli_query($conn, "SELECT * FROM data_dokter WHERE id='$id_dokter'");
    $fetch = mysqli_fetch_array($dokter);
    $namadokter = $fetch['nama_dokter'];
    echo '<script>
    setTimeout(function() {
        swal({
            title: "Booking Berhasil!",
            text: "Berhasil Booking Dokter ' . $namadokter . ' pada tanggal ' . $tanggal_jadwal . '",
            icon: "success"
        });
    }, 500);
    </script>';

    // Ambil nama pasien
    $query_pasien = mysqli_query($conn, "SELECT nama_pasien FROM pasien WHERE id = '$id_pasien'");
    $data_pasien = mysqli_fetch_assoc($query_pasien);
    $nama_pasien = $data_pasien['nama_pasien'];

    // Simpan ke tabel booking
    $query_insert = "INSERT INTO booking (id_pasien, nama_pasien, dokter_pilih, tanggal, keluhan, id_jadwal) 
                     VALUES ('$id_pasien', '$nama_pasien', '$id_dokter', '$tanggal_jadwal', '$keluhan', '$id_jadwal')";
    mysqli_query($conn, $query_insert);

    // Update tabel jadwal_dokter
    $query_update = "UPDATE jadwal_dokter SET id_pasien = '$id_pasien', status = 1 
                     WHERE id_dokter = '$id_dokter' AND id_jadwal = '$id_jadwal'";
    mysqli_query($conn, $query_update);


      
    $query = "SELECT id FROM booking WHERE id_pasien='$id_pasien' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    $row = mysqli_fetch_assoc($result);
    $id_book = $row['id'];
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
                    <h4>Booking!</h4>
                  </div>
                  <div class="card-body">
                    <div class="row mt-4">
                      <div class="col-12 col-lg-8 offset-lg-1">
                      <?php
                        if ($booking) {
                        ?>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th style="width: 150px; word-wrap: break-word;">Dokter yang Dipilih</th>
                                          <th>Tanggal</th>
                                          <th>Hari</th>
                                          <th>Jam</th>
                                          <th>Keluhan</th>
                                          <th>Aksi</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php
                                      // Query untuk mengambil data booking, dokter, dan jadwal
                                      $query = "SELECT booking.*, 
                                            data_dokter.nama_dokter AS nama_dokter, 
                                            jadwal.hari AS hari, 
                                            jadwal.jam_mulai AS jam_mulai, 
                                            jadwal.jam_selesai AS jam_selesai 
                                      FROM booking 
                                      LEFT JOIN data_dokter ON booking.dokter_pilih = data_dokter.id 
                                      LEFT JOIN jadwal_dokter ON jadwal_dokter.id_dokter = data_dokter.id 
                                      LEFT JOIN jadwal ON jadwal.id = booking.id_jadwal 
                                      WHERE booking.id_pasien = '$id_pasien'
                                      AND jadwal.id IS NOT NULL  -- Pastikan hanya jadwal yang valid yang diambil
                                      GROUP BY booking.id -- Mencegah duplikasi dengan mengelompokkan berdasarkan booking
                                      ORDER BY booking.id;
                                      ";
                      

                                      $result = mysqli_query($conn, $query);

                                      while ($booking = mysqli_fetch_array($result)) {
                                      ?>
                                          <tr>
                                              <td><?php echo ucwords($booking['nama_dokter']); ?></td> <!-- Nama dokter -->
                                              <td><?php echo $booking['tanggal']; ?></td> <!-- Tanggal booking -->
                                              <td><?php echo $booking['hari']; ?></td> <!-- Hari -->
                                              <td><?php echo $booking['jam_mulai'] . " - " . $booking['jam_selesai']; ?></td> <!-- Jam mulai - selesai -->
                                              <td><?php echo $booking['keluhan']; ?></td> <!-- Keluhan -->
                                              <td>
                                                <!-- Gunakan class 'd-flex' untuk membuat tombol sejajar -->
                                                <div class="d-flex">
                                                    <!-- Tombol Batalkan -->
                                                    <button type="button" class="btn btn-danger me-2" title="Batal"
                                                        data-toggle="modal" 
                                                        data-target="#ModalBatalBook" 
                                                        data-id="<?php echo $booking['id']; ?>">
                                                        Batalkan
                                                    </button>

                                                    <!-- Tombol Cetak Bukti -->
                                                    <form method="POST" action="print_pasien.php" target="_blank">
                                                        <input type="hidden" name="id" value="<?php echo $id_pasien; ?>">
                                                        <input type="hidden" name="id_book" value="<?php echo $booking['id']; ?>">
                                                        <button type="submit" class="btn btn-primary" name="printone" title="Print" data-toggle="tooltip">
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


                  <?php 
                  } else { ?>
                        <div class="wizard-steps">
                          <div class="wizard-step wizard-step-active">
                            <div class="wizard-step-icon">
                              <i class="fas fa-user-md"></i>
                            </div>
                            <div class="wizard-step-label">
                              Pilih Dokter
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['pilih_jadwal']) || isset($_POST['konfirmasi_jadwal']) || isset($_POST['keluhan']) || isset($_POST['print']) || isset($_POST['batal_booking'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="wizard-step-label">
                              Pilih Jadwal Pemeriksaan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['konfirmasi_jadwal']) || isset($_POST['keluhan']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">

                            <div class="wizard-step-icon">
                              <i class="fas fa-briefcase-medical"></i>
                            </div>
                            <div class="wizard-step-label">
                              Keluhan
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
                    <div class="wizard-pane">
                      <?php 
                      // STEP 1: PILIH DOKTER
                      if (isset($_POST['pilih_dokter']) || (isset($_GET['step']) && $_GET['step'] == 'pilih_dokter')) { 
                      ?>
                          <!-- TAMPILAN PILIH DOKTER -->
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table table-striped" id="table-1">
                                      <thead>
                                          <tr>
                                              <th>No</th>
                                              <th>Nama Dokter</th>
                                              <th>Spesialisasi</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php
                                          $query = "SELECT * FROM data_dokter ORDER BY id";
                                          $result = mysqli_query($conn, $query);
                                          $i = 0;
                                          while ($row = mysqli_fetch_array($result)) {
                                              $i++;
                                          ?>
                                              <tr>
                                                  <td><?php echo $i; ?></td>
                                                  <td><?php echo ucwords($row['nama_dokter']); ?></td>
                                                  <td><?php echo ucwords($row['spesialisasi']); ?></td>
                                                  <td>
                                                      <!-- FORM TERPISAH UNTUK PILIH JADWAL -->
                                                      <form method="POST">
                                                          <input type="hidden" name="id_dokter" value="<?php echo $row['id']; ?>">
                                                          <button class="btn btn-primary" type="submit" name="pilih_jadwal">Pilih Jadwal</button>
                                                      </form>
                                                  </td>
                                              </tr>
                                          <?php } ?>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      <?php 
                      } 

                      // STEP 2: PILIH JADWAL
                      if (isset($_POST['pilih_jadwal'])) { 
                          $id_dokter = $_POST['id_dokter'];
                          $query_jadwal = "SELECT jadwal.id AS id_jadwal, jadwal.hari, jadwal.jam_mulai, jadwal.jam_selesai, jadwal_dokter.status
                          FROM jadwal_dokter 
                          JOIN jadwal ON jadwal_dokter.id_jadwal = jadwal.id 
                          WHERE jadwal_dokter.id_dokter = '$id_dokter'";
         
                          $result_jadwal = mysqli_query($conn, $query_jadwal);
                          $days = ["Minggu" => 0, "Senin" => 1, "Selasa" => 2, "Rabu" => 3, "Kamis" => 4, "Jumat" => 5, "Sabtu" => 6];
                          $today = date('w');
                      ?>
                          <!-- TAMPILAN PILIH JADWAL -->
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table table-striped" id="table-2">
                                      <thead>
                                          <tr>
                                              <th>Hari</th>
                                              <th>Tanggal</th>
                                              <th>Jam Mulai</th>
                                              <th>Jam Selesai</th>
                                              <th>Aksi</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php while ($jadwal = mysqli_fetch_array($result_jadwal)) { 
                                            $hari_jadwal = $jadwal['hari'];
                                            $hari_index = $days[$hari_jadwal];
                                            $selisih_hari = ($hari_index >= $today) ? ($hari_index - $today) : (7 - ($today - $hari_index));
                                            $tanggal_jadwal = date('Y-m-d', strtotime("+$selisih_hari days"));
                                            $tanggal_format = date('d F Y', strtotime($tanggal_jadwal));
                                            $status = $jadwal['status']; // Ambil status jadwal dokter
                                        ?>
                                            <tr>
                                                <td><?php echo $hari_jadwal; ?></td>
                                                <td><?php echo $tanggal_format; ?></td>
                                                <td><?php echo $jadwal['jam_mulai']; ?></td>
                                                <td><?php echo $jadwal['jam_selesai']; ?></td>
                                                <td>
                                                    <form method="POST">
                                                        <input type="hidden" name="id_dokter" value="<?php echo $id_dokter; ?>">
                                                        <input type="hidden" name="id_jadwal" value="<?php echo $jadwal['id_jadwal']; ?>">
                                                        <input type="hidden" name="tanggal_jadwal" value="<?php echo $tanggal_jadwal; ?>">

                                                        <button class="btn btn-primary" type="submit" name="konfirmasi_jadwal" 
                                                            <?php echo ($status == 1) ? 'disabled' : ''; ?>>
                                                            <?php echo ($status == 1) ? 'Sudah Dipesan' : 'Konfirmasi Jadwal'; ?>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                  </table>
                              </div>
                          </div>

                      <?php 
                      } 

                      // STEP 3: INPUT KELUHAN
                      if (isset($_POST['konfirmasi_jadwal'])) { 
                      ?>
                      <!-- TAMPILAN INPUT KELUHAN -->
                      <div class="keluhan-section">
                          <form id="bookingForm" method="POST">
                              <div class="form-group row mb-4">
                                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keluhan</label>
                                  <div class="col-sm-12 col-md-7">
                                      <textarea class="summernote" id="keluhan" name="keluhan" placeholder="Wajib Diisi!" required></textarea>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <div class="col-md-6"></div>
                                  <div class="col-lg-4 col-md-6 text-right">
                                      <!-- Tombol ini memicu modal konfirmasi -->
                                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalKonfirmasi">Book</button>
                                  </div>
                              </div>
                          </form>
                      </div>

                      <?php } if (isset($_POST['print'])) { ?>
                      <div class="wizard-pane text-center">
                        <form method="POST" action="print_pasien.php" target="_blank">
                          <input type="hidden" name="id" value="<?php echo $id_pasien; ?>">
                          <input type="hidden" name="id_book" value="<?php echo $id_book; ?>">
                          <div class="btn-group">
                            <a href="data_booking_pasien.php" class="btn btn-info" title="Ke Menu Utama" data-toggle="tooltip">Ke Menu Utama</a>
                            <button type="submit" class="btn btn-primary" name="printone" title="Print" data-toggle="tooltip"><i class="fas fa-print"></i> Cetak Formulir</button>
                          </div>
                        </form>
                      </div>
                    <?php } ?>

                  </div>
                  <?php } ?>
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
    $('#ModalBatalBook').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang ditekan
        var idBooking = button.data('id'); // Ambil data-id dari tombol
        $('#modal_booking_id').val(idBooking); // Masukkan ke input hidden dalam modal
    });
    // Ambil data keluhan dari textarea booking_pasien.php dan kirimkan ke proses_booking.php
    document.querySelector('[data-target="#ModalKonfirmasi"]').addEventListener('click', function () {
        document.getElementById('confirmedKeluhan').value = document.getElementById('keluhan').value;
    });
  </script>
</body>

</html>

