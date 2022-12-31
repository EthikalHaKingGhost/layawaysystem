<?php
if (isset($_GET['depo'])) {

  include 'connection.php';

  $count = $_GET['count'];
  $dep = $_GET['ttd'];
  $type = $_GET['type'];
  $sold = $_GET['sold'];

  $sql = "INSERT INTO `bookstorepaymentdetails` (`totalDeposit`, `drawCount`, `totalSold`, `type`, `date_deposited`) VALUES ('$dep', '$count', '$sold', '$type', current_timestamp());";

  $query = mysqli_query($conn, $sql);

  header("location: layaways.php");

  exit();
}




if (isset($_GET["check"])) {

  $count = $_GET['count'];
  $dep = $_GET['ttd'];
  $to_date = $_GET['to_date'];
  $from_date = $_GET['from_date'];
  $action = $_GET['action'];
  $type = $_GET['type'];
  $sold = $_GET['sold'];

  header("location: layaways.php?ttd=$dep&count=$count&from_date=$from_date&to_date=$to_date&action=$action&type=$type&sold=$sold");

  exit();
}




if (isset($_GET["addLayaway"])) {

  include 'connection.php';

  $lid = $_GET['lid'];
  $cid = $_GET['cid'];
  $dateDue = $_GET['dateDue'];
  $Deposit = $_GET['Deposit'];

  //check if items are added before proceeding
  $check_query = "SELECT * FROM `productdetails` WHERE LID = $lid";

  $result = mysqli_query($conn, $check_query);
  if (mysqli_num_rows($result) < 1) {

    header("location: addlayaway.php?lid=$lid&cid=$cid&error=noitems");

    exit();
  }

  if ($Deposit > 0){
    $insertDeposit = "INSERT INTO `paymentdetails` (`LID`, `deposit`,`balance`,`datePaid`) VALUES ('$lid', '$Deposit','0',current_timestamp())";
    if (mysqli_query($conn, $insertDeposit)) {
      $last_pid = mysqli_insert_id($conn);
    }
}
   

//calculate fields for laywaydetails table
$SumTotal = "SELECT SUM(Deposit) AS amount FROM paymentdetails WHERE paymentdetails.LID = $lid";
$SumTotalQry = mysqli_query($conn, $SumTotal);
$row = mysqli_fetch_assoc($SumTotalQry);
$totalDeposit = $row['amount'];

$productTotal = "SELECT SUM(qty*price) AS priceproducts FROM productdetails WHERE productdetails.LID = $lid";
$SumTotalPro = mysqli_query($conn, $productTotal);
$rows = mysqli_fetch_assoc($SumTotalPro);
$priceProducts = $rows['priceproducts'];

$balance = $priceProducts - $totalDeposit; //balance 

 //insert the deposit into depoits table as a deposit.
 if ($Deposit > 0){
  $updateDeposit = "UPDATE paymentdetails SET balance = $balance WHERE PID = $last_pid"; 
  $updtQuery = mysqli_query($conn, $updateDeposit);
  }

if ($balance == 0){
    $status = 'closed';
}else{
  $status = 'open';
}

  // update table for layayway and Submit Data 
  $sql = "UPDATE `layawaydetails` SET `balance` = '$balance', `total` = '$priceProducts', `dateUpdated` = CURRENT_TIMESTAMP, `dateDue` = '$dateDue', `status` = '$status' WHERE `layawaydetails`.`LID` = $lid";

  if (mysqli_query($conn, $sql)) {
    header("location: layawaydetails.php?status=success");
    exit();

  } else {

    header("location: layawaydetails.php?status=failed");


    exit();
  }
}




if (isset($_GET["del_lay"])) {
  $lid = $_GET['lid'];

  //check customers total paymentdetails
  include 'connection.php';

  $delprod = "DELETE FROM `productdetails` WHERE `LID` = $lid";
  $delqry = mysqli_query($conn, $delprod);

  $delpay = "DELETE FROM `paymentdetails` WHERE `LID` = $lid";
  $layqry = mysqli_query($conn, $delpay);

  $delete = "DELETE FROM `layawaydetails` WHERE `LID` = $lid";
  $qry = mysqli_query($conn, $delete);


  header("location: layawaydetails.php?deleted");

  exit();
} 




if (isset($_GET['type'])) {

  $customID = $_GET['cid'];
  $lid = $_GET['lid'];
  $DepNew = $_GET['DepAmt'];
  $currentBalance = $_GET['bal'];
  $newBal = $currentBalance - $DepNew;

  //insert the paymentdetails if the payment is new
  include 'connection.php';

  $checkDep = "SELECT * FROM `layawaydetails` WHERE `layawaydetails`.`LID` = $lid";
  $results = mysqli_query($conn, $checkDep);
  $row = mysqli_fetch_assoc($results);

  $Actualbalance = $row['balance'];

  if ($newBal < 0) {

    header("location: customerLayaways.php?lid=$lid&cid=$customID&error=over");

    exit();

  } else {

    $datepaid = Date('Y-m-d');

    $insertDeposit = "INSERT INTO `paymentdetails` (`LID`, `Deposit`,`datePaid`) VALUES ('$lid','$DepNew','$datepaid')";

    $paymentdetailsQL = mysqli_query($conn, $insertDeposit);

    $updateDep = "UPDATE `layawaydetails` SET `balance` = '$newBal'  WHERE `layawaydetails`.`LID` = '$lid'";

    $depUpdate = mysqli_query($conn, $updateDep);

    if ($newBal == 0) {
      header("location: customerLayaways.php?lid=$lid&cid=$customID&status=close");
      exit();
    } else {
      header("location: customerLayaways.php?lid=$lid&cid=$customID&status=open");
      exit();
    }
  }
}



if (isset($_GET["add"])) {
  $lid = $_GET['lid'];
  $cid = $_GET['cid'];

  include 'connection.php';

  $sql = "UPDATE layawaydetails SET CID = $cid WHERE LID = $lid";

  if (mysqli_query($conn, $sql)) {

    header("location: addlayaway.php?lid=$lid&cid=$cid");

    exit();
  }
}




// Open and setup new Layaway from

if (isset($_GET["newLayaway"])) {

  if (isset($_GET['cid'])) {

    $cid = $_GET['cid'];

  } else {
        header("location: addcustomer.php");
    exit();
  }

  include 'connection.php';

  $qry = "SELECT * FROM layawaydetails WHERE CID = $cid AND layawaydetails.status = 'open'";

  $result = mysqli_query($conn, $qry);
  if (mysqli_num_rows($result) > 1) {
    
   header("location: customerLayaways.php");

    exit();
      
  } else {

    $query = "INSERT INTO `layawaydetails`(`CID`,`balance`, `total`, `dateCreated`, `dateDue`, `status`) VALUES ($cid,0,0, current_timestamp(),'0000-00-00','open')";
    if (mysqli_query($conn, $query)) {
      $last_id = mysqli_insert_id($conn);

    }
   
    header("location: addlayaway.php?lid=$last_id&cid=$cid");

    exit();
  }
}


//delete an item from the product list

if (isset($_GET["delProduct"])) {

  $lid = $_GET['lid'];
  $cid = $_GET['cid'];
  $PDID = $_GET["PDID"];
  $status = "open";


  include 'connection.php';

  //count number of products and prevent user from deleting 1 product
  $qry = "SELECT count(*) as minprod FROM productdetails WHERE LID = $lid";
  $result = mysqli_query($conn, $qry);
  $minrow = mysqli_fetch_assoc($result);
  $count = $minrow['minprod'];
  if ($count < 2){

    header("location: addlayaway.php?error=minproduct&lid=$lid&cid=$cid");

      exit();
  }
  
  //delete product
          $del = "DELETE FROM `productdetails` WHERE `productdetails`.`PDID` = $PDID";
          $deleteID = mysqli_query($conn, $del);

          //calculate fields for laywaydetails table
          $SumTotal = "SELECT SUM(Deposit) AS amount FROM paymentdetails WHERE paymentdetails.LID = $lid";
          $SumTotalQry = mysqli_query($conn, $SumTotal);
          $row = mysqli_fetch_assoc($SumTotalQry);
          $totalDeposit = $row['amount'];
      
          $productTotal = "SELECT SUM(qty*price) AS priceproducts FROM productdetails WHERE productdetails.LID = $lid";
          $SumTotalPro = mysqli_query($conn, $productTotal);
          $rows = mysqli_fetch_assoc($SumTotalPro);
          $priceProducts = $rows['priceproducts'];
      
          $balance = $priceProducts - $totalDeposit; //balance 
  
          ////Add information to layaway table
          $itemUpdate = "UPDATE `layawaydetails` SET `CID` = $cid, `balance` = '$balance', `total` = '$priceProducts', `dateUpdated` = CURRENT_TIMESTAMP WHERE `layawaydetails`.`LID` = $lid";
  
          $updateQry = mysqli_query($conn, $itemUpdate);

  header("location: addlayaway.php?lid=$lid&cid=$cid");

  exit();
}


if (isset($_GET["Additem"])) {

  $cid = $_GET["cid"];
  $Quantity = $_GET["quantity"];
  $price = $_GET["price"];
  $lid = $_GET["lid"];
  $product = $_GET["product"];

  if (empty($_GET["product"]) or empty($_GET["price"] or $_GET["quantity"] = NULL)) {

    header("location: addlayaway.php?lid=$lid&cid=$cid&error=missingitem");

    exit();

  } else {

    include 'connection.php';

    //Add items to products table
        $insert_query = "INSERT INTO `productdetails`(`LID`,`product`, `qty`, `price`) VALUES ('$lid','$product','$Quantity','$price')";

        $LayawaySql = mysqli_query($conn, $insert_query);

                
        //calculate fields for laywaydetails table
        $SumTotal = "SELECT SUM(Deposit) AS amount FROM paymentdetails WHERE paymentdetails.LID = $lid";
        $SumTotalQry = mysqli_query($conn, $SumTotal);
        $row = mysqli_fetch_assoc($SumTotalQry);
        $totalDeposit = $row['amount'];
    
        $productTotal = "SELECT SUM(qty*price) AS priceproducts FROM productdetails WHERE productdetails.LID = $lid";
        $SumTotalPro = mysqli_query($conn, $productTotal);
        $rows = mysqli_fetch_assoc($SumTotalPro);
        $priceProducts = $rows['priceproducts'];
    
        $balance = $priceProducts - $totalDeposit; //balance 

        ////Add information to layaway table
        $itemUpdate = "UPDATE `layawaydetails` SET `CID` = $cid, `balance` = '$balance', `total` = '$priceProducts', `dateUpdated` = CURRENT_TIMESTAMP WHERE `layawaydetails`.`LID` = $lid";

        $updateQry = mysqli_query($conn, $itemUpdate);

    header("location: addlayaway.php?lid=$lid&cid=$cid");

    exit();
  }
}
