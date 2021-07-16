            <div class="form-group mb-3">
              <div class="form-row">
                <div class="col mb-3">
                  <label>Name</label>
                  <input type="text" class="form-control form-control-sm" name="name" value='<?php
                                                                                              if (isset($_GET["cid"]) && !empty($_GET["cid"])) {
                                                                                                echo  $name_find;
                                                                                              } else {
                                                                                              } ?>' required>
                  <small class="text-muted">Enter the customer's full name</small>
                </div>

                <div class="col mb-3">
                  <label>Email</label>
                  <input type="email" class="form-control form-control-sm" name="email" value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {
                                                                                                  echo  $email_find;
                                                                                                } else {
                                                                                                } ?>">
                </div>
              </div>
            </div>


            <div class="form-group">
              <div class="form-row">
                <div class="col mb-3">
                  <label>Address</label>
                  <input type="text" class="form-control form-control-sm " name="address" value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {
                                                                                                    echo  $address_find;
                                                                                                  } else {
                                                                                                  } ?>">
                </div>

                <div class="col mb-3">
                  <label>Phone</label>
                  <input type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control form-control-sm" name="phone" maxlength="15" value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {
                                                                                                                                                                                                    echo  $phone_find;
                                                                                                                                                                                                  } else {
                                                                                                                                                                                                  } ?>">
                </div>
              </div>

              <?php
              include 'connection.php';
              $editQuery = "SELECT * FROM paymentdetails WHERE paymentID = $pid AND customerID = 0";
              $editresults = mysqli_query($conn, $editQuery);
              if (mysqli_num_rows($editresults) > 0) {
              } else {
              ?>
                <div class="text-center">
                  <input type="submit" class="btn btn-info" name="update" value="Edit Customer">
                </div>
              <?php
              }
              ?>
            </div>