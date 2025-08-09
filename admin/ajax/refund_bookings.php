<?php

require('../Inc/db_config.php');
require('../Inc/essentials.php');

header('Content-Type: application/json');
adminLogin();
$response = []; // Initialize response array




if (isset($_POST['get_bookings'])) {

  $frm_data = filteration($_POST);

  $querry = "SELECT bo.* , bd.* FROM `booking_order` bo
  INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
  WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
  AND (bo.booking_status=? AND bo.refund=?) ORDER BY bo.booking_id ASC";

  $res = select($querry,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","cancelled",0],'sssss');
  $i = 1;
  $table_data = "";
  if(mysqli_num_rows($res)==0){
    echo"<br>No data Found!</br>";
    exit;
  }

  while ($data = mysqli_fetch_assoc($res)) {
    $date = date("d-m-y",strtotime($data['datentime']));
    $checkin = date("d-m-y",strtotime($data['check_in']));
    $checkout = date("d-m-y",strtotime($data['check_out']));

      $table_data .=
       "<tr>
           <td>$i</td>
           <td>
           <span class='badge bg-primary '>
           Order ID: $data[order_id]
           </span>
           <br>
           <b>Name :</b> $data[user_name]
           <br>
            <b>Phone No :</b> $data[phonenum]
           </td>
           <td>
           <b>Room :</b> $data[room_name]
           <br>
           <b>Check in :</b>$checkin
           <br>
           <b>Check out :</b>$checkout
           <br>
           
           <b>Date :</b> $date
           </td>
           <td>
           <b>₹ $data[trans_amount]</b> 
           </td>
           <td>
           <button type='button' onclick='refund_booking($data[booking_id])' class=' btn btn-success btn-sm fw-bold shadow-none'>
             <i class='bi bi-cash-stack'></i> Refund
           </button>
            </td>
       </tr>";

       $i++;
  }
  echo $table_data;
}





if (isset($_POST['refund_booking'])) {
  $frm_data = filteration($_POST);

  $query="UPDATE `booking_order` SET `refund`=? WHERE `booking_id`=?";
  $values =[1,$frm_data['booking_id']];
  $res = update($query,$values,'ii');

  echo $res;

}

if (isset($_POST['search_user'])) {
  $frm_data = filteration($_POST);

  $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";

  $res = select($query, ["%$frm_data[name]%"], 's');
  $i = 1;
  $path = USERS_IMAGE_PATH;

  $data = "";
  while ($row = mysqli_fetch_assoc($res)) {

    $status = "<button onclick='toggle_status($row[id],0)' class ='btn btn-dark btn-sm shadow-none'>active</button>";

    $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger dark shadow-none btn-sm'>
              <i class='bi bi-trash'></i>
              </button>";

    if (!$row['status']) {
      $status = "<button onclick='toggle_status($row[id],1)' class ='btn btn-danger btn-sm shadow-none'>Inactive</button>";
    }

    $date = date("d-m-Y", strtotime($row['dateandtime']));

    $data .= " 
        <tr>
         <td>$i </td>
          <td><img src='$path$row[profile]' width='55px'>
          <br>
          $row[name]
          </td>
         <td>$row[email]</td>
         <td>$row[phnum]</td>
         <td>$row[address]| $row[pincode]</td>
         <td>$row[dob]</td>
         <td>$status</td>
         <td>$date</td>
         <td>$del_btn</td>
        </tr>
    
      ";
    $i++;
  }
  echo $data;
}

?>
