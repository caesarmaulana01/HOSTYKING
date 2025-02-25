   <meta charset="UTF-8">
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
   <title><?php echo $page; ?></title>
   <link rel="shortcut icon" type="image/x-icon" href="assets/img/Logo.png" />

   <!-- General CSS Files -->
   <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
   <link rel="stylesheet" href="assets/modules/ionicons/css/ionicons.min.css">
   <link rel="stylesheet" href="assets/modules/bootstrap-daterangepicker/daterangepicker.css">
   <link rel="stylesheet" href="assets/css/components.css">

   <!-- CSS Libraries -->
   <link rel="stylesheet" href="assets/modules/jqvmap/dist/jqvmap.min.css">
   <link rel="stylesheet" href="assets/modules/weather-icon/css/weather-icons.min.css">
   <link rel="stylesheet" href="assets/modules/weather-icon/css/weather-icons-wind.min.css">
   <link rel="stylesheet" href="assets/modules/summernote/summernote-bs4.css">
   <link rel="stylesheet" href="assets/modules/jquery-selectric/selectric.css">
   <link rel="stylesheet" href="assets/modules/chocolat/dist/css/chocolat.css">
   <link rel="stylesheet" href="assets/modules/select2/dist/css/select2.min.css">

   <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
   <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

   <!-- Template CSS -->
   <link rel="stylesheet" href="assets/css/style.css">
   <link rel="stylesheet" href="assets/css/components.css">

   <style>
      /* btn yang mirip link */
      #btn-link {
         border: none;
         outline: none;
         background: none;
         cursor: pointer;
         padding: 0;
         font-family: inherit;
         font-size: inherit;
      }

      /*auto completenya dibooking */
      .autocomplete {
         position: relative;
         display: inline-block;
      }
      .autocomplete-items {
         position: absolute;
         border: 1px solid;
         border-bottom: none;
         border-top: none;
         z-index: 99;
      }
      .autocomplete-items div {
         padding: 10px;
         cursor: pointer;
         border-bottom: 1px solid;
      }
      
      /* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

/* Global styles */
body {
   font-family: 'Poppins', sans-serif;
   color: #222831;
}

/* Heading styles */
h1, h2, h3, h4, h5, h6 {
   font-family: 'Poppins', sans-serif;
   color: #222831;
   font-weight: 600;
}

/* Link styles */
a {
   color: #00ADB5;
   text-decoration: none;
   transition: color 0.3s ease;
}

a:hover {
   color: #393E46;
   text-decoration: underline;
}

/* Button styles */
.btn-primary {
   background-color: #222831;
   border-color: #222831;
   color: #fff;
   border-radius: 8px;
   transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.btn-primary:hover {
   background-color: #393E46;
   box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
   background-color: #00ADB5;
   border-color: #00ADB5;
   color: #fff;
   border-radius: 8px;
}

.btn-secondary:hover {
   background-color: #393E46;
}

/* Navbar styles */
.navbar {
   background-color: #222831;
   color: #fff;
}

.navbar a {
   color: #fff;
}

.navbar a:hover {
   color: #00ADB5;
}

/* Card styles */
.card {
   border-radius: 16px;
   box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
   border: none;
   background-color: #EEEEEE;
}

.card-header {
   background-color: #393E46;
   color: #fff;
   font-weight: 500;
}

/* Table styles */
.table {
   color: #222831;
   font-family: 'Poppins', sans-serif;
   background-color: #EEEEEE;
}

.table thead th {
   background-color: #393E46;
   color: #fff;
}

.table tbody tr:hover {
   background-color: #00ADB5;
   color: #fff;
}

/* Form input styles */
input[type="text"], input[type="email"], input[type="password"], textarea, select {
   border-radius: 10px;
   border: 1px solid #393E46;
   padding: 12px;
   background-color: #EEEEEE;
   color: #222831;
   transition: border-color 0.3s ease;
}

input:focus, textarea:focus, select:focus {
   border-color: #00ADB5;
   box-shadow: 0 0 5px rgba(0, 173, 181, 0.5);
}

/* Autocomplete custom style */
.autocomplete-items {
   border-color: #393E46;
   background-color: #EEEEEE;
}

.autocomplete-items div:hover {
   background-color: #00ADB5;
   color: #fff;
}

/* Custom btn-link style */
#btn-link {
   color: #00ADB5;
   font-weight: 500;
}

#btn-link:hover {
   color: #393E46;
   text-decoration: underline;
}
   </style>