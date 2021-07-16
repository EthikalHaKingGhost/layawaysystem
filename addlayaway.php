<?php

//check if there is a payment id and grab it

if (isset($_GET["pid"]) && !empty($_GET["pid"])) {

  $pid = $_GET["pid"];

  include 'connection.php';

  //payment id does not exist in database

  $checkpid = "SELECT * FROM `paymentdetails` WHERE paymentID = $pid";
  $querypid = mysqli_query($conn, $checkpid);
  if (mysqli_num_rows($querypid) == 0) {

    header("location: index.php");

    exit();
  }

  //missing customer id in the url of an existing layaway

  $checkURL = "SELECT * FROM `paymentdetails` WHERE `paymentID` = $pid AND `customerID` > 0";
  $result = mysqli_query($conn, $checkURL);
  if (mysqli_num_rows($result) > 0) {

    if (!isset($_GET["cid"]) or empty($_GET["cid"] or $_GET["cid"] = NULL)) {


      $fetchcid = "SELECT customerID FROM paymentdetails WHERE paymentID = $pid";

      $fetch = mysqli_query($conn, $fetchcid);

      $row = mysqli_fetch_assoc($fetch);

      $fetch_cid = $row['customerID'];

      header("location: addlayaway.php?pid=$pid&cid=$fetch_cid");

      exit();
    }
  }
} else {

  include 'connection.php';

  $delete = "DELETE FROM `paymentdetails` WHERE `customerID` = 0";

  $qry = mysqli_query($conn, $delete);

  header("location: index.php");

  exit();
}


//refill text box when page refreshes

if (isset($_GET["cid"]) && !empty($_GET["cid"])) {

  $new_cid = $_GET['cid'];

  include 'connection.php';

  $find = "SELECT * FROM customerdetails WHERE customerID = $new_cid";
  $result_find = mysqli_query($conn, $find);
  if (mysqli_num_rows($result_find) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result_find)) {
      $name_find = $row["name"];
      $email_find = $row['email'];
      $phone_find = $row['phone'];
      $address_find = $row['address'];
    }
  }
}

include 'header.php';  ?>

<section class="py-5">
  <div class="container">
    <div class="row shadow rounded d-flex justify-content-center" style="background-color: lightgrey;">

      <div class="col-6 ">
        <form action='layaway_process.php' method='get'>
          <?php require "elements/customerBtns.php"; ?>

          <?php require "elements/inputFormCustomer.php"; ?>

          <hr class="solid pb-2" style="border-top: 3px solid #000;">

          <?php require "elements/addItemBtns.php"; ?>

          <hr class="solid pb-2" style="border-top: 3px solid #000;">

          <?php require "elements/inputDeposit.php"; ?>

          <?php require "elements/DepositBtns.php"; ?>

        </form>

      </div>
      <div class="col-6 my-auto">
        <?php include "elements/table.php"; ?>
      </div>


    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<?php

include 'footer.php'; ?>