<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('Include/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?>-CONTACT US</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>






</head>

<body class='bg-light'>

  <?php require('Include/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">CONTACT US</h2>
    <p class="text-center mt-3">We’d love to hear from you! Whether you have a question, need assistance, or just want to say hello, we're here for you.</p>
    <p class="text-center mt-3">✨ Your comfort is our priority! ✨</p>
  </div>




  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 mb-5 px-4">

        <div class="bg-white rounded shadow p-4 ">
          <iframe class="w-100 rounded mb-4" height="450px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652225.8017124156!2d73.8783471!3d26.62840795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396a3efaf7e30e37%3A0xb52b9b4506c088e5!2sRajasthan!5e0!3m2!1sen!2sin!4v1729620650738!5m2!1sen!2sin" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          <h5>Address</h5>
          <a href="https://maps.app.goo.gl/1qEs6TrfjCSbDerQA" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2 ">
            <i class="bi bi-geo-alt-fill"></i>XYZ,Jaisalmer,Rajasthan
          </a>

          <h5 class="mt-4">Call us</h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
          </a>
          <br>
          <?php
          if ($contact_r['pn2'] != '') {
            echo <<<data
             <a href="tel: +$contact_r[pn2] " class="d-inline-block mb-2 text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
             </a>
            data;
          } ?>



          <h5 class="mt-4">Mail us</h5>
          <a href="mailto:<?php echo $contact_r['email'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-envelope-at-fill"></i> <?php echo $contact_r['email'] ?>
          </a>

          <h5 class="mt-4">Follow us</h5>
          <?php
          if ($contact_r['tw'] != '') {
            echo <<<data
            <a href="$contact_r[tw] " class="d-inline-block  text-dark fs-6 me-2">
             <i class="bi bi-twitter-x me-1"></i>
            </a>
            data;
          } ?>
          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block  text-dark fs-6 me-2 ">
            <i class="bi bi-facebook me-1"></i>
          </a>
          <a href="<?php echo $contact_r['insta'] ?> " class="d-inline-block  text-dark fs-6 me">
            <i class="bi bi-instagram me-1"></i>

          </a>

        </div>
      </div>




      <div class="col-lg-6 col-md-6 px-4">
        <div class="bg-white rounded shadow p-4 ">
          <form method="POST">
            <h5>Fill out the form below, and we’ll get back to you in a heartbeat! ❤️</h5>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500">Name</label>
              <input name="name" required type="text" class="form-control shadow-none">
            </div>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500">Email</label>
              <input name="email" required type="email" class="form-control shadow-none">
            </div>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500">Subject</label>
              <input name="subject" required type="text" class="form-control shadow-none">
            </div>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500">Message</label>
              <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none"></textarea>
            </div>
            <button type="Submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>
          </form>

        </div>
      </div>



    </div>
  </div>

  <?php

   if(isset($_POST['send']))
   {
    $frm_data=filteration($_POST);
    $q = "INSERT INTO `user_queries`( `name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
    $values=[$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];
    $res=insert($q,$values,'ssss');
    if($res==1){
      alert('success','Mail sent!');
    }
    else{
      alert('error','Server Down! try again later.');
    }
   }
  ?>



  <?php require('Include/footer.php'); ?>




</body>

</html>