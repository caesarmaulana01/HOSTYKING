<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSTYKING - Hospital Facility Booking</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/Logo.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="../assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/modules/fontawesome/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EEEEEE;
            color: #222831;
        }

        .navbar {
            background-color: #222831 !important;
        }

        .navbar a {
            color: #EEEEEE !important;
        }

        .full-screen {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(135deg, #00ADB5 30%, #393E46 100%);
            color: #EEEEEE;
        }

        .full-screen h1 {
            font-weight: 600;
            font-size: 3rem;
        }

        .btn-primary, .btn-secondary {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #00ADB5;
            border: none;
        }

        .btn-primary:hover {
            background-color: #393E46;
            color: #EEEEEE;
        }

        .btn-secondary {
            background-color: #393E46;
            color: #EEEEEE;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #00ADB5;
        }

        .feature-section {
            background-color: #EEEEEE;
            padding: 100px 0;
        }

        .feature-card {
            background-color: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card i {
            font-size: 3rem;
            color: #00ADB5;
        }

        footer {
            background-color: #222831;
            color: #EEEEEE;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#home">HOSTYKING</a>
        </div>
    </nav>

    <section class="full-screen" id="home">
        <div class="container">
            <img src="../assets/img/Logo.png" alt="logo" width="100" class="shadow-light rounded-box mb-3">
            <h5>Hospital Facility Booking (HOSTYKING)</h5>
            <h1>Cepat tanpa Antrian Panjang!</h1>
            <p class="lead py-3">HOSTYKING memungkinkan pengguna membooking fasilitas rumah sakit dengan mudah.</p>
            <a href="../auth/login.php" class="btn btn-primary me-3">Login</a>
            <a href="../auth/signup.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </section>

    <section class="feature-section" id="features">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Keunggulan Pengguna</h2>
                    <p class="text-muted">HOSTYKING memberikan fitur terbaik untuk Admin, Dokter, dan Pasien demi kemudahan pengelolaan dan pelayanan rumah sakit.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-user-cog"></i>
                        <h4 class="mt-3">Admin</h4>
                        <p>Kelola data pasien, dokter, ruang inap, dan obat dengan mudah. Pantau aktivitas rumah sakit melalui dashboard dan lakukan validasi tindakan medis.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-user-md"></i>
                        <h4 class="mt-3">Dokter</h4>
                        <p>Akses data pasien, kelola jadwal praktek, dan buat laporan hasil pemeriksaan. Proses diagnosis dan tindakan medis dilakukan secara terstruktur dan efisien.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-user"></i>
                        <h4 class="mt-3">Pasien</h4>
                        <p>Lakukan booking konsultasi, kelola data diri, dan akses riwayat pemeriksaan serta foto medis dengan mudah. Dilengkapi fitur cetak hasil pemeriksaan dan jadwal dokter.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include "../part/footer.php" ?>

    <!-- General JS Scripts -->
    <script src="../assets/modules/jquery.min.js"></script>
    <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 800);
                }
            });
        });
    </script>
</body>
</html>