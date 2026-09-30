<!--Server side code to handle  Patient Transfer-->
<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['transfer_patient'])) {
    $t_pat_number = $_POST['t_pat_number'];
    $t_pat_name = $_POST['t_pat_name'];
    $t_date = $_POST['t_date'];
    $t_hospital = $_POST['t_hospital'];
    $t_status = $_POST['t_status'];

    // 1. Insert into transfer table
    $query = "INSERT INTO hmisphp.his_patient_transfers 
              (t_pat_number, t_pat_name, t_date, t_hospital, t_status) 
              VALUES(?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssss', $t_pat_number, $t_pat_name, $t_date, $t_hospital, $t_status);
    $stmt->execute();

    if ($stmt) {
        // 2. Delete patient from original table
        $del = $mysqli->prepare("DELETE FROM hmisphp.his_patients WHERE pat_number=?");
        $del->bind_param('s', $t_pat_number);
        $del->execute();
        $del->close();

        // Optional: Redirect to refresh page & show updated tables
        header("Location: his_admin_patient_transfer.php");
        exit();
    } else {
        $err = "Transfer failed, please try again.";
    }
}
?>
<!--End Server Side-->
<!--End Patient Transfer-->
<!DOCTYPE html>
<html lang="en">

<!--Head-->
<?php include('assets/inc/head.php'); ?>
<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?php include("assets/inc/nav.php"); ?>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include("assets/inc/sidebar.php"); ?>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">Transfer Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Transfer Patient To A Refferal Facility</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <!-- Form row -->
                <!--LETS GET DETAILS OF SINGLE PATIENT GIVEN THEIR ID-->
                <?php
                $pat_number = $_GET['pat_number']; // could be "P1234"
                $ret = "SELECT * FROM hmisphp.his_patients WHERE pat_number = ?";
                $stmt = $mysqli->prepare($ret);
                $stmt->bind_param('s', $pat_number); // 's' for string
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_object()) {
                    ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>
                                    <!--Add Patient Form-->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                <input type="text" required="required"
                                                       value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>"
                                                       name="t_pat_name" class="form-control" id="inputEmail4"
                                                       placeholder="Patient's First Name">
                                            </div>

                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">Refferal
                                                    Hospital</label>
                                                <input type="text" required="required" name="t_hospital"
                                                       class="form-control" id="inputEmail4"
                                                       placeholder="Refferal/Transfer Hospital">
                                            </div>
                                            <?php
                                            date_default_timezone_set('Asia/Phnom_Penh'); // Cambodia timezone
                                            ?>
                                            <div class="form-group col-md-6">
                                                <label for="transfer_datetime" class="col-form-label">Transfer Date & Time</label>
                                                <input required="required" type="datetime-local" name="t_date" class="form-control"
                                                       id="transfer_datetime"
                                                       value="<?php echo date('Y-m-d\TH:i'); ?>">
                                            </div>


                                            <div class="form-group col-md-6" style="display:none">
                                                <label for="inputPassword4" class="col-form-label">Patient
                                                    Number </label>
                                                <input required="required" type="text" name="t_pat_number"
                                                       value="<?php echo $row->pat_number; ?>" class="form-control"
                                                       id="inputPassword4" placeholder="">
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:none">
                                            <label for="inputAddress" class="col-form-label">Transfer Status</label>
                                            <input required="required" type="text" value="Success" class="form-control"
                                                   name="t_status" id="inputAddress" placeholder="Patient's Addresss">
                                        </div>

                                        <button type="submit" name="transfer_patient"
                                                class="ladda-button btn btn-success" data-style="expand-right">Transfer
                                            Patient
                                        </button>

                                    </form>
                                    <!--End Patient Form-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                <?php } ?>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        <?php include('assets/inc/footer.php'); ?>
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->


<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="assets/js/vendor.min.js"></script>

<!-- App js-->
<script src="assets/js/app.min.js"></script>

<!-- Loading buttons js -->
<script src="assets/libs/ladda/spin.js"></script>
<script src="assets/libs/ladda/ladda.js"></script>

<!-- Buttons init js-->
<script src="assets/js/pages/loading-btn.init.js"></script>

</body>

</html>