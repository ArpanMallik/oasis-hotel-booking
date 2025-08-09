<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('Include/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> BOOKING STATUS </title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>






</head>

<body class='bg-light'>

  <?php require('Include/header.php'); ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-3 px-4">
        <h2 class="fw-bold h-font">PAYMENT STATUS</h2>
      </div>

      <?php

      $frm_data = filteration($_GET['order']);

      if(!(isset($_SESSION['login']) &&  $_SESSION['login'] == true)) {
       redirect('index.php');
      }
      $booking_q =" SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bd.booking_id=bo.booking_id
      WHERE bo.order_id=? AND bo.user_id=? AND bo.booking_status!=? ";

      $booking_res =select($booking_q,[$frm_data['order'],$_SESSION['uId'],'pending'],'sis');

      if(mysqli_num_rows($booking_res)==0)
      {
        redirect('index.php');
      }

      $booking_fetch=mysqli_fetch_assoc($booking_res);

      if($booking_fetch['trans_status']=="TXN_SUCCESS")
      {
         echo<<<data
          <div class ="col-12 px-4">
           <p class="fw-bold alert alert-success">
           <i class="bi bi-check-circle-fill"></i>
           Payment Done! Booking Successful.
           <br><br>
           <a href='bookings.php'>Go to Bookings</a>
           </p>

          </div>
         data;
      }
      else{
        echo<<<data
          <div class ="col-12 px-4">
           <p class="fw-bold alert alert-danger">
           <i class="bi bi-exclamation-triangle-fill"></i>
           Payment failed! $booking_fetch[trans_resp_msg]
           <br><br>
           <a href='bookings.php'>Go to Bookings</a>
           </p>

          </div>
         data;

      }

      ?>

      


      <?php require('Include/footer.php'); ?>

      


</body>

</html>