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
      <a href="http://localhost/App-HOSTYKING/dash_dokter.php"><?php echo $judul; ?></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html"><?php echo $acronym; ?></a>
    </div>
    <ul class="sidebar-menu">
    <li <?php echo ($page == "Dashboard Dokter") ? "class=active" : ""; ?>><a class="nav-link" href="dash_dokter.php"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
    <li class="menu-header">Menu</li>

    <li <?php echo ($page == "Pasien Booking" || @$page1 == "det") ? "class=active" : ""; ?>>
        <a class="nav-link" href="data_booking_dokter.php">
            <i class="fas fa-user-injured"></i> <span>Pasien Booking</span>
        </a>
    </li>

    <li <?php echo ($page == "Pasien Ditangani") ? "class=active" : ""; ?>>
        <a class="nav-link" href="pasien_ditangani.php">
            <i class="fas fa-history"></i> <span>Pasien Ditangani</span>
        </a>
    </li>

    <li <?php echo ($page == "Jadwal Praktek") ? "class=active" : ""; ?>>
        <a class="nav-link" href="jadwal_praktek_dokter.php">
            <i class="fas fa-calendar-alt"></i> <span>Jadwal Praktek</span>
        </a>
    </li>

    <li <?php echo ($page == "Hasil Pemeriksaan") ? "class=active" : ""; ?>>
        <a class="nav-link" href="hasil_pemeriksaan_dokter.php?step=pilih_pasien">
            <i class="fas fa-notes-medical"></i> <span>Hasil Pemeriksaan</span>
        </a>
    </li>

    <li>
        <a class="nav-link" href="#" data-target="#ModalLogout" data-toggle="modal">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </li>

  </aside>
</div>