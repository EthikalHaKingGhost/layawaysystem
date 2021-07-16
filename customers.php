<style type="text/css">
  html body {
    background: lightgrey;
  }

  .tableFixHead {
    overflow: auto;
    height: 600px;
  }

  .tableFixHead thead th {
    position: sticky;
    top: 0;
    z-index: 1;
  }
</style>


<?php

include 'header.php';

if (isset($_GET["del"])) {

  $delID = $_GET["del"];

  include('connection.php');

  $del = "DELETE FROM `customerdetails` WHERE `customerdetails`.`customerID` = $delID ";

  if (mysqli_query($conn, $del)) {
?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Success!</strong> Customer was deleted successfully.
    </div>
<?php
  }
}

?>

<h1 class="display-4 py-5 text-center font-weight-bold">Customers</h1>

<div class="container">

  <div class="form-row align-items-end">
    <div class="form-group col-auto">
      <a href="addcustomer.php"><button type="button" class="btn btn-info btn-md">Add Customer</button></a>
      <a href="index.php" class="btn btn-danger btn-md">Back</a>
    </div>
    <div class="form-group col">
      <input class="form-control" id="myInput" type="text" placeholder="Type to search for Customer...">
    </div>
  </div>

  <div class="tableFixHead">

    <table class="table table-bordered mt-">
      <thead class="thead-secondary">
        <tr>
          <th>Name</th>
          <th>Address</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Action</th>
        </tr>
      </thead>


      <?php

      include 'connection.php';

      $sql = "SELECT * FROM customerdetails WHERE customerID != 0 ORDER BY name ASC";

      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
          $name = $row["name"];
          $address = $row["address"];
          $email = $row['email'];
          $phone = $row["phone"];
          $CID = $row["customerID"];
          $link = "customerdetails.php?cid=$CID";
          $del = "customers.php?del=$CID";
          $layaway = "customerLayaways.php?cid=$CID";
          $process = "layaway_process.php?newLayaway&cid=$CID";


      ?>

          <tbody class="bg-light" id="myTable">
            <tr>
              <td><?php echo $name; ?></td>
              <td><?php echo $address; ?></td>
              <td><?php echo $email; ?></td>
              <td><?php echo $phone; ?></td>


              <form action="<?php echo $link; ?>" method="post">

                <input type="hidden" name="cid" value="<?php echo $CID ?>">
                <td class="text-right">
                  <button type="submit" class="btn btn-dark btn-sm">Edit</button>
                  <a href="<?php echo $del; ?>" title="delete customer" class="btn btn-danger btn-sm">Delete</a>

                  <?php
                  $LWYsql = "SELECT * FROM paymentdetails WHERE customerID = $CID";
                  $LWYresult = mysqli_query($conn, $LWYsql);
                  if (mysqli_num_rows($LWYresult) > 0) {
                  ?>
                    <a href="<?php echo $layaway; ?>" title="View customer Layway" class="btn btn-info btn-sm">View Layways</a>

                  <?php
                  } else {
                  ?>
                    <a href="<?php echo $process; ?>" title="View customer Layway" class="btn btn-success btn-sm">New Layway</a>

                  <?php
                  }
                  ?>


                </td>
              </form>

            </tr>
          </tbody>


      <?php
        }
      }
      ?>

    </table>
  </div>
</div>


<script>
  $(document).ready(function() {
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>

<?php include 'footer.php'; ?>