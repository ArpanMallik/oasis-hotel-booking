<?php

require('../Inc/db_config.php');
require('../Inc/essentials.php');

header('Content-Type: application/json');
adminLogin();
$response = []; // Initialize response array




if (isset($_POST['get_bookings'])) {

  $frm_data = filteration($_POST);

  $limit = 3;
  $page = $frm_data['page'];
  $start=($page-1) * $limit;

  $query = "SELECT bo.* , bd.* FROM `booking_order` bo
  INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
  WHERE ((bo.booking_status='pending' AND bo.arrival=1) 
  OR (bo.booking_status='cancelled' AND bo.refund=1)
  OR (bo.booking_status='pending'))
  AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
  ORDER BY bo.booking_id DESC";

  $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');

  $limit_query=$query ." LIMIT $start,$limit";
  $limit_res= select($limit_query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');


  $i = 1;
  $table_data = "";

  $total_rows=mysqli_num_rows($res);
  if($total_rows==0){
    $output =json_encode(['table_data'=>"<b>No data Found!</b>","pagination" =>'']);
   
    exit;
  }
   $i=$start+1;

  $table_data="";

  while ($data = mysqli_fetch_assoc($limit_res)) {
    $date = date("d-m-y",strtotime($data['datentime']));
    $checkin = date("d-m-y",strtotime($data['check_in']));
    $checkout = date("d-m-y",strtotime($data['check_out']));

    if($data['booking_status']=='pending'){
      $status_bg='bg-warning text-dark';

    }else if ($data['booking_status']=='cancelled')
    {
       $status_bg='bg-danger text-dark';
    }
    else{
         $status_bg='bg-success';
    }
      
      
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
           <b>Price :</b> ₹$data[price]
           </td>
           <td>
           <b>Check in :</b>$checkin
           <br>
           <b>Check out :</b>$checkout
           <br>
           <b>Amount :</b> $data[trans_amount]
           <br>
           <b>Date :</b> $date
           </td>
            <td>
            <span class='badge $status_bg'>$data[booking_status]</span>
            </td>
           <td>
           <br>
           <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
             <i class='bi bi-file-earmark-arrow-down-fill'></i>
           </button>
            </td>
       </tr>";

       $i++;
  }

  $pagination="";

  if($total_rows>$limit)
  {
     $total_pages =ceil($total_rows/$limit);

     if($page!=1)
     {
        $pagination .=" <li class='page-item'><button onclick='change_page(1)' class='page-link'>First</button></li>";

     }

     $disabled =($page==1) ? "disabled" : "";
     $prev=$page-1;
     $pagination .=" <li class='page-item $disabled'><button onclick='change_page($prev)' class='page-link'>Previous</button></li>";

     $disabled=($page==$total_pages) ? "disabled" : "";
     $next=$page+1;
     $pagination .=" <li class='page-item $disabled'><button onclick='change_page($next)' class='page-link'>Next</button></li>";

     if($page!=$total_pages)
     {
        $pagination .=" <li class='page-item $disabled'><button onclick='change_page($total_pages)' class='page-link'>Last</button></li>";

     }

  }

  $output=json_encode(["table_data"=>$table_data ,"pagination" => $pagination ]);

  echo $output;
}


if (isset($_POST['assign_room']))
{
  $frm_data = filteration($_POST);

  $query="UPDATE `booking_order` bo INNER JOIN `booking_details` bd
  ON bo.booking_id=bd.booking_id
  SET bo.arrival=?,bd.room_no =?
  WHERE bo.booking_id=?";

  $values=[1,$frm_data['room_no'],$frm_data['booking_id']];
  $res=update($query,$values,'isi');

  echo($res==2)? 1 : 0;
}


if (isset($_POST['cancel_booking'])) {
  $frm_data = filteration($_POST);

  $query="UPDATE `booking_order` SET `booking_status`=?,`refund`=? WHERE `booking_id`=?";
  $values =['cancelled',0,$frm_data['booking_id']];
  $res = update($query,$values,'sii');

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


