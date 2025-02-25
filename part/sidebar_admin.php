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
      <a href="http://localhost/App-HOSTYKING/dash_admin.php"><?php echo $judul; ?></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html"><?php echo $acronym; ?></a>
    </div>
    <ul class="sidebar-menu">
      <li <?php echo ($page == "Dashboard Admin") ? "class=active" : ""; ?>><a class="nav-link" href="dash_admin.php"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
      <li class="menu-header">Menu</li>

      <li <?php echo ($page == "Data Pasien" || @$page1 == "det") ? "class=active" : ""; ?>><a class="nav-link" href="pasien_admin.php"><i class="fas fa-user-injured"></i> <span>Data Pasien</span></a></li>
      <li <?php echo ($page == "Data Booking") ? "class=active" : ""; ?>><a class="nav-link" href="data_booking_admin.php"><i class="fas fa-calendar-check"></i> <span>Data Booking</span></a></li>
      <li <?php echo ($page == "Data Dokter" || @$page1 == "detjad") ? "class=active" : ""; ?>><a class="nav-link" href="data_dokter_admin.php"><i class="fas fa-stethoscope"></i> <span>Data Dokter</span></a></li>
      <li <?php echo ($page == "Hasil Pemeriksaan" || @$page1 == "Detail Tindakan ") ? "class=active" : ""; ?>><a class="nav-link" href="hasil_dokter_admin.php?step=pasien_tindakan"><i class="fas fa-notes-medical"></i> <span>Hasil Pemeriksaan</span></a></li>

      <li <?php echo ($page == "Data Pegawai") ? "class=active" : ""; ?>><a href="data_admin_admin.php" class="nav-link"><i class="fas fa-users"></i> <span>Data Pegawai</span></a></li>
      <li class="dropdown <?php echo ($page1 == "ruang" || $page1 == "riwayatinap") ? "active" : ""; ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-bed"></i> <span>Rawat Inap</span></a>
        <ul class="dropdown-menu">
          <li <?php echo (@$page1 == "ruang") ? "class=active" : ""; ?>><a class="nav-link" href="ruang_inap_admin.php">Detail Ruangan</a></li>
          <li <?php echo (@$page1 == "riwayatinap") ? "class=active" : ""; ?>><a class="nav-link" href="riwayat_inap_admin.php">Riwayat Rawat Inap</a></li>
        </ul>
      </li>
      <li <?php echo ($page == "Data Foto Rontgen" || @$page1 == "detron") ? "class=active" : ""; ?>><a class="nav-link" href="rontgen_admin.php"><i class="fas fa-skull"></i> <span>Foto Rontgen</span></a></li>
      <li <?php echo ($page == "Data Foto USG" || @$page1 == "detusg") ? "class=active" : ""; ?>><a class="nav-link" href="usg_admin.php"><i class="fas fa-heartbeat"></i> <span>Foto USG</span></a></li>
      <li <?php echo ($page == "Data Obat") ? "class=active" : ""; ?>><a class="nav-link" href="obat_admin.php"><i class="fas fa-briefcase-medical"></i> <span>Obat</span></a></li>
      <li>
        <a class="nav-link" href="#" data-target="#ModalLogout" data-toggle="modal">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </li>
  </aside>
</div>