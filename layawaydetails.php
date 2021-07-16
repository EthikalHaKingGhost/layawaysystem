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
      <strong>Success!</strong> Layway Details
    </div>
<?php
  }
}

?>

<h1 class="display-4 py-5 text-center font-weight-bold">Payment Details</h1>

<div class="container">

  <div class="form-row align-items-end">

    <div class="form-group col">
      <input class="form-control" id="myInput" type="text" placeholder="Type to search for Customer...">
    </div>
  </div>

  <div class="tableFixHead">

    <table class="table table-striped table-light">
      <thead>
        <tr class="text-center">
          <th>Due</th>
          <th>Name</th>
          <th>Email</th>
          <th>Balance</th>
          <th>No.of Deposits</th>
          <th>Credit</th>
          <th>Completed</th>
          <th>Action</th>
        </tr>
      </thead>


      <?php

      include 'connection.php';

      $sql = "SELECT * FROM paymentdetails, customerdetails WHERE customerdetails.customerID = paymentdetails.customerID AND paymentdetails.customerID != 0 ORDER BY balance DESC
";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {

        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
          $name = $row['name'];
          $email = $row['email'];
          $phone = $row['phone'];
          $address = $row['address'];
          $status = $row['status'];
          $address = $row['address'];
          $totalQuantity = $row['totalQuantity'];
          $TotalPrice = $row['TotalPrice'];
          $balance = $row['balance'];
          $status = $row['status'];
          $cid = $row['customerID'];
          $pid = $row['paymentID'];
          $progress = (($TotalPrice - $balance) / $TotalPrice) * 100;
          $Date = Date('Y-m-d');
          $dueDate = $row['dueDate'];
          $layaway = "customerLayaways.php?cid=$cid";

          $datecolor = 'class="text-white text-center" style="background: yellow"';

          $color = 'class="text-white text-center" style="background: lightgreen;"';

          if ($dueDate == '0000-00-00') {
            $dueDate = 'N/A';
          }


          switch (true) {
            case $progress <= 25 && $progress > 0:
              $color = 'class="text-white text-center" style="background: #ff7979"';
              break;

            case $progress <= 50 && $progress > 25:
              $color = 'class="text-white text-center" style="background: #ffbe76"';
              break;

            case $progress < 100 && $progress > 50:
              $color = 'class="text-white text-center" style="background: #f6e58d"';
              break;

            case $progress == 100:
              $color = 'class="text-white text-center" style="background: #1dd1a1"';
              break;

            default:
              $color = 'class="text-white text-center" style="background: #000"';
              break;
          }


          //calculate the number of depoists 
          $depNum = "SELECT COUNT(depositID) AS numDep FROM deposits WHERE paymentID = $pid";
          $depresults = mysqli_query($conn, $depNum);
          $num = mysqli_fetch_assoc($depresults);
          $depositNumber = $num['numDep'];


          // Declare and define two dates
          $date1 = strtotime($dueDate);
          $date2 = strtotime($Date);

          // Formulate the Difference between two dates
          $diff = abs($date2 - $date1);

          // To get the year divide the resultant date into
          // total seconds in a year (365*60*60*24)
          $years = floor($diff / (365 * 60 * 60 * 24));

          // To get the month, subtract it with years and
          // divide the resultant date into
          // total seconds in a month (30*60*60*24)
          $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));

          // To get the day, subtract it with years and 
          // months and divide the resultant date into
          // total seconds in a days (60*60*24)
          $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
            $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));




          if ($date2 >= $dueDate) {
            $due = "due";
          } else {

            if ($months > 0) {
              $labelMonths = ' months, ';
            } else if ($months == 0) {
              $months = '';
              $labelMonths = '';
            }

            if ($days > 0) {
              $labelDays = ' days';
            } else if ($days == 0) {
              $days = '';
              $labelDays = '';
            }

            $due = '';
          }

      ?>

          <tbody id="myTable">
            <tr>
              <td><?php echo $months . $labelMonths . $days . $labelDays . $due; ?></td>
              <td><?php echo $name; ?></td>
              <td><?php echo $email; ?></td>
              <td><?php echo $balance; ?></td>
              <td class="text-center"><?php echo $depositNumber; ?></td>
              <td><?php echo $TotalPrice; ?></td>
              <td <?php echo $color; ?>><?php echo round($progress) . '%'; ?></td>


              

                  <a href="<?php echo $layaway; ?>" title="View customer Layway" class="btn btn-outline-dark btn-sm">View Layways</a>

                </td>
              

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

<?php include 'footer.php'; ?>