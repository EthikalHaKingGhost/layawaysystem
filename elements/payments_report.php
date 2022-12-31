<?php 
$startDateMessage = '';
$endDate = '';
$noResult = '';

$query = "SELECT paymentdetails.PID,layawaydetails.LID,Layawaydetails.CID,customerdetails.name,customerdetails.email,customerdetails.phone,paymentdetails.Deposit as Deposits,paymentdetails.datePaid,paymentdetails.balance,layawaydetails.total,layawaydetails.status 
FROM customerdetails,paymentdetails,layawaydetails 
WHERE layawaydetails.LID = paymentdetails.LID AND layawaydetails.CID = customerdetails.CID ORDER By paymentdetails.PID ASC";

$results = mysqli_query($conn, $query);
$allData = array();

if (mysqli_num_rows($results) > 0) {
  // output data of each row
  while ($Data = mysqli_fetch_assoc($results)) {
    $allData[] = $Data;
}


if(isset($_POST['export'])){

    if(isset($_POST['search']) && !empty($_POST['search'])){
        $search = $_POST['search'];
        $lookup = "AND customerdetails.name LIKE '%".$_POST['search']."%'";
    }else{
        $search = '';
        $lookup = '';
    }

    if(empty($_POST['fromDate'])){
        $startDateMessage = '<label class="text-danger">Select start date.</label>';
    }else if(empty($_POST['toDate'])){
        $endDate = '<label class="text-danger">Select an end date.</label>';
    }else{
        $dateQuery = "
        SELECT paymentdetails.PID,layawaydetails.LID,Layawaydetails.CID,customerdetails.name,customerdetails.email,customerdetails.phone,paymentdetails.Deposit as Deposits,paymentdetails.datePaid,paymentdetails.balance,layawaydetails.total,layawaydetails.status 
        FROM customerdetails,paymentdetails,layawaydetails 
        WHERE layawaydetails.LID = paymentdetails.LID AND layawaydetails.CID = customerdetails.CID $lookup AND datePaid >= '".$_POST["fromDate"]."' AND datePaid <= '".$_POST["toDate"]."' ORDER BY datePaid DESC";
        $dateresults = mysqli_query($conn, $dateQuery);
        $filterData = array();
            while ($Data = mysqli_fetch_assoc($dateresults)) {
              $filterData[] = $Data;
            }
   
            if(count($filterData)) {
                $fileName = "report_export_".date('Ymd') . ".csv";
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$fileName");
                header("Content-Type: application/csv;");
                $file = fopen('php://output', 'w');
                $header = array("Payment_id","Layaway_id", "Name", "email", "phone", "Deposits", "balance","total","status","Date Started");
                fputcsv($file, $header);  
                foreach($filterData as $Data) {
                $resultData = array();
                $resultData[] = $Data["PID"];
                $resultData[] = $Data["LID"];
                $resultData[] = $Data["name"];
                $resultData[] = $Data["email"];
                $resultData[] = $Data["phone"];
                $resultData[] = $Data["Deposits"];
                $resultData[] = $Data["balance"];
                $resultData[] = $Data["total"];
                $resultData[] = $Data["status"];
                $resultData[] = $Data["datePaid"];
                fputcsv($file, $resultData);
                }
                fclose($file);
                exit;
            } else {

            $noResult = '<label class="text-danger">No records exist with the date range or Name to export. Please choose different date range.</label>';  
            
}

}
}
}