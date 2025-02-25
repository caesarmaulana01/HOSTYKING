<!DOCTYPE html>
<html lang="en">

<head>
	<?php

	$page1 = "Detail Tindakan";
	$page == "Hasil Pemeriksaan";
	session_start();
	include 'auth/connect.php';
	include "part/head.php";
	include "part_func/tgl_ind.php";
    if (isset($_POST['update_status'])) {
        $id = $_POST['id'];
        mysqli_query($conn, "UPDATE riwayat_tindakan SET status='selesai' WHERE id='$id'");
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "Status Diperbarui",
                        text: "Tindakan telah diselesaikan!",
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
						<h1>Hasil Pemeriksaan Dokter</h1>
					</div>
					<div class="section-body">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4><?php echo $page1; ?></h4>
									</div>
									<div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>Nama Pasien</th>
                                                <th>Nama Dokter</th>
                                                <th>Penyakit</th>
                                                <th>Detail</th>
                                                <th>Harga</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = mysqli_query($conn, "SELECT rt.id, p.nama_pasien, d.nama_dokter, rp.penyakit, rt.detail, rt.harga, rt.status FROM riwayat_tindakan rt JOIN pasien p ON rt.id_pasien = p.id JOIN data_dokter d ON rt.id_dokter = d.id JOIN riwayat_penyakit rp ON rt.id_penyakit = rp.id");
                                            while ($row = mysqli_fetch_array($sql)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ucwords($row['nama_pasien']); ?></td>
                                                    <td><?php echo ucwords($row['nama_dokter']); ?></td>
                                                    <td><?php echo ucwords($row['penyakit']); ?></td>
                                                    <td><?php echo $row['detail']; ?></td>
                                                    <td>Rp. <?php echo number_format($row['harga'], 0, '.', '.'); ?></td>
                                                    <td>
                                                        <?php if ($row['status'] == 'pending') {
                                                            echo '<div class="badge badge-warning">Pending</div>';
                                                        } else {
                                                            echo '<div class="badge badge-success">Selesai</div>';
                                                        } ?>
                                                    </td>
                                                    <td align="center">
                                                        <?php if ($row['status'] == 'pending') { ?>
                                                            <form method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                <button type="submit" class="btn btn-primary btn-action mr-1" title="Tandai Selesai" data-toggle="tooltip" name="update_status">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
																
                                                            </form>
                                                        <?php } ?>
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

		<div class="modal fade" tabindex="-1" role="dialog" id="addUser">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Ruangan</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" method="POST" class="needs-validation" novalidate="">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Nama Ruangan</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="nama" required="">
									<div class="invalid-feedback">
										Mohon data diisi!
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Harga</label>
								<div class="input-group col-sm-9">
									<div class="input-group-prepend">
										<div class="input-group-text">
											Rp
										</div>
									</div>
									<input type="number" class="form-control currency" name="harga" required="">
									<div class="invalid-feedback">
										Mohon data diisi!
									</div>
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

		<div class="modal fade" tabindex="-1" role="dialog" id="editRuang">
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
								<label class="col-sm-3 col-form-label">Nama Ruangan</label>
								<div class="col-sm-9">
									<input type="hidden" class="form-control" name="id" required="" id="getId">
									<input type="text" class="form-control" name="nama" required="" id="getNama">
									<div class="invalid-feedback">
										Mohon data diisi!
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Harga</label>
								<div class="input-group col-sm-9">
									<div class="input-group-prepend">
										<div class="input-group-text">
											Rp
										</div>
									</div>
									<input type="number" class="form-control currency" name="harga" id="getHarga" required="">
								</div>
							</div>
							<div class="form-group">
								<label>Status Ruangan</label>
								<select class="form-control selectric" name="status">
									<option value="1">Terserdia</option>
									<option value="2">Dalam Perbaikan</option>
								</select>
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


</body>

</html>