<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('Include/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?>-ABOUT US</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <style>
    .about-image {
      width: 100%;
      height: auto;
      /* Ensures the image maintains its aspect ratio */
      margin-top: 20px;
      /* Adds space above the image */
    }

    .box {
      border-top-color: var(--teal) !important;
    }

    .pop:hover {
      /*border-top-color:var(--teal) !important;*/
      transform: scale(1.03);
      transition: all 0.3s;
    }
  </style>





</head>

<body class='bg-light'>

  <?php require('Include/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">ABOUT US</h2>
    <p class="text-center mt-3">"Escape. Relax. Experience. Welcome to Oasis Hotel – Your perfect haven of comfort & luxury! ❤️✨"</p>
  </div>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h3 class="mb-3">A Heartfelt Message from the Founder 💌</h3>
        <p class="text-justify">
          At <strong>Oasis Hotel</strong>, we believe that hospitality is more than just service—it’s about creating <em>unforgettable experiences</em>. Every corner of our hotel is designed with love, ensuring comfort, luxury, and a touch of home. Whether you’re here for business, leisure, or a special occasion, our team is dedicated to making your stay truly exceptional.
        </p>
        <p class="text-justify">
          We’re not just offering a place to stay; we’re offering a place where <strong>memories are made</strong>. Welcome to your <em>home away from home!</em> ❤️
        </p>
        <p class="text-end">
          <strong>– Arpan, Founder, Oasis Hotel</strong> 🍁
        </p>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="images/About/A1.jpeg" class="about-image">
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4  text-center box pop">
          <img src="images/About/hotel-svgrepo-com.svg" width="70px">
          <h4 class="mt-3">100+ Rooms</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box pop">
          <img src="images/About/users.svg" width="70px">
          <h4 class="mt-3">250+ Customers</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4 pop">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box pop">
          <img src="images/About/customer-evaluation-review.svg" width="70px">
          <h4 class="mt-3">150+ Reviews</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box pop">
          <img src="images/About/customer-service.svg" width="70px">
          <h4 class="mt-3">200+ Staffs</h4>
        </div>
      </div>
    </div>
  </div>

  <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>
  <div class="container px-4">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper mb-5">
        <?php
        $about_r = selectAll('team_details');
        $path = ABOUT_IMAGE_PATH;
        if (!$about_r) {
          die("Query failed or returned no results.");
        }

        while ($row = mysqli_fetch_assoc($about_r)) {

          echo <<<data
         <div class="swiper-slide bg-white text-center overflow-hidden rounded">
            <img src="$path$row[picture]" class="about-image">
            <h5 class="mt-2">$row[name]</h5>
        </div>

        data;
        }
        ?>



      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>





  <?php require('Include/footer.php'); ?>


  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 4,
      spaceBetween: 40,
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },

    });
  </script>




</body>

</html>