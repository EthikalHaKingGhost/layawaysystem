<?php
if (isset($_GET['error'])) {

  if ($_GET['error'] == "delbal") {

    $detID = $_GET['detID'];
    $amt = $_GET['amt'];

    echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#delBalance').modal('show');
            });
            </script>";
  }
}
?>
<table class="table table-bordered bg-secondary text-light">
  <thead>
    <tr>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Price($TTD)</th>
      <th>Action</th>
    </tr>
  </thead>

  <?php

  include 'connection.php';

  $sql1 = "SELECT * FROM layawaydetails, productdetails WHERE layawaydetails.LID = productdetails.LID AND productdetails.LID = $lid";

  $resultsql1 = mysqli_query($conn, $sql1);

  if (mysqli_num_rows($resultsql1) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($resultsql1)) {
      $productName = $row["product"];
      $qty = $row["qty"];
      $price = $row["price"];
      $PDID = $row["PDID"];

  ?>
      <tbody class="bg-white text-dark">
        <tr>
          <td><?php echo $productName; ?></td>
          <td><?php echo $qty; ?></td>
          <td><?php echo $price; ?></td>
          <td>

            <a href='<?php echo "layaway_process.php?delProduct&lid=$lid&PDID=$PDID&cid=$cid" ?>' title="delete product" class="btn btn-danger btn-sm">Remove</a>
          </td>
        </tr>
      </tbody>

  <?php
    }
  }

  ?>

</table>


<div class="modal fade" id="delBalance">
  <div class="modal-dialog ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-dark text-light border-0">
        <h4 class="modal-title">Cash Over</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body bg-dark text-light ">

        <img src="img/register.png" width="100" class="rounded mx-auto d-block" alt="cash register">

        <h5 class='text-justify py-3'> Customer has <b>$<?php echo $amt ?></b> extra in the drawer, would you like to refund the customer?...</h5>

        <em class="text-warning">Please withdraw the cash from the drawer and click continue to remove the item, else click cancel to stop the transaction.</em>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer bg-dark text-light border-0">

        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>

        <a type="button" class="btn btn-success btn-sm" href="<?php echo "layaway_process.php?pid=$pid&cid=$cid&amt=$amt&detID=$detID"; ?>">Continue</a>
      </div>

    </div>
  </div>
</div>