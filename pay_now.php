<?php
require('admin/Inc/db_config.php');
require('admin/Inc/essentials.php');

require('Include/Paytm/config_paytm.php');
require('Include/Paytm/encdec_paytm.php');

date_default_timezone_set("Asia/Kolkata");

session_start();

if (!(isset($_SESSION['login']) &&  $_SESSION['login'] == true)) {
    redirect('index.php');
}

if (isset($_POST['pay_now'])) {
    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

$checkSum = "";
$paramList = array();

$ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,99999);

$CUST_ID = $_SESSION['uId'];
$INDUSTRY_TYPE_ID = INDUSTRY_TYPE_ID;
$CHANNEL_ID = CHANNEL_ID;
$TXN_AMOUNT = $_SESSION['room']['payment'];

// Create an array having all required parameters for creating checksum.
$paramList["MID"] = PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $ORDER_ID;
$paramList["CUST_ID"] = $CUST_ID;
$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
$paramList["CHANNEL_ID"] = $CHANNEL_ID;
$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

$paramList["CALLBACK_URL"] = CALLBACK_URL;


$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

//insert payment data

$frm_data= filteration($_POST);

$query1="INSERT INTO `booking_order`( `user_id`, `room_id`, `check_in`, `check_out`,`order_id`)
 VALUES (?,?,?,?,?)";
 
 insert($query1,[$CUST_ID,$_SESSION['room']['id'],$frm_data['checkin'],
 $frm_data['checkout'],$ORDER_ID],'issss');

 $booking_id=mysqli_insert_id($con);

 $query2="INSERT INTO `booking_details`( `booking_id`, `room_name`, `price`, `total_pay`, 
  `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";
  

  insert($query2,[$booking_id,$_SESSION['room']['name'],$_SESSION['room']['price'],$TXN_AMOUNT,
  $frm_data['name'],$frm_data['phonenum'],$frm_data['address']],'issssss');



}


?>


<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<h1>Please do not refresh this page...</h1>
		<form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
		<table border="1">
			<tbody>
			<?php
			foreach($paramList as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>




<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .status-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .success {
            color: #28a745;
        }
        .fail {
            color: #dc3545;
        }
        h1 {
            font-size: 26px;
            margin-bottom: 10px;
        }
        p {
            color: #555;
            font-size: 16px;
        }
        .details {
            margin-top: 30px;
            text-align: left;
            font-size: 14px;
            color: #333;
        }
        .details span {
            display: block;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

<div class="container">
     Change class to 'fail' and text as needed 
<div class="status-icon success">✔️</div>
<h1>Payment Successful</h1>
<p>Thank you! Your payment has been processed successfully.</p>

<div class="details">
    <span><strong>Transaction ID:</strong> TXN123456789</span>
    <span><strong>Amount:</strong>XXXXXXX</span>
    <span><strong>Status:</strong> Success</span>
    <span><strong>Date:</strong> June 28, 2025</span>
</div>
 </div> -->

<!-- </body>
</html> -->