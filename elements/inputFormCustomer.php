<form action=get method=customer_edit.php>
  <div class="mb-3 form-group">
    <div class=form-row>
      <div class="mb-3 col">
        <label>Name
        </label>
        <input class="form-control form-control-sm"name=name value="<?php  if (isset($_GET["cid"]) && !empty($_GET["cid"])) {                                                                                            echo  $name_find;          } else {           } ?>"required> 
        <small class=text-muted>Enter the customer's full name
        </small>
      </div>
      <div class="mb-3 col">
        <label>Email
        </label>
        <input class="form-control form-control-sm"name=email value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {                                                                                echo  $email_find;  } else {         } ?>"type=email>
      </div>
    </div>
  </div>
  <div class=form-group>
    <div class=form-row>
      <div class="mb-3 col">
        <label>Address
        </label>
        <input class="form-control form-control-sm"name=address value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {                                                                                                    echo  $address_find;                                                        } else {                                                                                                  } ?>">
      </div>
      <div class="mb-3 col">
        <label>Phone
        </label>
        <input class="form-control form-control-sm"name=phone value="<?php if (isset($_GET["cid"]) && !empty($_GET["cid"])) {                                                                                                     echo  $phone_find;                                                                                                     } else {                                                                                                     } ?>"type=tel maxlength=15 oninput='this.value=this.value.replace(/[^0-9.]/g,"").replace(/(\..*)\./g,"$1")'>
      </div>
    </div> 
    <?php
include 'connection.php';
$editQuery = "SELECT * FROM paymentdetails WHERE paymentID = $pid AND customerID = 0";
$editresults = mysqli_query($conn, $editQuery);
if (mysqli_num_rows($editresults) >
0) { } else { ?> 
    <div class=text-center>
      <input class="btn btn-info"name=update value="Edit Customer"type=submit>
    </div> 
    <?php
}
if (isset($_GET["cid"])){
if (empty($_GET["cid"]) || $_GET["cid"] = "" ){
?> 
    <div class=text-center>
      <button class="btn btn-dark"name=add type=submit>Add Customer
      </button>
    </div> 
    <?php
}else{
}
}else{ 
?> 
    <div class=text-center>
      <button class="btn btn-dark"name=add type=submit>Add Customer
      </button>
    </div> 
    <?php
}
?> 
  </div>
</form>
