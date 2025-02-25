<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Jadwal Praktek";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";

  // Pastikan dokter sudah login dan id_dokter tersedia di session
  if (!isset($_SESSION['id_dokter'])) {
      header("Location: login.php");
      exit();
  }

  $id_dokter = $_SESSION['id_dokter'];
  ?>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <?php
      include 'part/navbar_dokter.php';
      include 'part/sidebar_dokter.php';
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
                    <h4>Jadwal Praktek Saya</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status Ketersediaan</th> <!-- Kolom Baru -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sql = mysqli_query($conn, "SELECT j.hari, j.jam_mulai, j.jam_selesai, jd.status
                              FROM jadwal_dokter jd
                              JOIN jadwal j ON jd.id_jadwal = j.id
                              WHERE jd.id_dokter = '$id_dokter'");

                          $i = 0;
                          while ($row = mysqli_fetch_array($sql)) {
                              $i++;
                          ?>
                            <tr>
                              <td class="text-center"><?php echo $i; ?></td>
                              <td><?php echo $row['hari']; ?></td>
                              <td><?php echo $row['jam_mulai']; ?></td>
                              <td><?php echo $row['jam_selesai']; ?></td>
                              <td>
                                <?php
                                if ($row['status'] == 0) {
                                    echo '<div class="badge badge-pill badge-success"><i class="ion-checkmark-round"></i> Tersedia</div>';
                                } else {
                                    echo '<div class="badge badge-pill badge-danger"><i class="ion-close"></i> Tidak Tersedia</div>';
                                }
                                ?>
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
      <?php include 'part/footer.php'; ?>
    </div>
  </div>
  <?php include "part/all-js.php"; ?>
</body>

</html>
