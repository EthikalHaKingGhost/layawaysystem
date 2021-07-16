<?php
if (isset($_GET['depo'])) {

  include 'connection.php';

  $count = $_GET['count'];
  $dep = $_GET['ttd'];
  $type = $_GET['type'];
  $sold = $_GET['sold'];

  $sql = "INSERT INTO `bookstoredeposits` (`totalDeposit`, `drawCount`, `totalSold`, `type`, `date_deposited`) VALUES ('$dep', '$count', '$sold', '$type', current_timestamp());";

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
  $pid = $_GET['pid'];
  $cid = $_GET['cid'];
  $Deposit = $_GET['initDeposit'];
  $dueDate = $_GET['dueDate'];
  $cashtype = $_GET['typ'];

  //check if items are added before proceeding
  $check_query = "SELECT * FROM `productdetails` WHERE paymentID = $pid";

  $result = mysqli_query($conn, $check_query);
  if (mysqli_num_rows($result) < 1) {

    header("location: addlayaway.php?pid=$pid&cid=$cid&error=noitems");

    exit();
  }

  //Total Amount of money

  $query = "SELECT SUM(qty*price) AS total
FROM productdetails WHERE paymentID = '$pid'";

  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $totalAmount = $row['total'];

  $balance = $totalAmount - $Deposit;

  //total quantity

  $totalQTY = "SELECT SUM(qty) AS totalQTY FROM productdetails WHERE paymentID = '$pid'";
  $qry = mysqli_query($conn, $totalQTY);
  $row = mysqli_fetch_assoc($qry);
  $totalQTY = $row['totalQTY'];
  $payDate = date('Y-m-d');

  //check to see if initial deposit was already made
  $InitCheck = "SELECT * FROM `paymentdetails` WHERE paymentID = $pid AND `intDeposit` > 0";
  $InitQry = mysqli_query($conn, $InitCheck);
  if (mysqli_num_rows($InitQry) > 0) {

    //update the deposits if the customer edits the layaway
    $updateDep = "UPDATE `deposits`, `paymentdetails` SET `deposits`.`Deposit` = '$Deposit', `deposits`.`paymentType` = '$cashtype', `deposits`.`status` = 'open' WHERE `deposits`.`Deposit` = `paymentdetails`.`intDeposit` AND `deposits`.`paymentID` = $pid ";

    $updateDepQry = mysqli_query($conn, $updateDep);
  } else {

    //insert the initial deposit into depoits table as a a deposit.
    $insertDeposit = "INSERT INTO `deposits` (`paymentID`, `Deposit`, `paymentType`, `status`) VALUES ('$pid', '$Deposit', '$cashtype', 'open')";
    $InitDep = mysqli_query($conn, $insertDeposit);
  }


  // update table for layayway and Submit Data 

  $sql = "UPDATE `paymentdetails` SET `customerID` = '$cid',`balance` = '$balance', `intDeposit` = '$Deposit', `totalQuantity`= '$totalQTY',  `TotalPrice` = '$totalAmount', `payDate` = '$payDate', `status` = 'open', `dueDate` = '$dueDate' WHERE `paymentdetails`.`paymentID` = $pid;";

  if (mysqli_query($conn, $sql)) {


    header("location: layawaydetails.php?status=success");
    exit();
  } else {

    header("location: layawaydetails.php?status=failed");

    exit();
  }
}




if (isset($_GET["del_lay"])) {
  $pid = $_GET['pid'];

  //check customers total deposits
  include 'connection.php';

  $delete = "DELETE FROM `paymentdetails` WHERE `paymentID` = $pid";
  $qry = mysqli_query($conn, $delete);


  header("location: index.php");

  exit();
}




if (isset($_GET['type'])) {

  $customID = $_GET['cid'];
  $pid = $_GET['pid'];
  $DepNew = $_GET['DepAmt'];
  $type = $_GET['type'];
  $currentBalance = $_GET['bal'];
  $newBal = $currentBalance - $DepNew;

  //insert the deposits if the payment is new
  include 'connection.php';


  if ($type == 'cash') {
    $SumCredit = "SELECT SUM(Deposit) AS cash FROM deposits WHERE paymentType = '$type'";
    $SumCreditQry = mysqli_query($conn, $SumCredit);
    $row = mysqli_fetch_assoc($SumCreditQry);
    $cash = $row['cash'];
  } elseif ($type == 'credit') {
    $SumCredit = "SELECT SUM(Deposit) AS credit FROM deposits WHERE paymentType = '$type'";
    $SumCreditQry = mysqli_query($conn, $SumCredit);
    $row = mysqli_fetch_assoc($SumCreditQry);
    $credit = $row['credit'];
  }


  $checkDep = "SELECT * FROM `paymentdetails` WHERE `paymentdetails`.`paymentID` = $pid";
  $results = mysqli_query($conn, $checkDep);
  $row = mysqli_fetch_assoc($results);

  $Actualbalance = $row['balance'];

  if ($newBal < 0) {

    header("location: customerLayaways.php?pid=$pid&cid=$customID&error=over");

    exit();
  } else {

    $insertDeposit = "INSERT INTO `deposits` (`paymentID`, `Deposit`, `paymentType`, `status`) VALUES ('$pid','$DepNew', '$type' ,'deposit')";

    $depositSQL = mysqli_query($conn, $insertDeposit);


    $updateDep = "UPDATE `paymentdetails` SET `balance` = '$newBal', `totalCredit` = $credit, `totalCash` = $cash  WHERE `paymentdetails`.`paymentID` = '$pid'";

    $depUpdate = mysqli_query($conn, $updateDep);

    if ($newBal == 0) {
      header("location: customerLayaways.php?pid=$pid&cid=$customID&status=close");
      exit();
    } else {
      header("location: customerLayaways.php?pid=$pid&cid=$customID&status=open");
      exit();
    }
  }
}





if (isset($_GET["add"])) {
  $pid = $_GET['pid'];
  $cid = $_GET['cid'];

  include 'connection.php';

  $sql = "UPDATE paymentdetails SET customerID = $cid WHERE paymentID = $pid";

  if (mysqli_query($conn, $sql)) {

    header("location: addlayaway.php?pid=$pid&cid=$cid");

    exit();
  }
}



if (isset($_GET["newLayaway"])) {

  if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
  } else {
    $cid = "";
  }

  include 'connection.php';

  $qry = "SELECT * FROM paymentdetails WHERE customerID = 0";

  $result = mysqli_query($conn, $qry);
  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {

      $pid = $row['paymentID'];

      $delProducts = "DELETE FROM productdetails WHERE paymentID = $pid";
      $queryDel = mysqli_query($conn, $delProducts);
    }

    $delete = "DELETE FROM `paymentdetails` WHERE `customerID` = 0";
    $qry = mysqli_query($conn, $delete);

    $query = "INSERT INTO `paymentdetails`(`customerID`,`balance`, `intDeposit`, `totalQuantity`, `TotalPrice`, `payDate`, `dueDate`) VALUES (0,0,0,0,0,'0000-00-00','0000-00-00')";

    if (mysqli_query($conn, $query)) {
      $last_id = mysqli_insert_id($conn);
    }

    header("location: addlayaway.php?pid=$last_id&cid=$cid");
    exit();
  } else {

    $query = "INSERT INTO `paymentdetails`(`customerID`,`balance`, `intDeposit`, `totalQuantity`, `TotalPrice`, `payDate`, `dueDate`) VALUES (0,0,0,0,0,'0000-00-00','0000-00-00')";

    if (mysqli_query($conn, $query)) {
      $last_id = mysqli_insert_id($conn);
    }

    header("location: addlayaway.php?pid=$last_id");
    exit();
  }
}






//delete an item from the product list

if (isset($_GET["detID"])) {

  $pid = $_GET['pid'];
  $cid = $_GET['cid'];
  $detID = $_GET["detID"];
  $status = "open";


  include 'connection.php';

  //Querys to subtract the deleted items from the paymentDetails table
  $pdetail = "SELECT * FROM productdetails WHERE detailID = $detID";
  $results = mysqli_query($conn, $pdetail);
  if (mysqli_num_rows($results) > 0) {
    while ($row = mysqli_fetch_assoc($results)) {

      $itemQty = $row['qty'];
      $Price = $row['price'];
      $itemPrice = $itemQty * $Price;
    }
  }

  $paydetail = "SELECT * FROM paymentdetails WHERE paymentID = $pid";
  $payresults = mysqli_query($conn, $paydetail);

  $status = "open";
  $new = mysqli_fetch_assoc($payresults);
  $TotalPrice = $new['TotalPrice'];
  $totalCash = $new['totalCash'];
  $totalCredit = $new['totalCredit'];
  $existingQty = $new['totalQuantity'];

  $newTotalPrice = $TotalPrice - $itemPrice;
  $newQuantity = $existingQty - $itemQty;


  if (isset($_GET['amt'])) {

    $moneyBack = $_GET['amt'];
    $status = "close";
    $balance = 0;
    $newTotalCash = $totalCash - $moneyBack;
    $cashType = "cash";
  } else {

    if ($itemPrice > $totalCash) {

      //message there is not enough cash in customer's drawer, 
      // if $totalCredit > 0,  customer paid with a debit.
      // header location 
      //exit
      // $balance = $newTotalPrice - ($newTotalCash + $totalCredit);

      echo "not enough cash in customer's drawer";
      exit();
    } else {

      //check if if the total cash balance is less than the price of the item being removed.

      if ($newTotalPrice < $totalCash) {

        $newTotalCash = $totalCash - $itemPrice;
        $status = "close";
        $cashType = "cash";
        $moneyBack = ($totalCash - $newTotalPrice) + $totalCredit;
        $balance = 0;
        $Deposit = - ($moneyBack);

        //add negative value to deposits 
        $insertDeposit = "INSERT INTO `deposits` (`paymentID`, `Deposit`, `paymentType`, `status`) VALUES ('$pid', '$Deposit', '$cashtype', '$status')";

        $InitDep = mysqli_query($conn, $insertDeposit);

        header("location: addlayaway.php?pid=$pid&cid=$cid&detID=$detID&amt=$moneyBack&error=delbal");

        exit();
      }

      //check if if the total cash balance is greater than the price of the item being removed.

      if ($newTotalPrice >= $totalCash) {

        $newTotalCash = $totalCash;
        $status = "open";
        $balance = $newTotalPrice - ($totalCash + $totalCredit);
      }
    }
  }



  //delete product
  $del = "DELETE FROM `productdetails` WHERE `productdetails`.`detailID` = $detID";
  $deleteID = mysqli_query($conn, $del);


  //update payment details
  $itemUpdate = "UPDATE `paymentdetails` SET `balance` = '$balance', `totalQuantity`= '$newQuantity',  `TotalPrice` = '$newTotalPrice', `totalCash` = '$newTotalCash', `totalCredit` = '$totalCredit', `status` = '$status' WHERE `paymentdetails`.`paymentID` = $pid";

  $updateQry = mysqli_query($conn, $itemUpdate);

  header("location: addlayaway.php?pid=$pid&cid=$cid");

  exit();
}




if (isset($_GET["Additem"])) {

  include 'connection.php';
  $cid = $_GET["cid"];
  $name = $_GET["name"];
  $email = $_GET["email"];
  $address = $_GET["address"];
  $phone = $_GET["phone"];
  $Quantity = $_GET["quantity"];
  $price = $_GET["price"];
  $pid = $_GET["pid"];
  $product = $_GET["product"];

  if (empty($_GET["product"]) or empty($_GET["price"] or $_GET["quantity"] = NULL)) {

    header("location: addlayaway.php?pid=$pid&cid=$cid&error=missingitem");

    exit();
  } else {


    $updateitems = "SELECT * FROM `paymentdetails` WHERE paymentID = $pid";
    $results = mysqli_query($conn, $updateitems);
    if (mysqli_num_rows($results) > 0) {
      while ($row = mysqli_fetch_assoc($results)) {
        $balance = $row["balance"];
        $TotalPrice = $row['TotalPrice'];
        $existingQty = $row['totalQuantity'];

        $newPrice = $Quantity * $price;
        $updateBalance = $balance + $newPrice;
        $updatePrice = $TotalPrice + $newPrice;
        $updateQuantity = $existingQty + $Quantity;


        $itemUpdate = "UPDATE `paymentdetails` SET `balance` = '$updateBalance', `totalQuantity`= '$updateQuantity',  `TotalPrice` = '$updatePrice', `status` = 'open' WHERE `paymentdetails`.`paymentID` = $pid";

        $updateQry = mysqli_query($conn, $itemUpdate);
      }
    }

    $insert_query = "INSERT INTO `productdetails`(`paymentID`,`productName`, `qty`, `price`) VALUES ('$pid','$product','$Quantity','$price')";

    $LayawaySql = mysqli_query($conn, $insert_query);


    header("location: addlayaway.php?pid=$pid&cid=$cid");

    exit();
  }
}
