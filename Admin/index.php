<?php
  require('Inc/db_config.php');
  require('Inc/essentials.php');
  session_start();
    //if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
       // redirect('dashboard.php');
 //}
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('Inc/links.php'); ?>
    <style>
        div.login-from{
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50% ,-50%);
            width: 400px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="login-from text-center rounded bg-white shadow overflow-hidden">
        
        <form method="POST">
            <h4 class="bg-dark text-white py-3">ADMIN LOGIN PANEL</h4>
            <div class="p-4">
              <div class="mb-3">
               
               <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name" >
             </div>
             <div class="mb-4">
              
              <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password" >
             </div>
             <button name="Login" type="submit" class="btn text-white custom-bg shadow none">LOGIN</button>
            </div>
        </form>
    </div>

    <?php 

       if(isset($_POST['Login']))
       {
        $frm_data = filteration($_POST);


        
        $query = " SELECT * FROM  admin_cred  WHERE admin_name = ?  AND admin_pass = ? ";
        $values = [$frm_data['admin_name'],$frm_data['admin_pass']];
      

        $res = select($query,$values,"ss");
        //print_r($res);
        //if(mysqli_num_rows($res) > 0) {
            // Fetch the row
            //$row = mysqli_fetch_assoc($res);
           // print_r($row); // Print the fetched row
        //} else {
           // echo "No matching user found!";
       //}

       if($res-> num_rows==1){
        $row = mysqli_fetch_assoc($res);
        $_SESSION['adminlogin'] = true;
        $_SESSION['adminId'] = $row['sr_no'];
        //session_start();
        redirect('dashboard.php');
        exit;
    } 
       else{
         
        alert('error', 'Login Failed - Invalid Credentials');


       }


    }
        

    ?>  


<?php require('Inc/scripts.php')?>  
</body>
</html>