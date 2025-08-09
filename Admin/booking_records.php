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
    <title>Admin Panel- Bookings Records</title>
    <?php require('Inc/links.php'); ?>

</head>

<body class="bg-light">

    <?php require('Inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4"> BOOKING RECORDS</h3>

                <div class="col-lg-12">
                    <div class="card  shadow ">
                        <div class="card-body">
                            <div class="text-end mb-4">
                                <input type="text" id="search_input" oninput="get_bookings(this.value)" class="from-control shadow-none w-25 ms-auto" placeholder=" Type to search user">

                                <!-- <button type="button" class="btn btn-primary dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                                    <i class="bi bi-plus-square"></i> Add
                                </button> -->
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover border" style="min-width: 1300px;">
                                    <thead>
                                        <tr class="bg-dark text-light">
                                            <th scope="col">#</th>
                                            <th scope="col">User Details</th>
                                            <th scope="col">Room Details</th>
                                            <th scope="col">Booking Details</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-data">
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <ul class="pagination mt-2" id="table-pagination">
                                </ul>
                            </nav>
                        </div> <!-- Closing card-body -->
                    </div> <!-- Closing card -->
                </div> <!-- Closing col-lg-12 -->



            </div> <!-- Closing col-lg-10 -->
        </div> <!-- Closing row -->
    </div> <!-- Closing container-fluid -->










    <?php require('Inc/scripts.php'); ?>

    <script src="scripts/booking_records.js"></script>

</body>

</html>