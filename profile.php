<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  require('Include/links.php');
  ?>

  <title><?php echo $settings_r['site_title'] ?>-PROFILE</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>






</head>

<body class='bg-light'>

  <?php require('Include/header.php');
  if (!(isset($_SESSION['login']) &&  $_SESSION['login'] == true)) {
    redirect('index.php');
  }

  $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], 's');

  if (mysqli_num_rows($u_exist) == 0) {
    redirect('index.php');
  }
  $u_fetch = mysqli_fetch_assoc($u_exist);
  ?>







  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold h-font">PROFILE</h2>
        <div style="font: size 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">PROFILE</a>
        </div>
      </div>

      <div class="col-12 mb-5 mb-4 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
          <form id="info-form">
            <h5 class="mb-3 fw-bold">Basic Information</h5>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Name</label>
                <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Phone Number</label>
                <input name="phnum" type="number" value="<?php echo $u_fetch['phnum'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Date of birth</label>
                <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Pincode</label>
                <input name="pincode" type="number" value="<?php echo $u_fetch['pincode'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-8 mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $u_fetch['address'] ?></textarea>
              </div>
            </div>
            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
          </form>

        </div>
      </div>
    </div>
  </div>

<div class="row justify-content-center">
   <div class="col-md-6 mb-5 mb-4 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
          <form id="profile-form">
            <h5 class="mb-3 fw-bold">Profile Picture</h5>
            <img src="<?php echo USERS_IMAGE_PATH.$u_fetch['profile']?>" class="img-fluid mb-3">

             <label class="form-label">New Profile Picture</label>
              <input  name="profile" type="file" accept=".jpg,.jpeg,.png,.webp" class="form-control shadow-none"required>
            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>



  <?php require('Include/footer.php'); ?>

  <script>
    let info_form = document.getElementById('info-form');

      info_form.addEventListener('submit', function(e) {
      e.preventDefault();

      let data = new FormData();
      data.append('info_form', '');
      data.append('name', info_form.elements['name'].value);
      data.append('phnum', info_form.elements['phnum'].value);
      data.append('dob', info_form.elements['dob'].value);
      data.append('pincode', info_form.elements['pincode'].value);
      data.append('address', info_form.elements['address'].value);

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/profile.php", true);
      

      xhr.onload = function() {
       if (this.responseText == 'phone_already') {
        alert('error', "Phone Number is already Registered!");
       }
        else if (this.responseText == 0) {
          alert('error', "No Changres Made!");
      }
      else {
        alert('success',"Changes Made!");
      }
     }
    
      xhr.send(data);
    });

    let profile_form =document.getElementById('profile-form');

    

      profile_form.addEventListener('submit', function(e) {
      e.preventDefault();

      let data = new FormData();
      data.append('profile_form', '');
      data.append('profile', profile_form.elements['profile'].files[0]);
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/profile.php", true);
      

      xhr.onload = function() {
       if (this.responseText == 'inv_img') {
        alert('error', "Only JPEG,WEBP & PNG images are allowed!");
       } else if (this.responseText == 'upd_failed') {
        alert('error', "Image Upload Failed!");
       }
        else if (this.responseText == 0) {
        alert('error', "No Changes made!");
       }
     else {
        window.location.href=window.location.pathname;
     }
      }
      xhr.send(data);
    
  });

  </script>




</body>

</html>