<?php


include 'header.php';

?>

<h1 class="display-4 py-4 text-center">Add Customer</h1>

<div class="container">

  <form method="post" action="customer_edit.php">

    <div class="form-group">
      <label for="name">Full Name</label>
      <input type="text" name="name" class="form-control">
    </div>

    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" name="address" class="form-control">
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" class="form-control">
    </div>

    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="phone" class="form-control">
    </div>

    <div>

      <input type="submit" name="addCustomer" class="btn btn-primary" value="Add">
      <a href="customers.php" title="back" class="btn btn-danger">Close</a>

    </div>

  </form>
</div>

<?php include 'footer.php'; ?>