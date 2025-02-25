<?php
$idnama = $_POST['id'];
$page1 = "det";
include 'auth/connect.php';
//All SQL Syntax
$cek = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$idnama'");
$pasien = mysqli_fetch_array($cek);
$page = "Cetak Bukti : " . $pasien['nama_pasien'];
$idid = $pasien['id'];
session_start();

include "part/head.php";
include 'part_func/umur.php';
include 'part_func/tgl_ind.php';

if (isset($_POST['printone'])) {
  $booking = mysqli_query($conn, "SELECT * FROM booking WHERE id_pasien='$idid' ORDER BY id DESC LIMIT 1");
  
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
                    <td> : <?php echo $pasien['nama_pasien']; ?></td>
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
            <h4>Tabel Booking</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered" id="table-1">
                <thead>
                  <tr>
                    <th>Tanggal Booking</th>
                    <th>Dokter yang dipilih</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Keluhan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  while ($row = mysqli_fetch_array($booking)) {
                    $id_booking = $row['id'];
                    $id_dokter = $row['dokter_pilih'];
                    $tanggal_jadwal = $row['tanggal'];
                    $keluhan = $row['keluhan'];
                    $id_jadwal = $row['id_jadwal'];

                    $tanggal_format = date('d F Y', strtotime($tanggal_jadwal)); // Format: 13 Februari 2025
                    $result = mysqli_query($conn, "SELECT nama_dokter FROM data_dokter WHERE id='$id_dokter'");
                    $row = mysqli_fetch_assoc($result); // Ambil hasil query
                    $nama_dokter = $row['nama_dokter']; // Simpan sebagai string
                    
                    $result_jadwal = mysqli_query($conn, "SELECT hari as h, jam_mulai as jm, jam_selesai as js FROM jadwal WHERE id='$id_jadwal'");
                    $row_jadwal = mysqli_fetch_assoc($result_jadwal); // ✅ Gunakan $result_jadwal
                    
                    $hari = $row_jadwal['h']; // ✅ Mengambil dari $row_jadwal
                    $jam_mulai = $row_jadwal['jm']; // ✅ Mengambil dari $row_jadwal
                    $jam_selesai = $row_jadwal['js']; // ✅ Mengambil dari $row_jadwal
                    

                  ?>
                    <tr>
                      <td><?php echo $tanggal_format; ?></td>
                      <td><?php echo ucwords($nama_dokter); ?></td>
                      <td><?php echo ucwords($hari); ?></td>
                      <td><?php echo ucwords($jam_mulai) . " - " . ucwords($jam_selesai); ?></td>

                      <td><?php echo ucwords($keluhan); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
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