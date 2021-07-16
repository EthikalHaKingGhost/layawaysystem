                <label for="textarea_id_13">Product Name</label>
                <textarea class="form-control mb-3" rows="2" name="product" id="textarea_id_13"></textarea>

                <div class="form-group">
                  <div class="form-row">
                    <div class="col">
                      <label for="input_id_20">Quantity</label>
                      <input type="number" class="form-control form-control-sm mb-3" min="1" name="quantity" id="input_id_20" />
                    </div>
                    <div class="col">
                      <label>Cost of Item</label>
                      <input type="number" class="form-control form-control-sm mb-3" min="1" step="any" name="price" />
                    </div>
                  </div>
                </div>

                <div class="text-center pb-2">
                  <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                  <input type="hidden" name="cid" value='<?php if (isset($_GET["cid"])) {
                                                            $cid = $_GET['cid'];
                                                            echo $cid;
                                                          } ?>'>

                  <input type="submit" class="btn btn-dark" name="Additem" value="Add Item">

                  <?php

                  if (isset($_GET['error'])) {
                    if ($_GET['error'] == "missingitem") {
                      echo '<h4 class="text-center pt-2 text-danger">Please enter all product information!</h4>';
                    }
                  }

                  if (isset($_GET['error'])) {
                    if ($_GET['error'] == "noitems") {
                      echo '<h4 class="text-center pt-2 text-danger">please click here to add an item to the list!</h4>';
                    }
                  }

                  ?>

                </div>