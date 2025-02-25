<?php
$judul = "HOSTYKING";
$pecahjudul = explode(" ", $judul);
$acronym = "";

foreach ($pecahjudul as $w) {
  $acronym .= $w[0];
}
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <img src="assets/img/Logo.png" alt="logo" width="35" class="rounded-box">
      <a href="http://localhost/App-HOSTYKING/dash_pasien.php"><?php echo $judul; ?></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html"><?php echo $acronym; ?></a>
    </div>
    <ul class="sidebar-menu">
      <li <?php echo ($page == "Dashboard") ? "class=active" : ""; ?>><a class="nav-link" href="dash_pasien.php"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
      <li class="menu-header">Menu</li>

      <li <?php echo ($page == "Data Diri") ? "class=active" : ""; ?>>
        <a class="nav-link" href="data_diri.php">
            <i class="fas fa-user"></i> <span>Data Diri</span>
        </a>
      </li>
      <li <?php echo ($page == "Booking Fasilitas") ? 'class="active"' : ''; ?>>
          <a href="data_booking_pasien.php?page=Booking Fasilitas" class="nav-link" id="bookingFasilitasLink">
              <i class="fas fa-calendar-check"></i> <span>Booking Fasilitas</span>
          </a>
          <form id="bookingForm" method="POST" action="data_booking_pasien.php" style="display: none;">
              <input type="hidden" name="pilih_dokter" value="1">
          </form>
      </li>
      <li <?php echo ($page == "Riwayat Pemeriksaan") ? "class=active" : ""; ?>>
          <a class="nav-link" href="detail_riwayat_pasien.php">
              <i class="fas fa-notes-medical"></i> <span>Riwayat Pemeriksaan</span>
          </a>
      </li>
      <li <?php echo ($page == "Data Dokter") ? "class=active" : ""; ?>>
          <a href="data_dokter_pasien.php" class="nav-link">
              <i class="fas fa-user-md"></i> <span>Data Dokter</span>
          </a>
      </li>
      <li <?php echo ($page == "Foto Medis" || @$page1 == "detrot") ? "class=active" : ""; ?>>
          <a class="nav-link" href="foto_medis_pasien.php">
              <i class="fas fa-x-ray"></i> <span>Foto Medis</span>
          </a>
      </li>
      <li>
          <a class="nav-link" href="#" data-target="#ModalLogout" data-toggle="modal">
              <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
          </a>
      </li>
  </aside>
</div>


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

<script>
    document.getElementById('bookingFasilitasLink').addEventListener('click', function(e) {
        e.preventDefault(); // Hindari perpindahan halaman langsung
        document.getElementById('bookingForm').submit(); // Kirim form POST
    });
</script>