<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Data Booking";
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
      include 'part/navbar_admin.php';
      include 'part/sidebar_admin.php';
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
                                  <th class="text-center">Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                              $sql = mysqli_query($conn, "SELECT p.id, p.nama_pasien, p.tgl_lahir, b.tanggal, j.hari, j.jam_mulai, j.jam_selesai 
                                  FROM pasien p
                                  JOIN jadwal_dokter jd ON p.id = jd.id_pasien
                                  JOIN booking b ON p.id = b.id_pasien
                                  JOIN jadwal j ON jd.id_jadwal = j.id
                                  WHERE jd.status = 1");
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
                                      <td align="center">
                                          <form method="POST" action="detail_pasien_admin.php">
                                              <input type="hidden" name="id" value="<?php echo $row['nama_pasien']; ?>">
                                              <button type="submit" class="btn btn-info btn-action mr-1" title="Detail Pasien" data-toggle="tooltip" name="submit">
                                                  <i class="fas fa-info-circle"></i>
                                              </button>
                                          </form>
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

      <div class="modal fade" tabindex="-1" role="dialog" id="editPasien">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="" method="POST" class="needs-validation" novalidate="">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Nama Pasien</label>
                  <div class="col-sm-9">
                    <input type="hidden" class="form-control" name="id" required="" id="getId">
                    <input type="text" class="form-control" name="nama" required="" id="getNama">
                    <div class="invalid-feedback">
                      Mohon data diisi!
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Tanggal lahir</label>
                  <div class="form-group col-sm-9">
                    <input type="text" class="form-control datepicker" id="getTgl" name="tgl">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Berat Badan</label>
                  <div class="input-group col-sm-9">
                    <input type="number" class="form-control" name="berat" required="" id="getBerat">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        Kg
                      </div>
                    </div>
                    <div class="invalid-feedback">
                      Mohon data diisi!
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Tinggi Badan</label>
                  <div class="col-sm-9 input-group">
                    <input type="number" class="form-control" name="tinggi" required="" id="getTinggi">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        cm
                      </div>
                    </div>
                    <div class="invalid-feedback">
                      Mohon data diisi!
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit">Edit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php include 'part/footer.php'; ?>
    </div>
  </div>
  <?php include "part/all-js.php"; ?>

  <script>
    $('#editPasien').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget)
      var nama = button.data('nama')
      var id = button.data('id')
      var tgl = button.data('lahir')
      var berat = button.data('berat')
      var tinggi = button.data('tinggi')
      var modal = $(this)
      modal.find('#getId').val(id)
      modal.find('#getNama').val(nama)
      modal.find('#getTgl').val(tgl)
      modal.find('#getBerat').val(berat)
      modal.find('#getTinggi').val(tinggi)
    })
  </script>
</body>

</html>