<?php include 'header.php';

if (isset($_GET['cid'])) {

  $cid = $_GET['cid'];
} else {

  header("location: customers.php");

  exit();
}



?>

<body style="background-color: lightgray;">

<div class="container">
    <div class="row text-center mt-3">
        <?php

    include 'connection.php';

    $sql = "SELECT * FROM layawaydetails,customerdetails WHERE layawaydetails.CID = customerdetails.CID AND layawaydetails.CID = $cid ORDER BY LID DESC";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while ($row = mysqli_fetch_assoc($result)) {
        $payID = $row['LID'];
        $date = $row['dateCreated'];
        $due = $row['dateDue'];
        $status = $row['status'];
        $name = $row['name'];
        $address = $row['address'];
    ?>

        <div class="p-3 well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="card p-4 shadow bg-white border-0">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 text-left">
                        <?php 
                if ($status == 'closed'){
                    echo '<img src="img/paid.png" alt="Paid" width="150">';
                 }else if($date >= $due){
                  echo  '<img src="img/overdue.png" alt="Paid" width="125">';
                  }
                ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                        <p>
                            <em>
                                <?php echo $name; ?>
                            </em>
                            <em>
                                <?php echo $address; ?>
                            </em>
                            <em>Purchased:
                                <?php echo date('F d, Y', strtotime($date)) ?>
                            </em>
                            <br>
                            <?php
                  if ($due == '0000-00-00') {

                    echo '<em>Due: N/A</em>';
                  } else {
                    echo '<em>Due: ' .  date('F d, Y', strtotime($due)) . '</em>';
                  }
                  ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center">
                        <h1>
                            <?php echo '<em>Receipt #' . $payID . '</em>'; ?>
                        </h1>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr class="table-secondary">
                                <th>Product</th>
                                <th>Qty</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                  $productsinfo = "SELECT * FROM layawaydetails, productdetails WHERE layawaydetails.LID = productdetails.LID AND productdetails.LID = $payID AND layawaydetails.CID = $cid";
                  $productsql = mysqli_query($conn, $productsinfo);
                  if (mysqli_num_rows($productsql) > 0) {
                    // output data of each row
                    while ($row = mysqli_fetch_assoc($productsql)) {
                      $product = $row['product'];
                      $qty = $row['qty'];
                      $price = $row['price'];
                      $subtotal = $qty * $price;

                      $totalAll = "SELECT * FROM `layawaydetails` WHERE CID = $cid AND LID = $payID ";
                      $totalAllQRY = mysqli_query($conn, $totalAll);
                      $row = mysqli_fetch_assoc($totalAllQRY);
                      $total = $row['total'];


                      $DepAmt = "SELECT SUM(Deposit) AS totals FROM `paymentdetails` WHERE LID = $payID ";

                      $DepAmtQry = mysqli_query($conn, $DepAmt);
                      $row = mysqli_fetch_assoc($DepAmtQry);

                      $DepositAMT = $row['totals'];

                      $balance = $total - $DepositAMT;
                  ?>
                            <tr class="text-left">
                                <td class="col-md-9"><em>
                                        <?php echo $product; ?>
                                    </em></td>
                                <td class="col-md-1" style="text-align: center">
                                    <?php echo $qty; ?>
                                </td>
                                <td class="col-md-1 text-center">
                                    <?php echo '$' . $price; ?>
                                </td>
                                <td class="col-md-1 text-center">
                                    <?php echo '$' . number_format($subtotal, 2); ?>
                                </td>
                            </tr>

                            <?php
                    }
                  }
                  ?>
                            <tr>
                                <td> </td>
                                <td> </td>

                                <td class="text-right">
                                    <h4><strong>Total:</strong></h4>
                                    <p></p>
                                </td>

                                <td class="text-center">
                                    <h4><strong class="text-success">
                                            <?php echo '$' . number_format($total, 2) ?>
                                        </strong></h4>
                                    <p></p>
                                </td>

                            </tr>

                            <tr class=" table-secondary">
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                    <strong>Date</strong>
                                </td>
                                <td class="text-center">
                                    <strong>Deposits</strong>
                                </td>
                            </tr>

                            <?php
                  $AllDep = "SELECT * FROM paymentdetails WHERE LID = $payID";
                  $AllDepQry = mysqli_query($conn, $AllDep);
                  if (mysqli_num_rows($AllDepQry) > 0) {
                    while ($rows = mysqli_fetch_assoc($AllDepQry)) {
                      $dep = $rows['Deposit'];
                      $DateDep = $rows['datePaid'];
                  ?>
                            <tr class="table-sm">

                                <td> </td>
                                <td> </td>

                                <td class="text-center">
                                    <?php echo $DateDep; ?>
                                </td>

                                <td class="text-center">
                                    <?php echo '$' . number_format($dep, 2); ?>
                                </td>

                            </tr>


                            <?php
                    }
                  }
                  ?>

                            <tr>
                                <td> </td>
                                <td> </td>
                                <td class="text-right">
                                    <h4><strong>Balance:</strong></h4>
                                </td>
                                <td class="text-center text-danger">
                                    <h4><strong>
                                            <?php echo '$' . number_format($balance, 2); ?>
                                        </strong></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <td>
                        <?php
                 $qry = "SELECT * FROM layawaydetails WHERE LID = $payID AND status = 'closed'";
                  $query = mysqli_query($conn, $qry);
                  if (mysqli_num_rows($query) > 0) {

                   }else{
                  ?>
                        <a class="btn btn-secondary" href='<?php echo "addlayaway.php?pid=$payID&cid=$cid" ?>'>edit</a>
                        <?php
                  }
                ?>
                    </td>


                </div>
            </div>
        </div>

        <?php
      }
      }else {

     header("location: index.php");

      exit();
    } 
    ?>

    </div>
</div>
</body>


<?php include 'footer.php' ?>