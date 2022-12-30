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

if (isset($_GET["error"])) {
  if($_GET['error'] == 'closed'){
echo'
<div class="alert alert-warning alert-dismissible">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>Closed!</strong> This Layaway has been closed and cannot be edited.
</div>';
}
}

if (isset($_GET["deleted"])) { 

echo'
      <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Success!</strong> Layaway was deleted successfully.
        </div>';
}


if (isset($_GET["cid"])) {

  $userid = $_GET["cid"];

  include 'connection.php';
  $sql = "SELECT name FROM customerdetails WHERE CID = $userid";
  $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $name = $row['name'];
    }
  }

?>

<h1 class="display-4 py-5 text-center font-weight-bold">Layaway Details</h1>

<div class="container">

  <div class="form-row align-items-end">

    <div class="form-group col-auto">
    <a href="addcustomer.php"><button type="button" class="btn btn-info btn-md">New Layaway</button></a>
      <a href="index.php" class="btn btn-danger btn-md">Back</a>
</div>
<div class="form-group col">
      <input class="form-control" id="myInput" type="text" placeholder="Type to search for Customer...">
    </div>
  </div>

  <div class="tableFixHead">

    <table class="table table-striped table-light">
      <thead>
        <tr class="text-center">
          <th>Name</th>
          <th>No.of Deposits</th>
          <th>Balance</th>
          <th>Total</th>
          <th>Date Due</th>
          <th>Completed</th>
          <th>Action</th>
        </tr>
      </thead>


      <?php

      include 'connection.php';

      $sql = "SELECT * FROM layawaydetails,customerdetails WHERE customerdetails.CID = layawaydetails.CID";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {

        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
          $name = $row['name'];
          $TotalPrice = $row['total'];
          $balance = $row['balance'];
          $cid = $row['CID'];
          $lid = $row['LID'];
          $status = $row['status'];
          $progress = 0;
          if($TotalPrice > 0){
            $progress = (($TotalPrice - $balance) / $TotalPrice) * 100;
          }
          $Date = Date('Y-m-d');
          $dueDate = $row['dateDue'];
          $layaway = "addlayaway.php?cid=$cid";
          $dellayaway = "layawaydetails.php?delCID=$cid&delLID=$lid";

          $datecolor = 'class="text-white text-center" style="background: yellow"';

          $color = 'class="text-white text-center" style="background: lightgreen;"';

          if ($dueDate == '0000-00-00') {
            $dueDate = 'N/A';
          }

/*Progress colors styling*/

          switch (true) {
            case $progress <= 25 && $progress > 0:
              $color = 'class="text-dark text-center" style="background: #ff7979"';
              break;

            case $progress <= 50 && $progress > 25:
              $color = 'class="text-dark text-center" style="background: #ffbe76"';
              break;

            case $progress < 100 && $progress > 50:
              $color = 'class="text-dark text-center" style="background: #f6e58d"';
              break;

            case $progress == 100:
              $color = 'class="text-white text-center" style="background: #1dd1a1"';
              break;

            default:
              $color = 'class="text-white text-center" style="background: #000"';
              break;
          }


          //calculate the number of depoists 
          $depNum = "SELECT COUNT(PID) AS numDep FROM paymentdetails WHERE LID = $lid";
          $depresults = mysqli_query($conn, $depNum);
          $rowz = mysqli_fetch_assoc($depresults);
          $depositNumber = $rowz['numDep']; 

?>

              <tbody id="myTable">
                <tr class="text-center">
                  
                  <td><?php echo $name; ?></td>
                  <td><?php echo $depositNumber; ?></td>
                  <td><?php echo $balance; ?></td>
                  <td><?php echo $TotalPrice; ?></td>
                  <td><?php echo $dueDate; ?></td>
                  <td <?php echo $color; ?>><?php echo round($progress) . '%'; ?></td>
                  <td><a href="<?php echo $layaway; ?>" title="View customer Layway" 
                  class="btn btn-success btn-sm">Edit</a>

                <!-- delete button -->
                <?php if ($balance == 0 || $status == 'closed'){ ?>
              
                <a href="<?php echo $dellayaway; ?>" class="btn btn-danger btn-sm">Delete1</a>

                <?php
                }else{
                ?>
                <a type="button" class="btn btn-danger btn-sm text-white" data-toggle="modal" data-target="#staticBackdrop">Delete</a>

                    <!-- Modal for delete button-->
                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Confirm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <?php

                          include 'connection.php';
                          $amountqry = "SELECT SUM(Deposit) AS totalDep FROM paymentdetails WHERE LID = $lid";

                          $amt = mysqli_query($conn, $amountqry);
                          $rowz = mysqli_fetch_assoc($amt);
                          $totalDep = $rowz['totalDep'];

                          if ($totalDep == "") {
                            $totalDep = '0.00';
                          }

                          echo 'Customer has <b>$' . $totalDep . '</b> in Layaway, are you sure you want to delete this Layway and all the Deposits?';
                          echo "</br>";
                          echo "</br>";
                          echo "<b class='text-danger'>Please withdraw Deposited cash before deleting!</b>";

                          ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                          <a href="<?php echo "layaway_process.php?lid=$lid&cid=$cid&del_lay" ?>"><button type="button" class="btn btn-danger">Delete</button></a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
                } 
                ?>
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