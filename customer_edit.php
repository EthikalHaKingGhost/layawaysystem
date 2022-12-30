
<?php

if (isset($_POST["submit"])) {

    $id = $_POST["cid"];
    $updateName = $_POST["name"];
    $updateAddress = $_POST["address"];
    $updateEmail = $_POST["email"];
    $updatePhone = $_POST["phone"];

    include 'connection.php';

    $sql = "SELECT * FROM customerdetails WHERE CID = $id";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {

            $sqlFname = "UPDATE `customerdetails` SET `name` = '$updateName' WHERE `customerdetails`.`CID` = $id ";

            $upd0 = mysqli_query($conn, $sqlFname);

            $sqladd = "UPDATE `customerdetails` SET `address` = '$updateAddress' WHERE `customerdetails`.`CID` = $id ";

            $upd2 = mysqli_query($conn, $sqladd);

            $sqlEmail = "UPDATE `customerdetails` SET `email` = '$updateEmail' WHERE `customerdetails`.`CID` = $id ";

            $upd3 = mysqli_query($conn, $sqlEmail);

            $sqlPhone = "UPDATE `customerdetails` SET `phone` = '$updatePhone' WHERE `customerdetails`.`CID` = $id ";

            $upd4 = mysqli_query($conn, $sqlPhone);


            header("location: customerdetails.php?cid=$id&stat=success");

            exit();
        }
    } else {

        header("location: customerdetails.php?cid=$id&stat=fail");

        exit();
    }
}


if (isset($_POST["addCustomer"])) {
    $addName = $_POST["name"];
    $addAddress = $_POST["address"];
    $addEmail = $_POST["email"];
    $addPhone = $_POST["phone"];

    include 'connection.php';

    //$last_id = $conn->insert_id;
    //check to see if first and last name is already in database

    $sqladd = "SELECT * FROM customerdetails WHERE `customerdetails`.`name` = '$addName'";
    $result = mysqli_query($conn, $sqladd);
    if (mysqli_num_rows($result) > 0) {
        //customer already exist

        header("location: addcustomer.php?error=exist");

        exit();

    } else {

        $sql = "INSERT INTO `customerdetails`(`name`, `address`, `email`, `phone`) VALUES ('$addName','$addAddress','$addEmail','$addPhone')";
        if (mysqli_query($conn, $sql)) {
            $last_id = mysqli_insert_id($conn);
            header("location: layaway_process.php?newLayaway&cid=$last_id");
            exit();
        } else {
            header("location: addcustomer.php?add=fail");
            exit();
        }
    }
} 




if (isset($_GET["add"])) {

    $addName = $_GET["name"];
    $addAddress = $_GET["address"];
    $addEmail = $_GET["email"];
    $addPhone = $_GET["phone"];
	$lid = $_GET["lid"];

    include 'connection.php';

    //$last_id = $conn->insert_id;
    //check to see if first and last name is already in database

    $sqladd = "SELECT * FROM customerdetails WHERE `customerdetails`.`name` = '$addName'";
    $result = mysqli_query($conn, $sqladd);
    if (mysqli_num_rows($result) > 0) {
        //customer already exist

        header("location: addlayaway.php?lid=$lid&exist");

        exit();
    } else {

        $sql = "INSERT INTO `customerdetails`(`name`, `address`, `email`, `phone`) VALUES ('$addName','$addAddress','$addEmail','$addPhone')";

        if (mysqli_query($conn, $sql)) {
          $cid = mysqli_insert_id($conn);
            header("location: addlayaway.php?lid=$lid&cid=$cid&success");
            exit();
        } else {
	  $cid = mysqli_insert_id($conn);
            header("location: addlayaway.php?lid=$lid&cid=$cid&fail");
            exit();
        }
    }
}





?>