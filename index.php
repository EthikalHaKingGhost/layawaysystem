<style type="text/css">
    html body {
        background: lightgrey;
    }

    .panel {
        box-shadow: 0 2px 0 rgba(0, 0, 0, 0.05);
        border-radius: 0;
        border: 0;
        margin-bottom: 24px;
    }

    .panel-dark.panel-colorful {
        background-color: #3b4146;
        border-color: #3b4146;
        color: #fff;
    }

    .panel-danger.panel-colorful {
        background-color: #f76c51;
        border-color: #f76c51;
        color: #fff;
    }

    .panel-primary.panel-colorful {
        background-color: #5fa2dd;
        border-color: #5fa2dd;
        color: #fff;
    }

    .panel-info.panel-colorful {
        background-color: #4ebcda;
        border-color: #4ebcda;
        color: #fff;
    }

    .panel-body {
        padding: 25px 20px;
    }

    .panel hr {
        border-color: rgba(0, 0, 0, 0.1);
    }

    .mar-btm {
        margin-bottom: 15px;
    }

    h2,
    .h2 {
        font-size: 28px;
    }

    .small,
    small {
        font-size: 85%;
    }

    .text-sm {
        font-size: .9em;
    }

    .text-thin {
        font-weight: 300;
    }

    .text-semibold {
        font-weight: 600;
    }
</style>


<?php

include 'connection.php';

$sql = "SELECT * FROM customerdetails, layawaydetails WHERE customerdetails.CID = layawaydetails.CID AND customerdetails.CID > 0";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
    }
}

include 'connection.php';

$SumTotal = "SELECT SUM(total) AS amount FROM layawaydetails";
$SumTotalQry = mysqli_query($conn, $SumTotal);
$row = mysqli_fetch_assoc($SumTotalQry);
$totalMoney = $row['amount'];


$SumBalance = "SELECT SUM(balance) AS bal FROM layawaydetails";
$SumBalanceQry = mysqli_query($conn, $SumBalance);
$row = mysqli_fetch_assoc($SumBalanceQry);
$sumbalance = $row['bal'];

$sumMoney = $totalMoney - $sumbalance;


$SumCash = "SELECT SUM(Deposit) AS cash FROM paymentdetails";
$SumCashQry = mysqli_query($conn, $SumCash);
$row = mysqli_fetch_assoc($SumCashQry);
$cashSum = $row['cash'];

$SumQty = "SELECT SUM(qty) AS items FROM productdetails";
$SumQtyQry = mysqli_query($conn, $SumQty);
$row = mysqli_fetch_assoc($SumQtyQry);
$sumqty = $row['items'];


$countPay = "SELECT COUNT(LID) AS counts FROM layawaydetails";
$SumPayQry = mysqli_query($conn, $countPay);
$row = mysqli_fetch_assoc($SumPayQry);
$LaywayN = $row['counts'];

$countCust = "SELECT COUNT(CID) AS customers FROM customerdetails WHERE CID != 0";
$countCustQry = mysqli_query($conn, $countCust);
$row = mysqli_fetch_assoc($countCustQry);
$customerCount = $row['customers'];

$countOpen = "SELECT COUNT(LID) AS stat FROM layawaydetails WHERE `status` = 'open' AND CID != 0";
$countOpenQry = mysqli_query($conn, $countOpen);
$row = mysqli_fetch_assoc($countOpenQry);
$openCount = $row['stat'];

?>  

<?php include 'header.php'; ?>



<div class="container pt-5">
    <div class="row mb-3">
        <div class="col-xl-4 col-sm-6 py-2">
            <div class="card bg-light text-dark h-100">
                <div class="card-body">
                    <div class="rotate">
                        <i class="fa fa-dollar-sign fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase">Total Balance</h6>
                    <h1 class="display-6 text-danger"><?php echo '$' . number_format($sumbalance, 2); ?></h1>
                    <small class="form-text text-muted"><?php echo 'from $' . $totalMoney; ?></small>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 py-2">
            <div class="card text-dark bg-light h-100">
                <div class="card-body">
                    <div class="rotate">
                        <i class="fa fa-cash-register fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase">Deposit in Drawer</h6>
                    <h1 class="display-6"><?php echo '$' . number_format($cashSum, 2); ?></h1>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 py-2">
            <div class="card text-dark bg-light h-100">
                <div class="card-body">
                    <div class="rotate">
                        <i class="fa fa-hand-holding-usd fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase">Open Layways</h6>
                    <h1 class="display-6"><?php echo $openCount; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="container bootstrap snippets bootdey">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="panel panel-dark panel-colorful">
                <div class="panel-body text-center">
                    <p class="text-uppercase mar-btm text-sm">Reports</p>
                    <i class="fa fa-chart-pie fa-5x"></i>
                    <hr>
                    <p class="h2 text-thin"><?php echo '$' . number_format($sumMoney, 2); ?></p>
                    <small><span class="text-semibold"><?php echo $sumqty ?></span> items Credited</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="layawaydetails.php" style="text-decoration: none; color: white;">
                <div class="panel panel-danger panel-colorful">
                    <div class="panel-body text-center">
                        <p class="text-uppercase mar-btm text-sm">View Layaways</p>
                        <i class="fa fa-book fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin"><?php echo $LaywayN; ?></p>
                        <small><span class="text-semibold"><i class="fa fa-book fa-fw"></i></span>Layways</small>
                    </div>
                </div>
        </div>
        </a>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="customers.php" style="text-decoration: none; color: white;">
                <div class="panel panel-primary panel-colorful">
                    <div class="panel-body text-center">
                        <p class="text-uppercase mar-btm text-sm">Customers</p>
                        <i class="fa fa-users fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin"><?php echo $customerCount; ?></p>
                        <small><span class="text-semibold"><i class="fa fa-users fa-fw"></i></span> Customers</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href='<?php echo "layaway_process.php?newLayaway"; ?>' style="text-decoration: none; color: white;">
                <div class="panel panel-info panel-colorful">
                    <div class="panel-body text-center">
                        <p class="text-uppercase mar-btm text-sm">New Layaway</p>
                        <i class="fa fa-dollar-sign fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin">+1</p>
                        <small><span class="text-semibold"><i class="fa fa-dollar-sign fa-fw"></i></span> Click to Add a Layaway</small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


