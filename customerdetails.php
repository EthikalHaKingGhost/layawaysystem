<style type="text/css">
  html body {
    background: lightgrey;
  }
</style>

<?php

if (isset($_GET["cid"])) {

  $CID = $_GET["cid"];
} else {

  header('location: customers.php');

  exit();
}

include 'header.php';


if (isset($_GET["stat"])) {
  $status = $_GET["stat"];

  if ($status == "success") {
?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Success!</strong> Customer Updated Successfully.
    </div>

  <?php
  }

  if ($status == "fail") {
  ?>

    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Failed!</strong> Failed to update customer, please try again.
    </div>
<?php
  }
}
?>

<p class="display-4 py-4 text-center font-weight-bold">
  <?php echo "Customer #" . $CID ?> </p>

<div class="container col-md-4 py-4 bg-light">

  <?php
  include 'connection.php';

  $sql = "SELECT * FROM customerdetails WHERE CID = $CID";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {

      $name = $row["name"];
      $address = $row["address"];
      $email = $row['email'];
      $phone = $row["phone"];
      $customerID  = $row["CID"];
      $update = "customer_edit.php";
      $back = "customers.php";
      $del = "customers.php?del=$customerID";
    }
  }  

  ?>

  <form method="post" action="<?php echo $update; ?>">

    <div class="form-group">
      <label for="firstname">Full Name</label>
      <input type="text" name="name" class="form-control" value='<?php echo $name ?>'>
    </div>

    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" name="address" class="form-control" value='<?php echo $address ?>'>

    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" class="form-control" value='<?php echo $email ?>'>

    </div>
    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" name="phone" class="form-control" value='<?php echo $phone ?>'>
      <input type="hidden" name="cid" class="form-control" value='<?php echo $customerID ?>'>
    </div>

    <div>

      <input type="submit" name="submit" class="btn btn-success" value="Update">
      <a href="<?php echo $back; ?>" title="back" class="btn btn-dark">Back</a>
      <a href="<?php echo $del; ?>" title="delete customer" class="btn btn-danger float-right">Delete</a>


    </div>


  </form>
</div>

<?php include 'footer.php'; ?>