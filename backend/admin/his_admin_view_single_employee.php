<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
?>

<!DOCTYPE html>
    <html lang="en">

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

            <!--Get Details Of A Single User And Display Them Here-->
            <?php
                $doc_id=$_GET['doc_id'];
            $ret = "SELECT  * FROM hmisphp.his_docs WHERE doc_id=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('i',$doc_id);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                $doc_number=$_GET['doc_number'];
                //$cnt=1;
                while($row=$res->fetch_object())
            {
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                                            <li class="breadcrumb-item active">View Employees</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><?php echo $row->doc_fname;?> <?php echo $row->doc_lname;?>'s Profile</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Profile Card -->
                            <div class="w-full md:w-1/2">
                                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                                    <!-- Profile Image -->
                                    <div class="flex justify-center mb-4">
                                        <img src="../doc/assets/images/users/<?php echo $row->doc_dpic; ?>"
                                             class="rounded-full h-32 w-32 object-cover border-4 border-gray-100 shadow-sm"
                                             alt="Profile Image">
                                    </div>

                                    <!-- Profile Details -->
                                    <div class="mt-4 text-black space-y-3 text-left">
                                        <p class="mb-3 text-sm">
                                            <strong class="font-medium text-gray-800">Full Name:</strong>
                                            <span class="ml-2"><?php echo $row->doc_fname; ?> <?php echo $row->doc_lname; ?></span>
                                        </p>
                                        <p class="mb-3 text-sm">
                                            <strong class="font-medium text-gray-800">Department:</strong>
                                            <span class="ml-2"><?php echo $row->doc_dept; ?></span>
                                        </p>
                                        <p class="mb-3 text-sm">
                                            <strong class="font-medium text-gray-800">Employee Number:</strong>
                                            <span class="ml-2"><?php echo $row->doc_number; ?></span>
                                        </p>
                                        <p class="mb-3 text-sm">
                                            <strong class="font-medium text-gray-800">Email:</strong>
                                            <span class="ml-2"><?php echo $row->doc_email; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Vitals Table -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Body Temp</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Heart Rate</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Respiratory Rate</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Blood Pressure</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date Recorded</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                        <?php
                                        $vit_pat_number = $_GET['doc_number'];
                                        $ret = "SELECT * FROM his_vitals WHERE vit_pat_number = '$vit_pat_number'";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();

                                        while($row = $res->fetch_object()) {
                                            $mysqlDateTime = $row->vit_daterec;
                                            ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo $row->vit_bodytemp; ?>°C</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo $row->vit_heartpulse; ?>BPM</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo $row->vit_resprate; ?>bpm</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo $row->vit_bloodpress; ?>mmHg</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo date("Y-m-d", strtotime($mysqlDateTime)); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end row-->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div>
            <?php }?>

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