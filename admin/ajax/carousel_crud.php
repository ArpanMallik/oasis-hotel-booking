<?php
require('../Inc/db_config.php');
require('../Inc/essentials.php');
adminLogin();



if (isset($_POST['add_image'])) {
  if (!isset($_FILES['picture'])) {
    echo "No file received";
    exit;
  }

  $file = $_FILES['picture'];
  $filename = time() . "_" . basename($file["name"]); // Unique file name
  $target_path = CAROUSEL_FOLDER . $filename;

  // Debugging: Print file details
  echo "<pre>";
  print_r($file);
  echo "</pre>";

  // Validate file type
  $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
  if (!in_array($file['type'], $allowed_types)) {
    echo "inv_img"; // Invalid image format
    exit;
  }

  // Validate file size (Max 2MB)
  if ($file['size'] > 2 * 1024 * 1024) {
    echo "inv_size"; // Image too large
    exit;
  }

  // Move file to target directory
  if (move_uploaded_file($file["tmp_name"], $target_path)) {
    echo "File uploaded successfully: " . $target_path . "\n";
  } else {
    echo "upd_failed"; // File move failed
    exit;
  }

  // Insert into database
  $q = "INSERT INTO `carousel` (`image`) VALUES (?)";
  $stmt = $con->prepare($q);
  $stmt->bind_param("s", $filename);

  if ($stmt->execute()) {
    echo "Database Insert Success!";
  } else {
    echo "Database Insert Failed! MySQL Error: " . $con->error;
  }

  $stmt->close();
}

if (isset($_POST['get_carousel'])) {
  $res = selectAll('carousel');

  while ($row = mysqli_fetch_assoc($res)) {
    $path = CAROUSEL_IMAGE_PATH;
    echo <<<data
       <div class="col-md-4 mb-3">
                                    <div class="card bg-dark text-white">
                                        <img src="$path$row[image]" class="card-img">
                                        <div class="card-img-overlay text-end">
                                            <!----<h5 class="card-title">Card title</h5>---->
                                            <!----<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>---->
                                            <button type="button" onclick="rem_image($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </div>
                                        
                                    </div>
                                </div>
    data;
  }
}

if (isset($_POST['rem_image'])) {
  $frm_data = filteration($_POST);
  $values = [$frm_data['rem_image']];

  //$pre_q = "SELECT * FROM `carousel` WHERE `sr_no`=?";
  //$res=select($pre_q , $values,'i');
  //$img=mysqli_fetch_assoc($res);

  //if(deleteImage($img['image'],CAROUSEL_FOLDER)){
  // $q="DELETE FROM `carousel` WHERE `sr_no`=?";
  //$res=delete($q,$values,'i');
  // echo $res;
  //}
  //else {
  //echo 0;
  //}

  // Fetch the image details from the database
  $pre_q = "SELECT * FROM `carousel` WHERE `sr_no`=?";
  $stmt = mysqli_prepare($con, $pre_q);
  mysqli_stmt_bind_param($stmt, 'i', $values[0]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  if ($res && mysqli_num_rows($res) > 0) {
    $img = mysqli_fetch_assoc($res);

    // Check if the 'image' key exists
    if (!isset($img['image']) || empty($img['image'])) {
      die("Error: Image column is empty or not found in DB.");
    }

    // Correct path formation
    $image_path = CAROUSEL_FOLDER . $img['image'];

    // Debugging Output
    echo "Attempting to delete: " . $image_path . "<br>";

    if (file_exists($image_path)) {
      if (unlink($image_path)) {
        // Delete from database
        $q = "DELETE FROM `carousel` WHERE `sr_no`=?";
        $stmt = mysqli_prepare($con, $q);
        mysqli_stmt_bind_param($stmt, 'i', $values[0]);
        $delete_res = mysqli_stmt_execute($stmt);

        if ($delete_res) {
          exit("1"); // Success
        } else {
          echo "Database delete error: " . mysqli_error($con);
        }
      } else {
        echo "Error: File could not be deleted!";
      }
    } else {
      echo "Error: File does not exist! Path: " . $image_path;
    }
  } else {
    echo "Error: Image not found in database!";
  }

  mysqli_close($con);
}

?>
