<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('Include/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?>-FACILITIES</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <style>
    .pop:hover {
      border-top-color: var(--teal) !important;
      transform: scale(1.03);
      transition: all 0.3s;
    }
  </style>





</head>

<body class='bg-light'>

  <?php require('Include/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
    <p class="text-center mt-3">Discover the epitome of elegance and comfort at Oasis, where every detail is designed to provide an exceptional stay. Our premier facilities include:​
    </p>
  </div>

  <div class="container">
    <div class="row">
      <?php
      $res = selectAll('facilities');
      $path = FACILITIES_IMAGE_PATH;

      while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
        <div class="col-lg-4 col-md-6 mb-5 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
          <div class="d-flex align-items-center mb-2">
          <img src="$path$row[icon]" width="40px">
          <h5 class="m-0 ms-2">$row[name]</h5>
          </div>
          <p>
            $row[description]
          </p>
        </div>
      </div>
      data;
      }
      ?>
      <p class="text-center mt-3">At Oasis, we are committed to providing an unparalleled experience, blending luxury with personalized service to create your perfect sanctuary.​
      </p>
    </div>
  </div>





  <?php require('Include/footer.php'); ?>




</body>

</html>