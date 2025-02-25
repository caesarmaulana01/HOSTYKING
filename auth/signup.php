<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="shortcut icon" type="image/x-icon" href="../assets/img/Logo.png" />
  <title>Sign Up</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/modules/fontawesome/css/all.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">

  <style>
    body {
      background-color: #f4f7f6;
    }
    .card-primary {
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
    }
    .login-brand img {
      width: 120px;
      display: block;
      margin: 0 auto;
      background: none;
    }
    .btn-primary {
      background-color: #5dade2;
      border-color: #5dade2;
    }
    .btn-primary:hover {
      background-color: #5499c7;
      border-color: #5499c7;
    }
  </style>

  <?php
  session_start();
  if(isset($_SESSION['id_pasien'])){
    header('location:../');
  }else{
    include 'connect.php';
    if(isset($_POST['submit'])){
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $namalengkap = mysqli_real_escape_string($conn, $_POST['namalengkap']);
      $username = mysqli_real_escape_string($conn, $_POST['username']);
      $password = mysqli_real_escape_string($conn, $_POST['password']);

      $cekuser = mysqli_query($conn, "SELECT * FROM pasien WHERE username='$username'");
      $cekemail = mysqli_query($conn, "SELECT * FROM pasien WHERE mail='$email'");

      if (mysqli_num_rows($cekuser) >= 1) {
        echo '<script>
          setTimeout(function() {
            swal({
              title: "Username sudah digunakan",
              text: "Gunakan username lain!",
              icon: "error"
            });
          }, 500);
        </script>';
      } elseif (mysqli_num_rows($cekemail) >= 1) {
        echo '<script>
          setTimeout(function() {
            swal({
              title: "Email sudah digunakan",
              text: "Gunakan email lain!",
              icon: "error"
            });
          }, 500);
        </script>';
      } else {
        mysqli_query($conn, "INSERT INTO pasien (mail, nama_pasien, tgl_lahir, nik, tinggi_badan, berat_badan, alamat, username, password) VALUES ('$email', '$namalengkap', '0', '0', '0', '0', '-', '$username', '$password')");
        echo '<script>
          setTimeout(function() {
            swal({
              title: "Berhasil!",
              text: "Akun berhasil dibuat!",
              icon: "success"
            });
          }, 500);
        </script>';
      }
    }
  }
  ?>
</head>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row justify-content-center">
          <div class="col-12 col-md-6 col-lg-5">
            <div class="login-brand">
              <img src="../assets/img/Logo.png" alt="logo">
            </div>
            <div class="card card-primary">
              <div class="card-header"><h4>Sign Up</h4></div>
              <div class="card-body">
                <form method="POST" action="" class="needs-validation" novalidate autocomplete="off">
                  <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="Masukkan alamat email" required>
                    <div class="invalid-feedback">Mohon isi alamat email anda yang valid!</div>
                  </div>

                  <div class="form-group">
                    <label for="namalengkap">Nama Lengkap</label>
                    <input id="namalengkap" type="text" class="form-control" name="namalengkap" placeholder="Masukkan nama lengkap" required>
                    <div class="invalid-feedback">Mohon isi nama lengkap anda!</div>
                  </div>

                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" placeholder="Username" required>
                    <div class="invalid-feedback">Mohon isi username anda!</div>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="invalid-feedback">Mohon isi password anda!</div>
                  </div>

                  <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Daftar</button>
                  </div>
                </form>
              </div>
              <div class="card-body text-center">
                <h6>Sudah memiliki akun?<a href="login.php"> Masuk Disini!</a></h6>
              </div>
            </div>
            <div class="simple-footer text-center">
              Copyright &copy; HOSTYKING 2025
              <p>All Rights Reserved</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="../assets/modules/jquery.min.js"></script>
  <script src="../assets/modules/popper.js"></script>
  <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/js/stisla.js"></script>
  <script src="../assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="../assets/js/page/modules-sweetalert.js"></script>
</body>
</html>
