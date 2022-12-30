<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bookstore</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/css/all.min.css">
  <script type="text/javascript" src="fonts/css/all.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php"> <img class="logo" src="img/icon.png" height="40"> Heavenly Lights Bookstore</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar1">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown"> Reports</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="report.php">Z-out Report</a></li>
            <li><a class="dropdown-item" href="#">Information</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="btn ml-2" style="background-color: #f76c51; color: white;" href="layawaydetails.php">Layaways</a>
        </li>
        <li class="nav-item">
          <a class="btn ml-2" style="background-color: #5fa2dd; color: white;" href="customers.php">Customers</a>
        </li>
        <a class="btn ml-2" style="background-color: #4ebcda; color: white;" href="<?php echo 'layaway_process.php?newLayaway'; ?>">New Layaway</a></li>
        <li class="nav-item active">
          <a class="btn ml-2 btn-warning" href="index.php">Home</a>
        </li>
      </ul>
    </div>
  </nav>
