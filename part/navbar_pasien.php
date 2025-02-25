<?php

$sessionid = $_SESSION['id_pasien'];

if(!isset($sessionid)){
  header('location:auth');
}
$nama = mysqli_query($conn, "SELECT * FROM pasien WHERE id=$sessionid");
$output = mysqli_fetch_array($nama);
?>
<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
  </form>
  <ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      <img alt="image" src="assets\img\avatar\avatar-2.png" class="rounded-circle mr-1">
      <div class="d-sm-none d-lg-inline-block">Hi, <?php echo ucwords($output['nama_pasien']); ?></div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-title"><i class="fas fa-circle text-success"></i>
          Pasien
        </div>
        <div class="dropdown-divider"></div>
        <a href="#" data-target="#ModalLogout" data-toggle="modal" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>

<div class="modal fade" tabindex="-1" role="dialog" id="ModalLogout">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda Yakin Ingin Logout?</p>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="window.location.href = 'auth/logout.php';" class="btn btn-danger">Ya</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="ModalKonfirmasi">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin melakukan booking dengan keluhan ini?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="confirmBookingForm" method="POST" action="data_booking_pasien.php">
                    <input type="hidden" id="confirmedKeluhan" name="keluhan">
                    <input type="hidden" name="print" value="1"> <!-- Pastikan POST print dikirim -->
                    <button type="submit" class="btn btn-primary">Ya, Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI BOOKING -->
<div class="modal fade" tabindex="-1" role="dialog" id="ModalBatalBook">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan booking?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <form id="confirmBatalBookForm" method="POST" action="data_booking_pasien.php">
                    <input type="hidden" name="id_booking" id="modal_booking_id" value="">
                    <input type="hidden" name="batal_booking" value="1">
                    <button type="submit" class="btn btn-danger">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>

