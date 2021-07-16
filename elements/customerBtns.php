<?php
#Hide-show button, checks to see if layways exist.
include 'connection.php';
$hide_show_button = "SELECT * FROM paymentdetails WHERE paymentID = $pid AND customerID > 0";
$result = mysqli_query($conn, $hide_show_button);
if (mysqli_num_rows($result) < 1) {
?>
  <div class="form-group">
    <div class="col">
      <div class="dropdown pt-4">
        <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Choose a Customer
        </button>
        <div class="dropdown-menu" style=" background:lightskyblue; height: auto; max-height: 200px; overflow-x:hidden;" aria-labelledby="dropdownMenuLink">

          <a class="dropdown-item" style="background: royalblue;" href="addlayaway.php?pid=<?php echo $pid; ?>">New Customer</a>
          <?php

          include 'connection.php';
          $sql = "SELECT * FROM customerdetails ORDER BY `customerdetails`.`name` ASC";
          $result = mysqli_query($conn, $sql);
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
              $name_search = $row["name"];
              $email = $row['email'];
              $search_id = $row["customerID"];

          ?>
              <a class="dropdown-item" href="<?php echo "addlayaway.php?pid=$pid&cid=$search_id"; ?>"><?php echo $name_search . ' [' . $email . ']'; ?></a>
            <?php
            }
          } else {
            ?>
            <a class="dropdown-item" href="addcustomer.php">Add a customer</a>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
<?php
} else {
?>

  <div class="form-group">
    <div class="col">
      <div class="dropdown pt-4">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Change Customer
        </button>
        <div class="dropdown-menu" style=" background:lightskyblue; height: auto; max-height: 200px; overflow-x:hidden;" aria-labelledby="dropdownMenuLink">
          <?php
          include 'connection.php';
          $sql2 = "SELECT * FROM customerdetails ORDER BY `customerdetails`.`name` ASC";
          $result = mysqli_query($conn, $sql2);
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
              $name_search = $row["name"];
              $email = $row['email'];
              $CID = $row["customerID"];
          ?>

              <a class="dropdown-item" href="<?php echo "layaway_process.php?pid=$pid&cid=$CID&add"; ?>"><?php echo $name_search . ' [' . $email . ']'; ?></a>

            <?php
            }
          } else {
            ?>

            <a class="dropdown-item" href="addcustomer.php">Add a customer</a>

          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>