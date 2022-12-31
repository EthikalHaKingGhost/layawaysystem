<?php  
require 'connection.php';
require 'elements/payments_report.php';
require_once 'header.php';
?>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<script src="js/datepickers.js"></script>
<style>
    .input-daterange input {
        text-align: left;
    }

    html body {
        background: lightgrey;
    }

    .tableFixHead {
        overflow: auto;
        height: 600px;
    }

    .tableFixHead thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
</style>

<h1 class="display-4 py-5 text-center font-weight-bold">Payment Report</h1>

<div class="container">
    <form method="post">
        <div class="row mb-3">
            <div class="input-daterange col-md-8 row">
                <div class="col-md-6">
                    From<input type="text" name="fromDate" class="form-control" value="<?php echo date(" Y-m-d"); ?>"
                    readonly />
                    <?php echo $startDateMessage; ?>
                </div>

                <div class="col-md-6">
                    To<input type="text" name="toDate" class="form-control" value="<?php echo date(" Y-m-d"); ?>"
                    readonly />
                    <?php echo $endDate; ?>
                </div>
            </div>

            <div class="col-md-2">
                <div>&nbsp;</div>
                <input type="submit" name="export" value="Export to CSV" class="btn btn-info" />
            </div>
        </div>


        <div class="row">
            <div class="col-md-8">
                <?php echo $noResult;?>
            </div>
        </div>

        <div class="form-row align-items-end">
            <div class="form-group col">
                <input class="form-control" id="myInput" name="search" type="text"
                    placeholder="Type to search for Customer...">
            </div>
        </div>
    </form>

    <div class="tableFixHead">
        <table class="table table-striped table-light">
            <thead>
                <tr class="text-center">
                    <th>ID#</th>
                    <th>LID#</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>Deposit</th>
                    <th>balance</th>
                    <th>total</th>
                    <th>status</th>
                    <th>Date Paid</th>
                </tr>
            </thead>
            <tbody id="myTable">
                <?php foreach($allData as $Data){

            $balance = $Data["total"] - $Data["Deposits"];

             echo 
                '<tr class="text-center">
                    <td>'.$Data['PID'].'</td> 
                    <td>'.$Data['LID'].'</td>
                    <td>'.$Data['name'].'</td>
                    <td>'.$Data['email'].'</td>
                    <td>'.$Data['phone'].'</td>
                    <td>'.$Data['Deposits'].'</td>
                    <td>'.$Data['balance'].'</td>
                    <td>'.$Data['total'].'</td>
                    <td>'.$Data['status'].'</td>
                    <td>'.$Data['datePaid'].'</td>
                </tr>';
              } ?>

            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    <?php include 'footer.php'; ?>