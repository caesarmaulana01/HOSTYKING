<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Data Pasien";
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
  if (isset($_POST['submit2'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $alam = $_POST['alamat'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $nik = $_POST['nik'];
    $t_badan = $_POST['tinggi_badan'];
    $b_badan = $_POST['berat_badan'];

    $cekuser = mysqli_query($conn, "SELECT * FROM pasien WHERE username='$username'");
    $baris = mysqli_num_rows($cekuser);
    if ($baris >= 1) {
      echo '<script>
        setTimeout(function() {
          swal({
            title: "Username sudah digunakan",
            text: "Username sudah digunakan, gunakan username lain!",
            icon: "error"
            });
          }, 500);
      </script>';
    } else {
      $add = mysqli_query($conn, "INSERT INTO pasien (`mail`, `nama_pasien`, `tgl_lahir`, `nik`, `tinggi_badan`, `berat_badan`, `alamat`, `username`, `password`) VALUES ('$email', '$nama', '$tgl_lahir', '$nik', '$t_badan', '$b_badan', '$alam', '$username', '$pass')");
      echo '<script>
        setTimeout(function() {
          swal({
            title: "Berhasil!",
            text: "Pasien telah ditambahkan!",
            icon: "success"
            });
          }, 500);
      </script>';
    }
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
                    <h4>Pasien yang telah terdaftar</h4>
                    <div class="card-header-action">
											<a href="#" class="btn btn-primary" data-target="#addPasien" data-toggle="modal">Tambah Pasien</a>
										</div>
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
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sql = mysqli_query($conn, "SELECT * FROM pasien");
                          $i = 0;
                          while ($row = mysqli_fetch_array($sql)) {
                            $idpasien = $row['id'];
                            $i++;
                          ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <th><?php echo ucwords($row['nama_pasien']); ?>
                                <div class="table-links">
                                  <?php
                                  $rekam = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id_pasien='$idpasien'");
                                  $cekrekam = mysqli_num_rows($rekam);
                                  if ($cekrekam == 0) {
                                    echo '<a>Pasien belum memiliki catatan medis</a>';
                                  } else { ?>
                                    <form method="POST" action="detail_pasien_admin.php">
                                      <input type="hidden" name="id" value="<?php echo $row['nama_pasien']; ?>">
                                      <button type="submit" id="btn-link">Pasien memiliki <?php echo $cekrekam; ?> catatan medis</button>
                                    </form>
                                  <?php }
                                  ?>
                                </div>
                              </th>
                              <td><?php if ($row['tgl_lahir'] == "0" OR $row['tgl_lahir'] == "") {
                                    echo "Data belum di input";
                                  } else {
                                    echo tgl_indo($row['tgl_lahir']);
                                  } ?></td>
                              <td><?php if ($row['tgl_lahir'] == "0" OR $row['tgl_lahir'] == "") {
                                    echo "Data belum di input";
                                  } else {
                                    umur($row['tgl_lahir']);
                                  } ?></td>
                              <td align="center">
                                <form method="POST" action="detail_pasien_admin.php">
                                  <span data-target="#editPasien" data-toggle="modal" data-id="<?php echo $idpasien; ?>" data-nama="<?php echo $row['nama_pasien']; ?>" data-lahir="<?php echo $row['tgl_lahir']; ?>" data-tinggi="<?php echo $row['tinggi_badan']; ?>" data-berat="<?php echo $row['berat_badan']; ?>">
                                    <a class="btn btn-primary btn-action mr-1" title="Edit Data Pasien" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>
                                  </span>
                                  <a class="btn btn-danger btn-action mr-1" data-toggle="tooltip" title="Hapus" data-confirm="Hapus Data|Apakah anda ingin menghapus data ini?" data-confirm-yes="window.location.href = 'auth/delete_admin.php?type=pasien&id=<?php echo $row['id']; ?>'" ;><i class="fas fa-trash"></i></a>
                                  <input type="hidden" name="id" value="<?php echo $row['nama_pasien']; ?>">
                                  <button type="submit" class="btn btn-info btn-action mr-1" title="Detail Pasien" data-toggle="tooltip" name="submit"><i class="fas fa-info-circle"></i></button>
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

      <div class="modal fade" tabindex="-1" role="dialog" id="addPasien">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Pasien</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="" method="POST" class="needs-validation" novalidate="">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama" required="">
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">NIK</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="nik" required="">
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Username</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="username" required="">
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Email</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" name="email" required="">
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" name="tgl_lahir" required="">
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Tinggi Badan</label>
                  <div class="col-sm-9 input-group">
                    <input type="number" class="form-control" name="tinggi_badan" required="">
                    <div class="input-group-prepend">
                      <div class="input-group-text">cm</div>
                    </div>
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Berat Badan</label>
                  <div class="col-sm-9 input-group">
                    <input type="number" class="form-control" name="berat_badan" required="">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Kg</div>
                    </div>
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" required="" name="alamat"></textarea>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Password</label>
                  <div class="col-sm-9">
                    <input type="password" name="password" class="form-control" required="">
                    <div class="invalid-feedback">Mohon data diisi!</div>
                  </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit2">Tambah</button>
              </form>
            </div>
          </div>
        </div>
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