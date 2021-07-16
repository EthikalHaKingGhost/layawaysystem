       <div class="text-center my-3">

         <a class="btn btn-danger btn-md" onclick="history.go(-1);">Back </a>

         <input type="hidden" name="pid" value="<?php echo $pid; ?>" required>
         <input type="hidden" name="cid" value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {
                                                  $cid = $_GET['cid'];
                                                  echo $cid;
                                                } else {
                                                } ?>" required>

         <?php
          //change button to update if layaway exist
          include 'connection.php';
          $buttonQRY = "SELECT * FROM `paymentdetails` WHERE paymentID = $pid AND `customerID` > 0";
          $result = mysqli_query($conn, $buttonQRY);
          if (mysqli_num_rows($result) > 0) {

          ?>

           <input type="submit" class="btn btn-primary btn-md" name="addLayaway" value="Update Layaway">

           <a type="button" class="btn btn-warning" data-toggle="modal" data-target="#staticBackdrop">
             Delete Layaway
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
                    $amountqry = "SELECT SUM(Deposit) AS totalDep FROM deposits WHERE paymentID = $pid";

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
                   <a href="<?php echo "layaway_process.php?pid=$pid&cid=$cid&del_lay" ?>"><button type="button" class="btn btn-danger">Delete</button></a>
                 </div>
               </div>
             </div>
           </div>


         <?php
          } else {
          ?>

           <input type="submit" class="btn btn-info btn-md " name="addLayaway" value="Add Layaway">

         <?php
          }
          ?>

       </div>
       </form>