<?php
require('../admin/Inc/db_config.php');
require('../admin/Inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

if (isset($_POST['info_form'])) {
    $frm_data = filteration($_POST);
    session_start();

    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE  `Phnum`=? AND `id`!=? LIMIT 1",
        [$frm_data['phnum'], $_SESSION['uId']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo 'phone_already';
        exit;
    }
    $query = "UPDATE `user_cred` SET `name`=?,`address`=?,`phnum`=?,`pincode`=?,`dob`=? WHERE `id`=?";
    $values = [
        $frm_data['name'],
        $frm_data['address'],
        $frm_data['phnum'],
        $frm_data['pincode'],
        $frm_data['dob'],
        $_SESSION['uId']
    ];
    if (update($query, $values, 'ssssss')) {
        $_SESSION['uName'] = $frm_data['name'];
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['profile_form'])) {

    session_start();


    $img = uploadUserImage($_FILES['profile']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }








    $u_exist = select( "SELECT `profile` FROM  `user_cred` WHERE  `id`=?  LIMIT 1",[ $_SESSION['uId']],"s");

    $u_fetch=mysqli_fetch_assoc($u_exist);

    deleteImage($u_fetch['profile'],USERS_FOLDER);

    
    $query = "UPDATE `user_cred` SET `profile`=? WHERE `id`=?";
    $values = [
        $img,
        $_SESSION['uId']
    ];
    if (update($query, $values, 'ss')) {
        $_SESSION['uPic'] = $img;
        echo 1;
    } else {
        echo 0;
    }
}
