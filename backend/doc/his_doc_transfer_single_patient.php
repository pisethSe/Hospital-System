<!--Server side code to handle  Patient Transfer-->
<?php
session_start();
include('assets/inc/config.php');

if(isset($_POST['transfer_patient'])) {
    // Set the timezone to Cambodia (Phnom Penh)
    date_default_timezone_set('Asia/Phnom_Penh');

    $t_pat_number = $_POST['t_pat_number'];
    $t_pat_name = $_POST['t_pat_name'];
    $t_hospital = $_POST['t_hospital'];
    $t_status = $_POST['t_status'];

    // Get current datetime in Cambodia timezone (ICT - UTC+7)
    $t_date = date('Y-m-d H:i:s');

    // SQL to insert captured values with proper datetime
    $query = "INSERT INTO hmisphp.his_patient_transfers (t_pat_number, t_pat_name, t_date, t_hospital, t_status) VALUES(?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);

    $rc = $stmt->bind_param('sssss', $t_pat_number, $t_pat_name, $t_date, $t_hospital, $t_status);
    $stmt->execute();

    // Update patient status
    $update_query = "UPDATE hmisphp.his_patients SET pat_type = 'Transferred' WHERE pat_number = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('s', $t_pat_number);
    $update_stmt->execute();

    if($stmt && $update_stmt) {
        $_SESSION['success'] = "Patient Transferred Successfully at " . date('h:i A', strtotime($t_date)) . " (Cambodia Time)";
    } else {
        $_SESSION['err'] = "Please Try Again Or Try Later";
    }

    header("Location: his_doc_patient_transfer.php");
    exit();
}
?>
<!--End Server Side-->
<!--End Patient Transfer-->
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>view patients</title>
</head>
    <!--Head-->
    <?php include('assets/inc/head.php');?>
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include("assets/inc/nav.php");?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include("assets/inc/sidebar.php");?>
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
                                            <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
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
                        $pat_number = $_GET['pat_number'];
                        $ret = "SELECT * FROM hmisphp.his_patients WHERE pat_number = ?";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->bind_param('s', $pat_number); // 's' because pat_number is VARCHAR
                        $stmt->execute();
                        $res = $stmt->get_result();

                        while($row = $res->fetch_object()) {
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="mb-4">
                                                <label for="t_pat_name" class="block text-sm font-medium text-gray-700">Patient Name</label>
                                                <input
                                                        type="text"
                                                        id="t_pat_name"
                                                        name="t_pat_name"
                                                        value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>"
                                                        required
                                                        placeholder="Patient's First Name"
                                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                >
                                            </div>


                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- Referral Hospital -->
                                                <div>
                                                    <label for="t_hospital" class="block text-sm font-medium text-gray-700">Referral Hospital</label>
                                                    <input
                                                            type="text"
                                                            id="t_hospital"
                                                            name="t_hospital"
                                                            required
                                                            placeholder="Referral/Transfer Hospital"
                                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    >
                                                </div>

                                                <!-- Transfer Date -->
                                                <div>
                                                    <label for="t_date" class="block text-sm font-medium text-gray-700">Transfer Date</label>
                                                    <input
                                                            type="date"
                                                            id="t_date"
                                                            name="t_date"
                                                            required
                                                            value="<?php echo date('Y-m-d'); ?>"
                                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    />

                                                </div>

                                                <!-- Hidden Patient Number -->
                                                <div class="hidden">
                                                    <label for="t_pat_number" class="block text-sm font-medium text-gray-700">Patient Number</label>
                                                    <input
                                                            type="text"
                                                            id="t_pat_number"
                                                            name="t_pat_number"
                                                            value="<?php echo $row->pat_number; ?>"
                                                            required
                                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700"
                                                    >
                                                </div>
                                            </div>


                                            <div class="form-group" style="display:none">
                                                <label for="inputAddress" class="col-form-label">Transfer Status</label>
                                                <input required="required" type="text" value="Success" class="form-control" name="t_status" id="inputAddress" placeholder="Patient's Addresss">
                                            </div>

                                            <button
                                                    type="submit"
                                                    name="transfer_patient"
                                                    class=" mt-2 inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-colors duration-200"
                                            >
                                                Transfer Patient
                                            </button>


                                        </form>
                                        <!--End Patient Form-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <?php  }?>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
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