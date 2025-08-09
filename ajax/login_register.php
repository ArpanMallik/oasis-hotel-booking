<?php
require('../admin/Inc/db_config.php');
require('../admin/Inc/essentials.php');

if (isset($_POST['register'])) {
    $data = filteration($_POST);

    //match password and confirm passward
    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }
    //check user exist or not
    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `email`=? OR `Phnum`=? LIMIT 1",
        [$data['email'], $data['phnum']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    //upload user image to server 

    $img = uploadUserImage($_FILES['profile']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT); // Secure password

    $query = "INSERT INTO `user_cred` (`name`, `email`, `Phnum`, `address`, `pincode`, `dob`, `profile`, `password`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $values = [
        $data['name'],
        $data['email'],
        $data['phnum'],
        $data['address'],
        $data['pincode'],
        $data['dob'],
        $img,
        $enc_pass
    ];

    if (insert($query, $values, "ssssisss")) {
        echo 1;
    } else {
        echo 'ins_failed';
    }
}


if (isset($_POST['login'])) {

    $data = filteration($_POST);

    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `email`=? OR `Phnum`=? LIMIT 1",
        [$data['email_mob'], $data['email_mob']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) == 0) {

        echo 'inv_email_mob';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            if (!password_verify($data['pass'], $u_fetch['password'])) {
                echo 'invalid_pass';
            } else {
                session_start();
                $_SESSION['login']=true;
                $_SESSION['uId']=$u_fetch['id'];
                $_SESSION['uName']=$u_fetch['name'];
                $_SESSION['uPic']=$u_fetch['profile'];
                $_SESSION['uPhone']=$u_fetch['phnum'];
                echo 1;
            }
        }
    }
}

?>
