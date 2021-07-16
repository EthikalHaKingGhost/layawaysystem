<div class="form-group">
  <div class="form-row">
    <?php
    if (isset($_GET['cid'])) {

      $cid = $_GET['cid'];

      //populate field values with data
      $updatefields = "SELECT * FROM `paymentdetails` WHERE paymentID = $pid";
      $updatefieldsqry = mysqli_query($conn, $updatefields);


      if (mysqli_num_rows($updatefieldsqry) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($updatefieldsqry)) {

          $InitDeposit = $row['intDeposit'];
          $DueDate = $row['dueDate'];
    ?>

          <div class="col mb-3">
            <label>Initial Deposit</label>
            <input type="number" class="form-control form-control-sm " min="1" step="any" name="initDeposit" value="<?php echo $InitDeposit; ?>" required>
            <small class="text-muted">Change customer deposit</small>
          </div>

          <div class="col mb-3">
            <label>Payment Type</label>
            <select type="select" class="form-control form-control-sm " name="typ" required>
              <option value="cash">Cash</option>
              <option value="credit">Debit/Credit</option>
            </select>
          </div>


          <div class="col mb-3">
            <label>Due Date</label>
            <input type="Date" class="form-control form-control-sm " name="dueDate" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $DueDate; ?>">
          </div>


        <?php
        }
      } else {
        ?>

        <div class="col mb-3">
          <label>Initial Deposit</label>
          <input type="number" class="form-control form-control-sm" min="0.01" step="any" name="initDeposit" placeholder="0.00" required>
          <small class="text-muted">Add customer deposit</small>
        </div>

        <div class="col mb-3">
          <label>Due Date</label>
          <input type="Date" class="form-control form-control-sm " name="dueDate" min="<?php echo date("Y-m-d"); ?>">
        </div>


    <?php
      }
    } else {

      echo "
  <h4 class='text-success'>Please add a customer</h4>
  ";
    }
    ?>

  </div>
</div>