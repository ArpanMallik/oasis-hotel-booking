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
    <title>Admin Panel- New Bookings</title>
    <?php require('Inc/links.php'); ?>

</head>

<body class="bg-light">

    <?php require('Inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">NEW BOOKINGS</h3>

                <div class="col-lg-12">
                    <div class="card  shadow ">
                        <div class="card-body">
                            <div class="text-end mb-4">
                                <input type="text" oninput="get_bookings(this.value)" class="from-control shadow-none w-25 ms-auto" placeholder=" Type to search user">

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
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-data">
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- Closing card-body -->
                    </div> <!-- Closing card -->
                </div> <!-- Closing col-lg-12 -->



            </div> <!-- Closing col-lg-10 -->
        </div> <!-- Closing row -->
    </div> <!-- Closing container-fluid -->




    <!---- Assign Room  model  section---->
    <div class="modal fade" id="assign-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="assign_room_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Room</h5>

                    </div>
                    <div class="modal-body">
                        <div class=" mb-3">
                            <label class="form-label fw-bold">Room Number</label>
                            <input type="text" name="room_no" class="form-control shadow-none" required>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark mb-3 text -wrap lh-base">
                            Note: Assign Room Number only when user has been arrived.
                        </span>
                        <input type="hidden" name="booking_id">
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Assign</button>
                    </div>
                </div>

            </form>

        </div>
    </div>





    <?php require('Inc/scripts.php'); ?>

    <script src="scripts/new_bookings.js"></script>

</body>

</html>