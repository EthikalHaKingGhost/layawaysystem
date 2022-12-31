<?php include 'header.php'; ?>
<body style="background-color: lightgrey;">
<h1 class="display-4 py-5 text-center font-weight-bold">Add Customer</h1>

<div class="container bg-light p-5 rounded shadow">

  <form method="post" action="customer_edit.php">

    <div class="form-group">
      <label for="name">Full Name</label>
      <input type="text" name="name" class="form-control" required>
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

      <input type="submit" name="addCustomer" class="btn btn-success" value="Add Layaway">
      <a href="customers.php" title="Show all Customers" class="btn btn-primary">Existing Customers</a>
      <a href="index.php" title="Go to home page" class="btn btn-danger">Close</a>

    </div>

  </form>
</div>
</body>

<?php include 'footer.php'; ?>