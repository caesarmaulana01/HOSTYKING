  <script src="../assets/modules/sweetalert/sweet2.js"></script>
  <link rel="stylesheet" href="../assets/modules/sweetalert/sweet2.css">

  <?php
    include 'connect.php';

    $tipe = $_GET['type'];
    $id = $_GET['id'];

    $sql = mysqli_query($conn, "DELETE FROM $tipe WHERE id='$id'");
    ?>
  <script>
      setTimeout(function() {
          swal({
              title: "Sukses",
              text: "Hapus data berhasil!",
              type: "success"
          }, function() {
              <?php
                // if ($tipe == "ruang_inap") {
                //     echo 'window.location.href="../ruang_inap_admin.php";';
                // } else {
                //     echo 'window.location.href="../'.$tipe.'_admin.php";';
                // }
                echo 'window.location.href="../'.$tipe.'_admin.php";';
                ?>
          });
      }, 500);
  </script>