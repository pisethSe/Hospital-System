<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $doc_id = $_SESSION['doc_id'];
  $doc_number = $_SESSION['doc_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>My Profile</title>
</head>
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

        <?php
        // Fetch doctor profile
        $ret = "SELECT * FROM hmisphp.his_docs WHERE doc_number = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('s', $doc_number);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($doc = $res->fetch_object()) {
        ?>
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profile</a></li>
                                        <li class="breadcrumb-item active">View My Profile</li>
                                    </ol>
                                </div>
                                <h4 class="page-title"><?php echo $doc->doc_fname;?> <?php echo $doc->doc_lname;?>'s Profile</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <!-- Profile -->
                        <div class="col-lg-6 col-xl-6">
                            <div class="card-box text-center">
                                <img src="../doc/assets/images/users/<?php echo $doc->doc_dpic;?>" 
                                     class="rounded-circle avatar-lg img-thumbnail"
                                     alt="profile-image">

                                <div class="text-centre mt-3">
                                    <p class="text-muted mb-2 font-13">
                                        <strong>Employee Full Name :</strong> 
                                        <span class="ml-2"><?php echo $doc->doc_fname;?> <?php echo $doc->doc_lname;?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13">
                                        <strong>Employee Department :</strong> 
                                        <span class="ml-2"><?php echo $doc->doc_dept;?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13">
                                        <strong>Employee Number :</strong> 
                                        <span class="ml-2"><?php echo $doc->doc_number;?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13">
                                        <strong>Employee Email :</strong> 
                                        <span class="ml-2"><?php echo $doc->doc_email;?></span>
                                    </p>
                                </div>
                            </div>
                        </div> <!-- end col-->

                        <!-- Vitals -->
                        <div class="col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Body Temperature</th>
                                            <th>Heart Rate/Pulse</th>
                                            <th>Respiratory Rate</th>
                                            <th>Blood Pressure</th>
                                            <th>Date Recorded</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $vit_pat_number = $_SESSION['doc_number'];
                                        $ret = "SELECT * FROM hmisphp.his_vitals WHERE vit_pat_number = ?";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->bind_param('s', $vit_pat_number);
                                        $stmt->execute();
                                        $res = $stmt->get_result();

                                        while($vit = $res->fetch_object()) {
                                            $mysqlDateTime = $vit->vit_daterec;
                                    ?>
                                        <tr>
                                            <td><?php echo $vit->vit_bodytemp;?>°C</td>
                                            <td><?php echo $vit->vit_heartpulse;?> BPM</td>
                                            <td><?php echo $vit->vit_resprate;?> bpm</td>
                                            <td><?php echo $vit->vit_bloodpress;?> mmHg</td>
                                            <td><?php echo date("Y-m-d", strtotime($mysqlDateTime));?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end col-->
                    </div>
                    <!-- end row-->
                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php');?>
            <!-- end Footer -->
        </div>
        <?php } // end if doc found ?>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
</body>
</html>
