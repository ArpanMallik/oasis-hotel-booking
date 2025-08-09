<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php

use Mpdf\Tag\Option;

 require('Include/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?>-HOME</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Use Swiper from CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


  <style>
    .swiper-slide img {
      width: 100%;
      /* Adjust to fit the container */
      height: auto;
      /* Keep the aspect ratio */
      max-height: 500px;
      /* Optional: Set a max height */
    }


    .swiper-testimonials .profile img {
      width: 50px;
      /* Adjust this value to control the size */
      height: 50px;
      /* Ensure consistent width and height */
      border-radius: 50%;
      /* Makes the image circular */
      object-fit: cover;
      /* Ensures the image maintains aspect ratio */
    }



    /*.availability-from{-->
   <!-- mergin-top:-50px;
    z-index: 2;
    position: relative;
  }*/
  </style>

</head>

<body class='bg-light'>

  <?php require('Include/header.php'); ?>

  <!-- Use Swiper from CDN Carousel -->
  <div class="containter-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">

          <!-- 
          // $res = selectAll('carousel');
          // while ($row = mysqli_fetch_assoc($res)) {
          //$path = CAROUSEL_IMAGE_PATH;
          //echo <<< data
          //<div class="swiper-slide">
          // <img src="$path$row[image]" />
          //</div>
          //data;
          // }
           -->
          <img src="Images\carousel\img_1.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745680159_carousel 10.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745680546_carousel 12.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745652327_carousel 4.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745652239_carousel 3.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1742058790_gettyimages-74902820-612x612.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745652339_carousel 5.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745679735_Golden 4.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745679756_Golden 3.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745680148_carousel 9.jpg" />
        </div>
        <div class="swiper-slide">
          <img src="Images\carousel\1745681409_carousel 13.jpg" />
        </div>

      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  <!-- Check Availability -->

  <div class="container">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded">
        <h5 class="mb-4"> Check booking Availability</h5>
        <form action="rooms.php">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight:500;">Check-in</label>
              <input type="date" class="form-control shadow-none" name="checkin" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight:500;">Check-out</label>
              <input type="date" class="form-control shadow-none" name="checkout" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight:500;">Adult</label>
              <select class="form-select shadow-none" name="adult">
                <?php
                    $guests_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`,
                     MAX(children) AS `max_children` FROM `rooms`WHERE
                     `status`='1'AND `removed`='0'");
                     $guests_res=mysqli_fetch_assoc($guests_q);

                     for($i=1;$i<=$guests_res['max_adult'];$i++){
                       echo"<option value='$i'>$i</option>";
                     }

                ?>
                
              </select>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight:500;">Child</label>
              <select class="form-select shadow-none" name="children">
                <?php
                   for($i=1;$i<=$guests_res['max_children'];$i++){
                       echo"<option value='$i'>$i</option>";
                     }
                ?>
                
              </select>
            </div>
              <input type="hidden" name="check_availabIlity">
            <div class="col-lg-1 mb-lg-2 mb-3">
              <button type="submit" class="btn text-white shadow-none  custom-bg">Submit</button>

            </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Our Rooms Card-->
  <h2 class="mt-5  pt-4 mb-4 text-center fw-bold h-font">OUR ROOMS </h2>

  <div class="container">
    <div class="row">

      <?php
      $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=?  ORDER BY `id` DESC LIMIT 3 ", [1, 0], 'ii');

      while ($room_data = mysqli_fetch_assoc($room_res)) {

        // get features of room 

        $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f
          INNER JOIN `rooms_features` rfea ON f.id=rfea.features_id
          WHERE rfea.room_id='$room_data[id]' ");


        $features_data = "";
        while ($fea_row = mysqli_fetch_assoc($fea_q)) {
          $features_data .= "<span class='badge rounded-pill bg-light text-dark  text -wrap lh-base me-1 mb-1'>
                  $fea_row[name]
                </span>";
        }


        // get facilities of room

        $fac_q = mysqli_query($con, " SELECT f.name FROM `facilities` f
           INNER JOIN `rooms_facilites` rfac ON f.id = rfac.facilites_id 
           WHERE rfac.rooms_id = '$room_data[id]'");

        $facilities_data = "";

        while ($fac_row = mysqli_fetch_assoc($fac_q)) {
          $facilities_data .= "<span class='badge rounded-pill bg-light text-dark  text -wrap lh-base me-1 mb-1'>
                   $fac_row[name]
                 </span>";
        }

        // get thumbnail of Image

        $room_thumb = ROOMS_IMAGE_PATH . "thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM `room_image`
           WHERE `room_id`='$room_data[id]'
           AND `thumb`='1'");

        if (mysqli_num_rows($thumb_q) > 0) {
          $thumb_res = mysqli_fetch_assoc($thumb_q);
          $room_thumb = ROOMS_IMAGE_PATH . $thumb_res['image'];
        }

        $book_btn = "";

        if (!$settings_r['shutdown'] == 1) {
          $login = 0;
          if (isset($_SESSION['login']) &&  $_SESSION['login'] == true) {
            $login = 1;
          }
          $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Book now</button>";
        }

         $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
         WHERE `room_id` ='$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20 ";

         $rating_res = mysqli_query($con,$rating_q);
         $rating_fetch = mysqli_fetch_assoc($rating_res);
     
         $rating_data="";

         if($rating_fetch['avg_rating']!=NULL)
          {
            $rating_data="<div class='Ratings mb-4'>
                            <h6 class='mb-1'>Ratings</h6>
                              <span class='badge rounded-pill bg-light text-dark  text -wrap lh-base'>";

                               for($i=0;$i<$rating_fetch['avg_rating'];$i++)
               {
                 $rating_data .= " <i class='bi bi-star-fill text-warning'></i>";
               }
               $rating_data .="  </span>

            </div>";
          }
         

        //print room card

        echo <<<data
         <div class="col-lg-4 col-md-6 my-3">

        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
          <img src="$room_thumb" class="card-img-top">

          <div class="card-body">
            <h5> $room_data[name]</h5>
            <h6 class="mb-4"> ₹ $room_data[price]</h6>
            <div class="features mb-4">
              <h6 class="mb-1">Features</h6>
             $features_data
            </div>

            <div class="facilities mb-4">
              <h6 class="mb-1">Facilites</h6>
              $facilities_data
            </div>

            <div class="guests mb-4">
              <h6 class="mb-1">Guests</h6>
              <span class="badge rounded-pill bg-light text-dark  text -wrap lh-base">
                 $room_data[adult] Adults 
              </span>
              <span class="badge rounded-pill bg-light text-dark  text -wrap lh-base">
                 $room_data[children] Childrens
              </span>
            </div>

            $rating_data
            <p class="card-text">You can enjoy here some beautiful views from balcony and spends some beautiful moment with your friends.</p>
            <div class="d-flex justify-content-evenly mb-2">
                  $book_btn
              <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
            </div>
          </div>
        </div>
      </div> 
                
      data;
      }

      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms</a>
      </div>
    </div>
  </div>

  <!--Our Facilites-->
  <h2 class="mt-5  pt-4 mb-4 text-center fw-bold h-font">OUR FACILITIES </h2>

  <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
      <?php
      $res = mysqli_query($con, "SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
      $path = FACILITIES_IMAGE_PATH;

      while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
          <img src="$path$row[icon]" width="80px">
          <h5 class="mt-3">$row[name]</h5>
        </div>
      data;
      }
      ?>
      <div class="col-lg-12 text-center mt-5">
        <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities</a>
      </div>

    </div>
  </div>

  <!--Our Testimonials-->
  <h2 class="mt-5  pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS </h2>
  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php

                    $review_q = "SELECT rr.*,uc.name AS uname, uc.profile, r.name AS rname FROM `rating_review` rr
                     INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                     INNER JOIN `rooms` r ON rr.room_id = r.id
                     ORDER BY `sr_no` DESC LIMIT 6";

         $review_res=mysqli_query($con,$review_q);
         $img_path=USERS_IMAGE_PATH;

         if(mysqli_num_rows($review_res)==0)
         {
           echo 'No review yet!';
         }
         else{
             while($row=mysqli_fetch_assoc($review_res))
             {
               $stars="<i class='bi bi-star-fill text-warning'></i> ";
               for($i=1;$i<$row['rating'];$i++)
               {
                $stars .= " <i class='bi bi-star-fill text-warning'></i>";
               }

              echo<<<slides
               <div class="swiper-slide bg-white p-4 rounded shadow-sm">
                  <div class="profile d-flex align-items-center p-4">
                     <img src=" $img_path$row[profile] " width="30px">
                     <h6 class="m-0 ms-2"> $row[uname] </h6>
                  </div>
                <p class="mb-2" style="font-size: 0.95rem;">
                    $row[review]
                </p>
             <div class="rating">
               $stars

             </div>
           </div>

           slides;
             }
         }

        ?>
        
       
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More</a>
    </div>
  </div>


  <!--Reach us-->



  <h2 class="mt-5  pt-4 mb-4 text-center fw-bold h-font">REACH US </h2>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" height="450px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652225.8017124156!2d73.8783471!3d26.62840795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396a3efaf7e30e37%3A0xb52b9b4506c088e5!2sRajasthan!5e0!3m2!1sen!2sin!4v1729620650738!5m2!1sen!2sin" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5>Call us</h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?> " class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
          </a>
          <br>
          <?php
          if ($contact_r['pn2'] != '') {
            echo <<<data
               <a href="tel:+{$contact_r['pn2']}" class="d-inline-block mb-2 text-decoration-none text-dark">
               <i class="bi bi-telephone-fill"></i> +{$contact_r['pn2']}
              </a>
             data;
          }
          ?>


        </div>

        <div class="bg-white p-4 rounded mb-4">
          <h5>Mail us</h5>
          <a href="Mail:<?php echo $contact_r['email'] ?> " class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-envelope-at-fill"></i> Mail:<?php echo $contact_r['email'] ?>

        </div>

        <div class="bg-white p-4 rounded mb-4 ">
          <h5>Follow us</h5>
          <div>
            <?php
            if ($contact_r['tw'] != '') {
              echo <<<data
             <a href="$contact_r[tw]" class="d-inline-block mb-3">
               <span class="badge bg-light text-dark fs-6 p-2">
               <i class="bi bi-twitter-x me-1"></i> Twitter
               </span>
             </a>
             <br>
           data;
            }
            ?>
          </div>

          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
            <span class=" badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-facebook me-1"></i>Facebook
            </span>
          </a>
          <br>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3">
            <span class=" badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-instagram me-1"></i>Instagram
            </span>
          </a>

        </div>


      </div>
    </div>
  </div>

  <?php require('Include/footer.php'); ?>



  <br><br><br>
  <br><br><br>



  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });

    var swiper = new Swiper(".swiper-testimonials", {
      
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      //slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 1,
        },
      }
    });
  </script>
</body>

</html>