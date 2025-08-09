<?php

require('../Inc/db_config.php');
require('../Inc/essentials.php');

header('Content-Type: application/json');
adminLogin();
$response = []; // Initialize response array

if (isset($_POST['add_room'])) {
  $features = filteration(json_decode($_POST['features']));
  $facilities = filteration(json_decode($_POST['facilities']));

  $frm_data = filteration($_POST);
  $flag = 0;

  $q1 = "INSERT INTO `rooms`( `name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?,?,?,?,?,?,?)";
  $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc']];

  if (insert($q1, $values, 'siiiiis')) {
    $flag = 1;
    $room_id = mysqli_insert_id($con);
  } else {
    $flag = 0;
    $response = ['success' => false, 'message' => 'Error inserting room data'];
    echo json_encode($response);
    exit;
  }


  $q2 = "INSERT INTO `rooms_facilites`( `rooms_id`, `facilites_id`) VALUES (?,?)";

  if ($stmt = mysqli_prepare($con, $q2)) {
    foreach ($facilities as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
      mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    $response = ['success' => false, 'message' => 'Query cannot be prepared - insert (facilities)'];
    echo json_encode($response);
    exit;
  }

  $q3 = "INSERT INTO `rooms_features`( `room_id`, `features_id`) VALUES (?,?)";

  if ($stmt = mysqli_prepare($con, $q3)) {
    foreach ($features as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
      mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    $response = ['success' => false, 'message' => 'Query cannot be prepared - insert (features)'];
    echo json_encode($response);
    exit;
  }

  if ($flag == 1) {
    $response = ['success' => true, 'message' => 'Room added successfully!'];
  } else {
    $response = ['success' => false, 'message' => 'Invalid request'];
  }
  echo json_encode($response);
}


if (isset($_POST['get_all_rooms'])) {
  $res = select("SELECT * FROM `rooms` WHERE `removed`=?",[0],'i');
  $i = 1;

  $data = "";
  while ($row = mysqli_fetch_assoc($res)) {
    if ($row['status'] == 1) {
      $status = "<button onclick='toggle_status($row[id],0)'class ='btn btn-dark btn-sm shadow-none'>Active</button>";
    } else {
      $status = "<button onclick='toggle_status($row[id],1)' class ='btn btn-warning btn-sm shadow-none'>Inactive</button>";
    }


    $data .= " 
    <tr class='align-middle'>
       <td>$i</td>
       <td>$row[name]</td> 
       <td>$row[area] sq.ft</td>
       <td>
        <span class ='badge rounded-pill bg-light text-dark'>
          Adult: $row[adult]
        </span><br>
        <span class ='badge rounded-pill bg-light text-dark'>
          Children: $row[children]
        </span>
       </td>
       <td> ₹ $row[price]</td>
       <td>$row[quantity]</td>
       <td>$status</td>
       <td>
             <button type='button' onclick='edit_details($row[id])' class='btn btn-primary dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
              <i class='bi bi-pencil-square'></i> Edit
              </button>
              <button type='button' onclick=\"room_image($row[id],'$row[name]')\" class='btn btn-info dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
              <i class='bi bi-images'></i>
              </button>
               <button type='button' onclick='remove_room($row[id])' class='btn btn-danger dark shadow-none btn-sm'>
              <i class='bi bi-trash'></i>
              </button>
       </td>
       </tr>";
    $i++;
  }
  echo $data;
}

if (isset($_POST['get_room'])) {
  $frm_data = filteration($_POST);
  $res1 = select("SELECT * FROM `rooms` WHERE `id`=? ", [$frm_data['get_room']], 'i');
  $res2 = select("SELECT * FROM `rooms_features` WHERE `room_id`=? ", [$frm_data['get_room']], 'i');
  $res3 = select("SELECT * FROM `rooms_facilites` WHERE `rooms_id`=? ", [$frm_data['get_room']], 'i');

  $roomdata = mysqli_fetch_assoc($res1);
  $features = [];
  $facilities = [];

  if (mysqli_num_rows($res2) > 0) {
    while ($row = mysqli_fetch_assoc($res2)) {
      array_push($features, $row['features_id']);
    }
  }

  if (mysqli_num_rows($res3) > 0) {
    while ($row = mysqli_fetch_assoc($res3)) {
      array_push($facilities, $row['facilites_id']);
    }
  }

  $data = ["roomdata" => $roomdata, "features"=> $features, "facilities" => $facilities];

  $data = json_encode($data);

  echo $data;
}

if (isset($_POST['edit_room'])) {
  $features = filteration(json_decode($_POST['features']));
  $facilities = filteration(json_decode($_POST['facilities']));

  $frm_data = filteration($_POST);
  $flag = 0;

  $q1 = "UPDATE `rooms` SET `name`=?,`area`=?,`price`=?,`quantity`=?,
  `adult`=?,`children`=?,`description`=? WHERE `id`=?";

  $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc'], $frm_data['room_id']];

  if ( update($q1, $values, 'siiiiisi')) {
    $flag = 1;
  }

   $del_features=delete("DELETE FROM `rooms_features` WHERE `room_id`=? ", [$frm_data['room_id']], 'i');
   $del_facilities=delete("DELETE FROM `rooms_facilites` WHERE `rooms_id`=? ", [$frm_data['room_id']], 'i');

   if(!($del_features && $del_facilities)){
     $flag=0;
   }

  $q2 = "INSERT INTO `rooms_facilites`( `rooms_id`, `facilites_id`) VALUES (?,?)";

  if ($stmt = mysqli_prepare($con, $q2)) {
    foreach ($facilities as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
      mysqli_stmt_execute($stmt);
    }
    $flag = 1;
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    $response = ['success' => false, 'message' => 'Query cannot be prepared - insert(facilities)'];
    echo json_encode($response);
    exit;
  }

  $q3 = "INSERT INTO `rooms_features`( `room_id`, `features_id`) VALUES (?,?)";

  if ($stmt = mysqli_prepare($con, $q3)) {
    foreach ($features as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
      mysqli_stmt_execute($stmt);
    }
    $flag = 1;
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    $response = ['success' => false, 'message' => 'Query cannot be prepared - insert (features)'];
    echo json_encode($response);
    exit;
  }

  if ($flag == 1) {
    $response = ['success' => true, 'message' => 'Room Edited successfully!'];
  } else {
    $response = ['success' => false, 'message' => 'Invalid request'];
  }
  echo json_encode($response);
}

// if (isset($_POST['edit-room'])) {
//   // Decode JSON data properly
//   $features = json_decode($_POST['features'], true);
//   $facilities = json_decode($_POST['facilities'], true);

//   // Validate decoded data
//   if (!is_array($features) || !is_array($facilities)) {
//       die(json_encode(['success' => false, 'message' => 'Invalid JSON data']));
//   }

//   $frm_data = filteration($_POST);
//   $flag = 0;

//   // Fix SQL syntax issue in UPDATE query
//   $q1 = "UPDATE `rooms` SET `name`=?, `area`=?, `price`=?, `quantity`=?, `adult`=?, `children`=?, `description`=? WHERE `id`=?";
//   $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc'], $frm_data['room_id']];

//   // Use update function instead of insert
//   if (update($q1, $values, 'siiiiisi')) {
//       $flag = 1;
//   } else {
//       error_log("Failed to update room data.");
//   }

//   // Fix DELETE queries to match correct table name
//   $del_features = delete("DELETE FROM `rooms_features` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
//   $del_facilities = delete("DELETE FROM `rooms_facilites` WHERE `rooms_id`=?", [$frm_data['room_id']], 'i'); // Correct table name

//   if (!($del_features && $del_facilities)) {
//       $flag = 0;
//       error_log("Failed to delete existing room features or facilities.");
//   }

//   // Insert into `room_facilites` (corrected table name)
//   $q2 = "INSERT INTO `room_facilites` (`rooms_id`, `facilites_id`) VALUES (?, ?)";
//   if ($stmt = mysqli_prepare($con, $q2)) {
//       foreach ($facilities as $f) {
//           mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
//           mysqli_stmt_execute($stmt);
//       }
//       $flag = 1;
//       mysqli_stmt_close($stmt);
//   } else {
//       die(json_encode(['success' => false, 'message' => 'Query cannot be prepared - insert (facilities)']));
//   }

//   // Insert into `rooms_features`
//   $q3 = "INSERT INTO `rooms_features` (`room_id`, `features_id`) VALUES (?, ?)";
//   if ($stmt = mysqli_prepare($con, $q3)) {
//       foreach ($features as $f) {
//           mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
//           mysqli_stmt_execute($stmt);
//       }
//       $flag = 1;
//       mysqli_stmt_close($stmt);
//   } else {
//       die(json_encode(['success' => false, 'message' => 'Query cannot be prepared - insert (features)']));
//   }

//   // Final response
//   echo json_encode(['success' => $flag == 1, 'message' => $flag ? 'Room Edited successfully!' : 'Invalid request']);
// }

// if (isset($_POST['edit-room'])) {
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);

//   //require 'db.php'; // Ensure this file correctly initializes $con

//   // Validate database connection
//   if (!$con) {
//       die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]));
//   }

//   // 🔹 DEBUG: Show received POST data
//   file_put_contents('debug_log.txt', print_r($_POST, true));

//   // Decode JSON fields properly
//   $features = json_decode($_POST['features'], true);
//   $facilities = json_decode($_POST['facilities'], true);

//   // Ensure they are arrays
//   if (!is_array($features) || !is_array($facilities)) {
//       die(json_encode(['success' => false, 'message' => 'Invalid JSON data for features or facilities']));
//   }

//   // Filter input (if function exists)
//   $frm_data = filteration($_POST);
//   $flag = 0;

//   // Check if room exists
//   $check = select("SELECT id FROM rooms WHERE id=?", [$frm_data['room_id']], 'i');
//   if (mysqli_num_rows($check) == 0) {
//       die(json_encode(['success' => false, 'message' => 'Invalid room ID']));
//   }

//   // 🔹 DEBUG: Log filtered data
//   file_put_contents('debug_log.txt', print_r($frm_data, true), FILE_APPEND);

//   // ✅ Update room details
//   $q1 = "UPDATE `rooms` SET `name`=?, `area`=?, `price`=?, `quantity`=?, `adult`=?, `children`=?, `description`=? WHERE `id`=?";
//   $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc'], $frm_data['room_id']];

//   $stmt = mysqli_prepare($con, $q1);
//   if (!$stmt) {
//       die(json_encode(['success' => false, 'message' => 'Query preparation failed: ' . mysqli_error($con)]));
//   }

//   if (update($q1, $values, 'siiiiisi')) {
//       $flag = 1;
//   }

//   // ✅ Delete old feature and facility associations
//   delete("DELETE FROM `rooms_features` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
//   delete("DELETE FROM `room_facilites` WHERE `rooms_id`=?", [$frm_data['room_id']], 'i');

//   // ✅ Insert new facilities
//   $q2 = "INSERT INTO `room_facilites` (`rooms_id`, `facilites_id`) VALUES (?, ?)";
//   if ($stmt = mysqli_prepare($con, $q2)) {
//       foreach ($facilities as $f) {
//           mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
//           mysqli_stmt_execute($stmt);
//       }
//       mysqli_stmt_close($stmt);
//   } else {
//       die(json_encode(['success' => false, 'message' => 'Query cannot be prepared - insert (facilities)']));
//   }

//   // ✅ Insert new features
//   $q3 = "INSERT INTO `rooms_features` (`room_id`, `features_id`) VALUES (?, ?)";
//   if ($stmt = mysqli_prepare($con, $q3)) {
//       foreach ($features as $f) {
//           mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
//           mysqli_stmt_execute($stmt);
//       }
//       mysqli_stmt_close($stmt);
//   } else {
//       die(json_encode(['success' => false, 'message' => 'Query cannot be prepared - insert (features)']));
//   }

//   // ✅ Return final success response
//   echo json_encode(['success' => $flag == 1, 'message' => 'Room edited successfully!']);
// }



if (isset($_POST['toggle_status'])) {
  $frm_data = filteration($_POST);

  $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
  $v = [$frm_data['value'], $frm_data['toggle_status']];

  if (update($q, $v, 'ii')) {
    echo 1;
  } else {
    echo 0;
  }
}

if (isset($_POST['add_image'])) {
  $frm_data = filteration($_POST);

  $img_r = uploadImage($_FILES['image'], ROOMS_FOLDER);

  if ($img_r == 'inv_img') {
    echo $img_r;
  } else if ($img_r == 'inv_size') {
    echo $img_r;
  } else if ($img_r == 'upd_failed') {
    echo $img_r;
  } else {
    $q = "INSERT INTO `room_image`( `room_id`, `image`) VALUES (?,?)";
    $values = [$frm_data['room_id'], $img_r];
    $res = insert($q, $values, 'is');
    echo $res;
  }
}

if (isset($_POST['get_room_images'])) {
  $frm_data = filteration($_POST);
  $res=select("SELECT * FROM `room_image` WHERE `room_id`=?",[$frm_data['get_room_images']],'i');

  $path=ROOMS_IMAGE_PATH;

  while($row=mysqli_fetch_assoc($res)) 

  {
    if($row['thumb']==1)
    {
      $thumb_btn =" <i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
    }
    else {
      $thumb_btn=" <button onclick='thumb_image($row[sr_no],$row[room_id])' class ='btn btn-secondary btn-sm shadow-none'>
            <i class='bi bi-check-lg'></i>
         </button>";
    }
    
    echo<<<data
    <tr class='align-middle'>
     <td><img src='$path$row[image]' class='img-fluid'></td>
     <td class='text-center'>$thumb_btn</td>
       <td class='text-center'>
         <button onclick='rem_image($row[sr_no],$row[room_id])' class ='btn btn-danger btn-sm shadow-none'>
            <i class='bi bi-trash'></i>
         </button>
       </td>
    </tr>
    data;
  }
}

if (isset($_POST['rem_image'])) {

  $frm_data = filteration($_POST);
  $values = [$frm_data['image_id'],$frm_data['room_id']];

  $pre_q="SELECT * FROM `room_image` WHERE `sr_no`=? AND `room_id`=?";
  $res = select($pre_q, $values, 'ii');
  $img=mysqli_fetch_assoc($res);

  if(deleteImage($img['image'],ROOMS_FOLDER))
  {
    $q = "DELETE FROM `room_image` WHERE `sr_no`=? AND `room_id`=?";
    $res = delete($q, $values, 'ii');
    echo $res;
  }
  else {
    echo 0;
  }
}

if (isset($_POST['thumb_image'])) {

  $frm_data = filteration($_POST);
  
  $pre_q="UPDATE `room_image` SET `thumb`= ? WHERE `room_id`=?";
  $pre_v=[0,$frm_data['room_id']];
  $pre_res=update($pre_q,$pre_v,'ii');

  $q="UPDATE `room_image` SET `thumb`= ? WHERE `sr_no`= ? AND `room_id`=?";
  $v=[1,$frm_data['image_id'],$frm_data['room_id']];
  $res=update($q,$v,'iii');

  echo $pre_res;

  
}
 
if (isset($_POST['remove_room']))
{
  $frm_data = filteration($_POST);

  $res1=select("SELECT * FROM `room_image` WHERE `room_id`=?",[$frm_data['room_id']],'i');

  while($row=mysqli_fetch_assoc($res1))
  {
    deleteImage($row['image'],ROOMS_FOLDER);
  }
  $res2=delete("DELETE FROM `room_image` WHERE `room_id`=?",[$frm_data['room_id']],'i');
  $res3=delete("DELETE FROM `rooms_features` WHERE `room_id`=?",[$frm_data['room_id']],'i');
  $res4=delete("DELETE FROM `rooms_facilites` WHERE `rooms_id`=?",[$frm_data['room_id']],'i');
  $res5=update("UPDATE `rooms` SET `removed`= ? WHERE `id`=?",[1,$frm_data['room_id']],'ii');


  if($res2|| $res3||$res4||$res5)
  {
    echo 1;
  } 
  else
  {
    echo 0;
  }
 
}

?>
