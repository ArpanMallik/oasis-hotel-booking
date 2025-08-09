<?php
require('Inc/essentials.php');
require('Inc/db_config.php');
adminLogin();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel- Users</title>
    <?php require('Inc/links.php'); ?>

</head>

<body class="bg-light">

    <?php require('Inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">USERS</h3>

                <div class="col-lg-12">
                    <div class="card  shadow ">
                        <div class="card-body">
                            <div class="text-end mb-4">
                                <input type="text" oninput="search_user(this.value)" class="from-control shadow-none w-25 ms-auto" placeholder=" Type to search user">

                                <!-- <button type="button" class="btn btn-primary dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                                    <i class="bi bi-plus-square"></i> Add
                                </button> -->
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover border" style="min-width:1300px;">
                                    <thead>
                                        <tr class="bg-dark text-light">
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone no.</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">DOB</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-data">
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- Closing card-body -->
                    </div> <!-- Closing card -->
                </div> <!-- Closing col-lg-12 -->



            </div> <!-- Closing col-lg-10 -->
        </div> <!-- Closing row -->
    </div> <!-- Closing container-fluid -->




    





    <?php require('Inc/scripts.php'); ?>

    <script src="scripts/users.js"></script>

</body>

</html>