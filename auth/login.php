<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="shortcut icon" type="image/x-icon" href="../assets/img/Logo.png" />
  <title>Login</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/modules/fontawesome/css/all.min.css">

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
  if(isset($_SESSION['id_dokter'])){
    header('location:../dash_dokter.php');
  } elseif (isset($_SESSION['id_admin'])){
    header('location:../dash_admin.php');
  } elseif (isset($_SESSION['id_pasien'])){
    header('location:../dash_pasien.php');
  } else {
    include 'connect.php';

    if(isset($_POST['submit'])){
      @$user = mysqli_real_escape_string($conn, $_POST['username']);
      @$pass = mysqli_real_escape_string($conn, $_POST['password']);
      @$role = $_POST['role'];  // Get role from the form

      // Adjust query based on selected role
      if ($role == "dokter") {
        $login = mysqli_query($conn, "SELECT * FROM data_dokter WHERE username='$user' AND password='$pass'");
      } elseif ($role == "admin") {
        $login = mysqli_query($conn, "SELECT * FROM data_admin WHERE username='$user' AND password='$pass'");
      } elseif ($role == "pasien") {
        $login = mysqli_query($conn, "SELECT * FROM pasien WHERE username='$user' AND password='$pass'");
      }

      $cek = mysqli_num_rows($login);
      $userid = mysqli_fetch_array($login);

      if($cek == 0){
        echo '
        <script>
        setTimeout(function() {
          swal({
            title: "Login Gagal",
            text: "Username atau Password Anda Salah. Mohon periksa kembali form anda!",
            icon: "error"
            });
            }, 500);
            </script>
            ';
      } else {
        // Set session and redirect based on role
        if ($role == "dokter") {
          $_SESSION['id_dokter'] = $userid['id'];
          header('location:../dash_dokter.php');
        } elseif ($role == "admin") {
          $_SESSION['id_admin'] = $userid['id'];
          header('location:../dash_admin.php');
        } elseif ($role == "pasien") {
          $_SESSION['id_pasien'] = $userid['id'];
          header('location:../dash_pasien.php');
        }
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
              <div class="card-header"><h4>Login</h4></div>
              <div class="card-body">
                <form method="POST" action="" autocomplete="off">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                    <div class="invalid-feedback">Mohon isi username anda yang valid!</div>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                    <div class="invalid-feedback">Mohon isi password anda!</div>
                  </div>

                  <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                      <option value="" disabled selected>Pilih Role</option>
                      <option value="dokter">Dokter</option>
                      <option value="admin">Admin</option>
                      <option value="pasien">Pasien</option>
                      <div class="invalid-feedback">Mohon pilih role anda!</div>
                    </select>
                  </div>

                  <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                  </div>
                </form>
              </div>
              <div class="card-body text-center">
                <h6>Belum memiliki akun?<a href="login.php"> Daftar Disini!</a></h6>
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
  <script src="../assets/modules/tooltip.js"></script>
  <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../assets/modules/moment.min.js"></script>
  <script src="../assets/js/stisla.js"></script>

  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <script src="../assets/js/custom.js"></script>
  <!-- Sweet Alert -->
  <script src="../assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="../assets/js/page/modules-sweetalert.js"></script>
</body>
</html>
