<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	$page = "Data Dokter";
	session_start();
	include 'auth/connect.php';
	include "part/head.php";

	if (isset($_POST['submitEdit'])) {
		$id = $_POST['id_dokter'];
		$nama = $_POST['nama_dokter'];
		$user = $_POST['username'];
		$alamat = $_POST['alamat'];
		$spesialisasi = $_POST['spesialisasi'];
		$old_pass = $_POST['old_password'];
		$new_pass = $_POST['new_password'];
		$jadwal = isset($_POST['jadwal']) ? $_POST['jadwal'] : []; // Jadwal sebagai array
	
		// **Update Data Dokter tanpa mengganti password**
		if ($old_pass == "" && $new_pass == "") {
			$up1 = mysqli_query($conn, "UPDATE data_dokter SET 
										nama_dokter='$nama', 
										username='$user', 
										alamat='$alamat', 
										spesialisasi='$spesialisasi' 
										WHERE id='$id'");
			echo '<script>
				setTimeout(function() {
					swal({
						title: "Data Diubah",
						text: "Data berhasil diubah!",
						icon: "success"
					});
				}, 500);
			</script>';
		} 
		// **Update Data Dokter & Password**
		elseif ($old_pass != "" && $new_pass != "") {
			$cekpass = mysqli_query($conn, "SELECT password FROM data_dokter WHERE id='$id'");
			$data = mysqli_fetch_assoc($cekpass);
	
			if ($cekpass && $old_pass !== $data['password']) {
				echo '<script>
					setTimeout(function() {
						swal({
							title: "Password salah",
							text: "Password lama salah, coba lagi!",
							icon: "error"
						});
					}, 500);
				</script>';
			} else {
				$up2 = mysqli_query($conn, "UPDATE data_dokter SET 
											nama_dokter='$nama', 
											username='$user', 
											password='$new_pass', 
											alamat='$alamat', 
											spesialisasi='$spesialisasi' 
											WHERE id='$id'");
				echo '<script>
					setTimeout(function() {
						swal({
							title: "Data Diubah",
							text: "Data atau Password berhasil diubah!",
							icon: "success"
						});
					}, 500);
				</script>';
			}
		}
	
		// **Update Jadwal Dokter hanya jika ada perubahan**
		if (!empty($jadwal)) {
			// Hapus jadwal lama
			mysqli_query($conn, "DELETE FROM jadwal_dokter WHERE id_dokter='$id'");
	
			// Masukkan jadwal baru
			foreach ($jadwal as $id_jadwal) {
				mysqli_query($conn, "INSERT INTO jadwal_dokter (id_dokter, id_jadwal) VALUES ('$id', '$id_jadwal')");
			}
		}
	}
	
	
	

	if (isset($_POST['submit2'])) {
		$nama = $_POST['nama_dokter'];
		$user = $_POST['username'];
		$alam = $_POST['alamat'];
	    $spesialisasi = $_POST['spesialisasi'];	
		$pass = $_POST['password'];
		$jadwal_terpilih = $_POST['jadwal'];

		$cekuser = mysqli_query($conn, "SELECT * FROM data_dokter WHERE username='$user'");
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
			$add = mysqli_query($conn, "INSERT INTO data_dokter (username, password, nama_dokter, alamat, spesialisasi) VALUES ('$user', '$pass', '$nama', '$alam', '$spesialisasi')");
			if ($add) {
				// Dapatkan ID dokter yang baru ditambahkan
				$id_dokter = mysqli_insert_id($conn);
		
				// Loop untuk memasukkan setiap jadwal yang dipilih ke tabel jadwal_dokter
				foreach ($jadwal_terpilih as $id_jadwal) {
					mysqli_query($conn, "INSERT INTO jadwal_dokter (id_dokter, id_jadwal, status) 
										 VALUES ('$id_dokter', '$id_jadwal', 0)");
				}
			echo '<script>
				setTimeout(function() {
					swal({
						title: "Berhasil!",
						text: "Dokter telah ditambahkan!",
						icon: "success"
						});
					}, 500);
			</script>';
		}
	}
}
	$query_jadwal = "SELECT * FROM jadwal ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')";
	$result_jadwal = mysqli_query($conn, $query_jadwal);
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
										<h4><?php echo $page; ?></h4>
										<div class="card-header-action">
											<a href="#" class="btn btn-primary" data-target="#addDoctor" data-toggle="modal">Tambah Dokter</a>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-striped" id="table-1">
												<thead>
													<tr>
														<th class="text-center">#</th>
														<th>Nama Dokter</th>
														<th>Spesialis</th>
														<th>Alamat</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$sql = mysqli_query($conn, "SELECT data_dokter.*, 
																					GROUP_CONCAT(
																						CASE WHEN jadwal_dokter.status = 0 
																						THEN CONCAT(jadwal.hari, ' (', jadwal.jam_mulai, ' - ', jadwal.jam_selesai, ')') 
																						END 
																						ORDER BY FIELD(jadwal.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') 
																						SEPARATOR ', ') AS jadwal_tersedia, 
																					COUNT(CASE WHEN jadwal_dokter.status = 0 THEN 1 END) AS total_tersedia
																				FROM data_dokter 
																				LEFT JOIN jadwal_dokter ON data_dokter.id = jadwal_dokter.id_dokter 
																				LEFT JOIN jadwal ON jadwal_dokter.id_jadwal = jadwal.id 
																				GROUP BY data_dokter.id");

													$i = 0;
													while ($row = mysqli_fetch_array($sql)) {
														$i++;
														$jadwal_tersedia = $row['jadwal_tersedia'];
														$total_tersedia = $row['total_tersedia'];

														echo "<tr>
																<td>{$i}</td>
																<td>" . ucwords($row['nama_dokter']) . "</td>
																<td>" . ucwords($row['spesialisasi']) . "</td>
																<td>" . ucwords($row['alamat']) . "</td>
																<td>";

														// Menampilkan status dokter
														if ($total_tersedia > 0) {
															$jadwal_arr = explode(", ", $jadwal_tersedia);
															$tampil_jadwal = array_slice($jadwal_arr, 0, 1);
															$sisa_jadwal = count($jadwal_arr) - 1;

															echo "<div class='badge badge-pill badge-success mb-1'>
																	<i class='ion-checkmark-round'></i> Tersedia: " . implode(", ", $tampil_jadwal) . 
																	($sisa_jadwal > 0 ? ", +$sisa_jadwal" : "") . "
																</div>";
														} else {
															echo "<div class='badge badge-pill badge-danger mb-1'>
																	<i class='ion-close'></i> Tidak Tersedia
																</div>";
														}

														echo "</td>
																<td>
																	<span data-target='#editUser' data-toggle='modal' 
																		data-id='{$row['id']}' 
																		data-nama='" . htmlspecialchars($row['nama_dokter']) . "' 
																		data-user='" . htmlspecialchars($row['username']) . "' 
																		data-spesialis='" . htmlspecialchars($row['spesialisasi']) . "' 
																		data-alam='" . htmlspecialchars($row['alamat']) . "'>
																		<a class='btn btn-primary btn-action mr-1' title='Edit' data-toggle='tooltip'>
																			<i class='fas fa-pencil-alt'></i>
																		</a>
																	</span>

																	<a href='detail_jadwal_admin.php?id={$row['id']}' class='btn btn-info btn-action' title='Detail Jadwal' data-toggle='tooltip'>
																		<i class='fas fa-calendar-alt'></i>
																	</a>

																	<a class='btn btn-danger btn-action' data-toggle='tooltip' title='Hapus' 
																	data-confirm='Hapus Data|Apakah anda ingin menghapus data ini?' 
																	data-confirm-yes='window.location.href = \"auth/delete_admin.php?type=data_dokter&id={$row['id']}\";'>
																		<i class='fas fa-trash'></i>
																	</a>
																</td>
															</tr>";
													}
													?>
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
			<!-- Modal Tambah Dokter -->
			<div class="modal fade" tabindex="-1" role="dialog" id="addDoctor">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Tambah Dokter</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="" method="POST" class="needs-validation" novalidate="">
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Nama Lengkap</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="nama_dokter" required="">
										<div class="invalid-feedback">
											Mohon data diisi!
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Username</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="username" required="">
										<div class="invalid-feedback">
											Mohon data diisi!
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Spesialisasi</label>
									<div class="col-sm-9">
										<select class="form-control" name="spesialisasi" required="" id="getSpesialis">
											<option value="">Pilih Spesialisasi</option>
											<option value="Jantung">Jantung</option>
											<option value="Mata">Mata</option>
											<option value="Paru-paru">Paru-paru</option>
											<option value="Kulit">Kulit</option>
											<option value="Kandungan">Kandungan</option>
										</select>
										<div class="invalid-feedback">
											Mohon pilih spesialisasi dokter!
										</div>
									</div>
								</div>

								<!-- Jadwal Dokter -->
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Pilih Jadwal Dokter</label>
									<div class="col-sm-9" id="jadwalContainer">
										<?php while ($jadwal = mysqli_fetch_assoc($result_jadwal)) : ?>
											<div class="form-check">
												<input class="form-check-input jadwal-checkbox" type="checkbox" name="jadwal[]" value="<?= $jadwal['id']; ?>">
												<label class="form-check-label">
													<?= $jadwal['hari'] ?> (<?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?>)
												</label>
											</div>
										<?php endwhile; ?>
										<!-- Pesan error -->
										<div class="invalid-feedback d-block text-danger" id="jadwalError" style="display: none;">
											Pilih minimal 2 jadwal dokter!
										</div>
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
											<div class="invalid-feedback">
												Mohon isi password!
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

			<!-- Modal Edit Dokter -->
			<div class="modal fade" id="editUser" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit Dokter</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" class="needs-validation" novalidate="">
								<input type="hidden" name="id_dokter" id="edit_id_dokter">

								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Nama Lengkap</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="nama_dokter" id="edit_nama_dokter" required="">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Username</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="username" id="edit_username" required="">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Spesialisasi</label>
									<div class="col-sm-9">
										<select class="form-control" name="spesialisasi" id="edit_spesialisasi" required="">
											<option value="">Pilih Spesialisasi</option>
											<option value="Jantung">Jantung</option>
											<option value="Mata">Mata</option>
											<option value="Paru-paru">Paru-paru</option>
											<option value="Kulit">Kulit</option>
											<option value="Kandungan">Kandungan</option>
										</select>
									</div>
								</div>

								<!-- Pilihan Jadwal -->
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Pilih Jadwal Dokter</label>
									<div class="col-sm-9" id="edit_jadwalContainer">
										<!-- Checkbox jadwal akan di-generate dengan JavaScript -->
	
										<?php 
											$query_jadwal = "SELECT * FROM jadwal ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')";
											$result_jadwal = mysqli_query($conn, $query_jadwal);
										while ($jadwal = mysqli_fetch_assoc($result_jadwal)) : ?>
											<div class="form-check">
												<input class="form-check-input jadwal-checkbox" type="checkbox" name="jadwal[]" value="<?= $jadwal['id']; ?>">
												<label class="form-check-label">
													<?= $jadwal['hari'] ?> (<?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?>)
												</label>
											</div>
										<?php endwhile; ?>
										<!-- Pesan error -->
										<div class="invalid-feedback d-block text-danger" id="jadwalError" style="display: none;">
											Pilih minimal 2 jadwal dokter!
										</div>
									</div>
			
								</div>

								<div class="form-group">
									<label>Alamat</label>
									<textarea class="form-control" required="" name="alamat" id="edit_alamat"></textarea>
								</div>
								<div class="alert alert-light text-center">
									Jika password tidak diganti, form di bawah dikosongi saja.
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Password Lama</label>
									<div class="col-sm-9">
										<input type="password" name="old_password" id="edit_old_password" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Password Baru</label>
									<div class="col-sm-9">
										<input type="password" name="new_password" id="edit_new_password" class="form-control">
									</div>
								</div>


						</div>
						<div class="modal-footer bg-whitesmoke br">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" name="submitEdit">Simpan Perubahan</button>
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
		$(document).ready(function () {
			$('#editUser').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				var id = button.data('id');
				var nama = button.data('nama');
				var user = button.data('user');
				var spesialis = button.data('spesialis');
				var alamat = button.data('alam');
				var selectedJadwal = button.data('jadwal');

				var modal = $(this);
				modal.find('#edit_id_dokter').val(id);
				modal.find('#edit_nama_dokter').val(nama);
				modal.find('#edit_username').val(user);
				modal.find('#edit_spesialisasi').val(spesialis);
				modal.find('#edit_alamat').val(alamat);

				// Reset password setiap modal dibuka
				modal.find('#edit_old_password').val('');
				modal.find('#edit_new_password').val('');

				// Pilih spesialisasi yang sesuai
				modal.find('#edit_spesialisasi option').each(function () {
					$(this).prop('selected', $(this).val() === spesialis);
				});

				var jadwalArray = [];
				if (typeof selectedJadwal === "string" && selectedJadwal.length > 0) {
					try {
						jadwalArray = JSON.parse(selectedJadwal).map(Number);
					} catch (e) {
						console.error("Error parsing JSON: ", e);
					}
				}

				// Loop checkbox jadwal
				$('.jadwal-checkbox').each(function () {
					var checkbox = $(this);
					var value = parseInt(checkbox.val());

					checkbox.prop('checked', jadwalArray.includes(value));
				});
			});


			// **Script untuk Validasi Minimal 2 Checkbox**
			const form = $("#addDoctor form"); // Form dalam modal Tambah Dokter
			const checkboxes = $('#jadwalContainer .jadwal-checkbox');
			const errorMessage = $("#jadwalError");

			function validateCheckboxes() {
				let checkedCount = 0;
				checkboxes.each(function () {
					if ($(this).prop("checked")) {
						checkedCount++;
					}
				});

				if (checkedCount < 2) {
					errorMessage.show(); // Tampilkan pesan error
					return false; // Validasi gagal
				} else {
					errorMessage.hide(); // Sembunyikan error
					return true; // Validasi sukses
				}
			}

			// Cek setiap kali ada perubahan di checkbox
			checkboxes.on("change", validateCheckboxes);

			// Cek sebelum submit form
			form.on("submit", function (event) {
				if (!validateCheckboxes()) {
					event.preventDefault(); // Mencegah submit jika tidak valid
				}
			});
		});






		$(document).ready(function () {

		});
			

		
	</script>
</body>

</html>