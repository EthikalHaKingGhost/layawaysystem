<?php

//check if there is a payment id and grab it

if (isset($_GET["cid"]) && !empty($_GET["cid"])) {

  $cid = $_GET["cid"];

  include 'connection.php';

if(isset($_GET["lid"]) && !empty($_GET["lid"])) {

  $lid = $_GET["lid"];

}else{

    //missing customer id in the url of an existing layaway
    $checkURL = "SELECT LID FROM layawaydetails WHERE CID = $cid AND layawaydetails.status = 'open'";

    $result = mysqli_query($conn, $checkURL);
  
    if (mysqli_num_rows($result) > 0) {
      
    $row = mysqli_fetch_assoc($result);
  
      $lid = $row['LID'];
  
      header("location: addlayaway.php?lid=$lid&cid=$cid");

      exit();
  
    }

  }

  $find = "SELECT customerdetails.name,dateDue FROM customerdetails,layawaydetails WHERE customerdetails.CID = layawaydetails.CID AND layawaydetails.CID = $cid AND layawaydetails.status = 'open'";

  $result_find = mysqli_query($conn, $find);

  if (mysqli_num_rows($result_find) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result_find)) {
      $name_find = $row["name"];
      $dateDue = $row["dateDue"];
    }


  }else{

    header("location: layawaydetails.php?error=closed");

    exit();
  }


}

include 'header.php';  

if (isset($_GET['error'])) {
  if ($_GET['error'] == "minproduct") {
    echo '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>OOPS!</strong> Please add another product before deleting the last product.
    </div>';
  }
}
?>

<h1 class="display-4 py-5 text-center font-weight-bold"><?php echo "Layaway for ".$name_find ?> </h1>

<body class="vh-100" style="background-color: lightgrey;">
  <div class="container bg-light rounded shadow">
    <div class="row d-flex justify-content-center p-4 mt-4">

      <div class="col-6">

        <form action='layaway_process.php' method='get'>

        <label for="productinfo">Product Name</label>
                <textarea class="form-control mb-3" rows="2" name="product" id="productinfo"></textarea>

                <div class="form-group">
                  <div class="form-row">
                    <div class="col">
                      <label for="input_id_20">Quantity</label>
                      <input type="number" class="form-control form-control-sm mb-3" min="1" value="1" name="quantity" id="quantityinfo" />
                    </div>
                    <div class="col">
                      <label>Cost of Item</label>
                      <input type="number" class="form-control form-control-sm mb-3" min="1" placeholder="0" step="any" name="price" />
                    </div>
                  </div>
                </div>

                <div class="text-center pb-2">
                  <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                  <input type="hidden" name="cid" value='<?php echo $cid; ?>'>

                  <input type="submit" class="btn btn-dark" name="Additem" value="Add Item">

                  <?php

                  if (isset($_GET['error'])) {
                    if ($_GET['error'] == "missingitem") {
                      echo '<h4 class="text-center pt-2 text-danger">Please enter product information!</h4>';
                    }
                  }

                  if (isset($_GET['error'])) {
                    if ($_GET['error'] == "noitems") {
                      echo '<h4 class="text-center pt-2 text-danger">please click here to add an item to the list!</h4>';
                    }
                  }


                  //checks if inital deposit is same as balance 
                  $ttl = 99999;
                  $bal = 99999;
                  $sql2 = "SELECT * FROM layawaydetails WHERE LID = $lid";
                  $sql2qry = mysqli_query($conn, $sql2);
                    $row = mysqli_fetch_assoc($sql2qry);
                      $ttl = $row['total'];
                      $bal = $row['balance'];
                  ?>

                </div>


          <hr class="solid pb-2" style="border-top: 3px solid #EEE;">

<!-------- DEPOSIT FIELDS  ---------> 
          <div class="form-group">
              <div class="form-row">
                      <div class="col mb-3">
                        <label><?php
                        //checks if inital deposit is same as balance and updates label
                         if ($row['total'] != $row['balance']){echo 'Deposit';}else{ echo 'Initial Deposit';} 
                                                 
                         ?></label>
                        <input type="number" class="form-control form-control-sm " min="1" max="<?php echo $row['balance']; ?>" step="any" name="Deposit" placeholder="0">
                        <small class="text-muted">Change customer deposit</small>
                      </div>
                      <div class="col mb-3">
                        <label>Due Date</label>
                        <input type="Date" class="form-control form-control-sm " name="dateDue" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $dateDue; ?>">
                      </div>
                </div>
            </div>

<h2><?php echo "Total: $". $row['total']; ?></h2>
<h3><?php echo "Balance: $". $row['balance']; ?></h3>


 <!-------- END OF DEPOSIT FIELDS  ---------> 


 <!-------- DEPOSIT BUTTONS  ---------> 
            <div class="text-center my-3">
              <a class="btn btn-primary btn-md" onclick="history.go(-1);">Back </a>
              <input type="hidden" name="lid" value="<?php echo $lid; ?>">
              <input type="hidden" name="cid" value="<?php echo $cid; ?>">

              <?php

              //change button to update if layaway total is not equal to the balance.
              include 'connection.php';

              $buttonQRY = "SELECT COUNT(*) AS count FROM `layawaydetails` WHERE `CID` = $cid";
              $sql3qry = mysqli_query($conn, $buttonQRY);
                $rows = mysqli_fetch_assoc($sql3qry);
                (int)$count = $rows['count'];
              if ($rows['count'] > 1) {
              ?>
                <a type="button" class="btn btn-dark" href="Layawaydetails.php">
                  All Layaways
                </a>
              <?php
              }

              //check if total deposit is not equal to balance (!= if deposit made)
              if ($row['total'] != $row['balance']){
              ?>
                <input type="submit" class="btn btn-success btn-md" name="addLayaway" value="Update">
              <!-- delete button -->
                <a type="button" class="btn btn-danger" data-toggle="modal" data-target="#staticBackdrop">
                  Delete
                </a>

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
           $row = mysqli_fetch_assoc($amt);
           $totalDep = $row['totalDep'];

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
 } else { 
 ?>

  <input type="submit" class="btn btn-info btn-md " name="addLayaway" value="Add">

<?php
 }
 ?>

</div>
</form>

</div>
      <div class="col-6 my-auto">
        
        <?php include "elements/table.php"; ?>
      </div>


    </div>
  </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<?php

include 'footer.php'; ?>