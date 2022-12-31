<?php include 'header.php';


?>
<style type="text/css">
    html body {
        background: lightgrey;
    }
</style>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from_date">From Date</label>
                                        <input type="date" name="from_date" class="form-control form-control-sm"
                                            placeholder="From Date" value="<?php if (isset($_GET['from_date'])) {
																			echo $_GET['from_date'];
																			} else {																																} ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date" class="form-control form-control-sm"
                                            placeholder="To Date" value="<?php if (isset($_GET['to_date'])) {
																		echo $_GET['to_date'];
																} else {
															} ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="action">Action</label>
                                        <select type="select" name="action" class="form-control form-control-sm"
                                            placeholder="Choose an action">

                                            <option value="" <?php if (empty($_GET['action'])) { echo "selected" ;} ?>
                                                >All</option>
                                            <option value="deposit" <?php if
                                                (isset($_GET['action'])){if($_GET['action']=='deposit' ){
                                                echo "selected" ; }} ?>>deposit</option>
                                            <option value="refund" <?php if
                                                (isset($_GET['action'])){if($_GET['action']=='refund' ){ echo "selected"
                                                ; }} ?>>refund</option>
                                            <option value="drawer" <?php if
                                                (isset($_GET['action'])){if($_GET['action']=='drawer' ) {
                                                echo "selected" ; }} ?>>drawer</option>

                                            } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="action">Payment Method</label>
                                        <select type="select" name="type" class="form-control form-control-sm"
                                            placeholder="Choose an Payment Method">

                                            <option value="" <?php if (empty($_GET['type'])) { echo "selected" ; } ?>
                                                >All</option>
                                            <option value="cash" <?php if (isset($_GET['type'])){
                                                if($_GET['type']=="cash" ){ echo "selected" ; }} ?>>Cash</option>
                                            <option value="credit" <?php if (isset($_GET['type'])){
                                                if($_GET['type']=="credit" ){ echo "selected" ; }} ?>>Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="check">Check</label><br>
                                        <button type="submit" name="check" class="btn-primary">Filter Payments</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="card mt-3">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Deposit</th>
                                    <th>Payment</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                    <th>Date</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php

								include 'connection.php';

								if (isset($_GET['from_date']) && isset($_GET['to_date']) && isset($_GET['action']) && isset($_GET['type'])) {

									$from_date = $_GET['from_date'];
									$to_date = $_GET['to_date'];
									$action = $_GET['action'];
									$paymentType = $_GET['type'];
									$qry = "AND deposits.stat LIKE '$action' AND deposits.paymentType LIKE '$paymentType'";

									if ($_GET['type'] != "" && $_GET['action'] == "") {
										$qry = "AND deposits.paymentType LIKE '$paymentType'";
									}

									if ($_GET['action'] != "" && $_GET['type'] == "") {
										$qry = "AND deposits.stat LIKE '$action'";
									}

									if ($_GET['type'] == "" && $_GET['action'] == ""){
										$qry = "";
									}

								   //total deposits that are closed in the date range to grab the items that are sold
								  echo $sql = "SELECT SUM(Deposit) AS sold FROM deposits, paymentdetails, customerdetails 
								   WHERE paymentdetails.status = 'close' 
								   AND paymentdetails.paymentID = deposits.paymentID 
								   AND paymentdetails.customerID = customerdetails.customerID 
								   AND deposits.dateCreated BETWEEN '$from_date' AND '$to_date' $qry";
								   $depoz = mysqli_query($conn, $sql);
								   $row = mysqli_fetch_assoc($depoz);
								   $itemsSold = $row['sold'];

								   if ($row['sold'] == NULL) {
									$itemsSold  = 0;
								   }

								    //total deposits for range for calculations
								   $totalQuery = "SELECT SUM(Deposit) AS total 
									FROM deposits, paymentdetails, customerdetails 
									WHERE paymentdetails.paymentID = deposits.paymentID 
									AND paymentdetails.customerID = customerdetails.customerID 
									AND deposits.dateCreated BETWEEN '$from_date' AND '$to_date'";
									$depsql = mysqli_query($conn, $totalQuery);
									$row = mysqli_fetch_assoc($depsql);
									$totalDepo = $row['total']; 

								  $query = "SELECT * FROM deposits, paymentdetails, customerdetails 
									WHERE paymentdetails.paymentID = deposits.paymentID 
									AND paymentdetails.customerID = customerdetails.customerID 
									AND deposits.dateCreated BETWEEN '$from_date' 
									AND '$to_date' $qry";

									$result = mysqli_query($conn, $query);
									if (mysqli_num_rows($result) > 0) {
									  while ($row = mysqli_fetch_assoc($result)) {
											$day = $row['dateCreated'];
											
										?>
                                <tr>
                                    <td>
                                        <?php echo $row['depositID']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['Deposit']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['paymentType']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['balance']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['stat']; ?>
                                    </td>
                                    <td>
                                        <?php echo date('m/d/Y g:i a', strtotime($day)); ?>
                                    </td>
                                </tr>

                                <?php

										}
									} else {

										echo 'No Layaways found';
									}

								}
							

								?>


                            </tbody>
                        </table>
                    </div>
                </div>

                <form action="layaway_process.php" method="GET">
                    <div class="card mt-3 ">
                        <div class="card-body">

                            <div class="form-group row ">
                                <div class="col-xs-4 pr-2">
                                    <div>
                                        <input type="text" step="any" class="form-control form-control-sm" name="ttd"
                                            value="<?php if (!empty($totalDepo)) {
											echo $totalDepo;} ?>">
                                        <small>Amount in Drawer</small>
                                    </div>
                                </div>

                                <div class="col-xs-4 pr-2">
                                    <div>
                                        <input type="text" step="any" class="form-control form-control-sm" name="count">

                                        <input type="hidden" name="sold" value="<?php echo $itemsSold; ?>">

                                        <input type="hidden" name="from_date" value="<?php if (isset($_GET['from_date'])) {
																							echo $_GET['from_date'];
																						} ?>">

                                        <input type="hidden" name="to_date" value="<?php if (isset($_GET['to_date'])) {
																						echo $_GET['to_date'];
																					} ?>">

                                        <input type="hidden" name="action" value="<?php if (isset($_GET['action'])) {
																						echo $_GET['action'];
																					} ?>">

                                        <input type="hidden" name="type" value="<?php if (isset($_GET['type'])) {
																					echo $_GET['type'];
																				} ?>">

                                        <small>Amount in Drawer</small>
                                    </div>
                                </div>


                                <div class="wrapper">

                                    <button class="btn-info btn-md" type="submit" name="check">Calculate</button>

                </form>

                <?php if (isset($_GET['count'])) {

					echo '<script type="text/javascript">' . '$( document ).ready(function() {' . '$("#myModal").modal("show");' . '});' . '</script>';
				} ?>

                <div class="modal fade " id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Deposit Amount</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <?php

								$amt  = $_GET['ttd'] + $_GET['count'] - $_GET['sold'];

								$count = $_GET['count'];
								$dep = $_GET['ttd'];
								$type = $_GET['type'];
								$sold = $_GET['sold'];
								?>
                                <div class="row h4 px-5">
                                    <table>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <?php echo $_GET['ttd']; ?>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>+</td>
                                            <td>
                                                <?php echo $_GET['count']; ?>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>-</td>
                                            <td>
                                                <?php echo $_GET['sold']; ?>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td> </td>
                                            <td class="border-top border-dark">
                                                <?php echo round($amt, 2); ?>
                                            </td>
                                            <td class="border-top border-dark"></td>
                                        </tr>
                                    </table>
                                </div>

                                <hr>

                                <div class="text-center row px-5">
                                    <h4><em>
                                            <?php echo 'Please deposit $' . round($amt, 2) . ' in ' . $_GET['type'] . ' payment into the bookstore'; ?>
                                        </em></h4>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-secondary" data-dismiss="modal">Cancel</button>
                                <a
                                    href='<?php echo "layaway_process.php?depo&ttd=$dep&count=$count&sold=$sold&type=$type"; ?>'>
                                    <button type="button" class="btn-danger">Deposit</button></a>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn-danger btn-md" onkeypress="history.go(-1) ">Exit</button>
            </div>
        </div>
    </div>
    </div>
    </div>


    </div>
    </div>
    </div>

</section>

<?php include 'footer.php'; ?>