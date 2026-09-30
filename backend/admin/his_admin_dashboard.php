<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Dashboard Admin </title>

    </head>
    <!--Head Code-->
    <?php include("assets/inc/head.php");?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include('assets/inc/sidebar.php');?>
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
                                    
                                    <h4 class="page-title text-3xl font-extrabold bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 bg-clip-text text-transparent">Hospital Management System Dashboard</h4>

                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">

                        <!-- Out Patients -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 p-6">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center shadow-md">
                                    <i class="fab fa-accessible-icon text-white text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <?php
                                        $result ="SELECT count(*) FROM hmisphp.his_patients WHERE pat_type = 'OutPatient' ";
                                        $stmt = $mysqli->prepare($result);
                                        $stmt->execute();
                                        $stmt->bind_result($outpatient);
                                        $stmt->fetch();
                                        $stmt->close();
                                    ?>
                                    <h3 class="text-3xl font-bold text-gray-800">
                                        <?php echo $outpatient;?>
                                    </h3>
                                    <p class="text-gray-600 text-sm">Out Patients</p>
                                </div>
                            </div>
                        </div>

                        <!-- In Patients -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 p-6">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center shadow-md">
                                    <i class="mdi mdi-hospital text-white text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <?php
                                        $result = "SELECT count(*) FROM hmisphp.his_patients WHERE pat_type = 'InPatient'";
                                        $stmt = $mysqli->prepare($result);
                                        $stmt->execute();
                                        $stmt->bind_result($inpatient);
                                        $stmt->fetch();
                                        $stmt->close();
                                    ?>
                                    <h3 class="text-3xl font-bold text-gray-800">
                                        <?php echo $inpatient;?>
                                    </h3>
                                    <p class="text-gray-600 text-sm">In Patients</p>
                                </div>
                            </div>
                        </div>

                        <!-- Employees -->
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 p-6">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded-full bg-purple-500 flex items-center justify-center shadow-md">
                                    <i class="mdi mdi-account-group text-white text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <?php
                                        $result ="SELECT count(*) FROM hmisphp.his_docs";
                                        $stmt = $mysqli->prepare($result);
                                        $stmt->execute();
                                        $stmt->bind_result($doc);
                                        $stmt->fetch();
                                        $stmt->close();
                                    ?>
                                    <h3 class="text-3xl font-bold text-gray-800">
                                        <?php echo $doc;?>
                                    </h3>
                                    <p class="text-gray-600 text-sm">Hospital Employees</p>
                                </div>
                            </div>
                        </div>

                    </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">

                            <!-- Vendors -->
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-full bg-indigo-500 flex items-center justify-center shadow-md">
                                        <i class="mdi mdi-account-clock text-yellow-500 text-2xl"></i>

                                    </div>
                                    <div class="ml-4">
                                        <?php
                                        $result = "SELECT count(*) FROM hmisphp.his_patients WHERE pat_type = 'Waiting'";
                                        $stmt = $mysqli->prepare($result);
                                        $stmt->execute();
                                        $stmt->bind_result($inpatient);
                                        $stmt->fetch();
                                        $stmt->close();
                                        ?>
                                        <h3 class="text-3xl font-bold text-gray-800">
                                            <?php echo $inpatient;?>
                                        </h3>
                                        <p class="text-gray-600 text-sm">Waiting Patient</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Corporation Assets -->
                            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-full bg-emerald-500 flex items-center justify-center shadow-md">
                                        <i class="mdi mdi-account-multiple text-white text-2xl"></i>

                                    </div>
                                    <div class="ml-4">
                                        <?php
                                            $result ="SELECT count(*) FROM hmisphp.his_patients";
                                            $stmt = $mysqli->prepare($result);
                                            $stmt->execute();
                                            $stmt->bind_result($assets);
                                            $stmt->fetch();
                                            $stmt->close();
                                        ?>
                                        <h3 class="text-3xl font-bold text-gray-800"><?php echo $assets;?></h3>
                                        <p class="text-gray-600 text-sm">All Patient</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pharmaceuticals -->
                            <div class="bg-gradient-to-r from-pink-50 to-pink-100 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-full bg-pink-500 flex items-center justify-center shadow-md">
                                        <i class="mdi mdi-pill text-white text-2xl"></i>

                                    </div>
                                    <div class="ml-4">
                                        <?php
                                            $result ="SELECT count(*) FROM hmisphp.his_prescriptions";
                                            $stmt = $mysqli->prepare($result);
                                            $stmt->execute();
                                            $stmt->bind_result($phar);
                                            $stmt->fetch();
                                            $stmt->close();
                                        ?>
                                        <h3 class="text-3xl font-bold text-gray-800"><?php echo $phar;?></h3>
                                        <p class="text-gray-600 text-sm">All prescriptions</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        

                        <!-- Recently Employed Employees -->
                    <div class="row mt-6">
                    <div class="col-xl-12">
                        <div class="bg-white shadow-md rounded-2xl p-6">
                        
                        <!-- Header -->
                        <h4 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                            <i class="mdi mdi-account-group mr-2 text-blue-600"></i> Hospital Employees
                        </h4>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 text-left text-gray-700 text-sm uppercase">
                                <th class="px-4 py-3">Picture</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Department</th>
                                <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php
                                $ret="SELECT * FROM hmisphp.his_docs LIMIT 10";
                                $stmt= $mysqli->prepare($ret);
                                $stmt->execute();
                                $res=$stmt->get_result();
                                while($row=$res->fetch_object()) {
                                ?>
                                <tr class="hover:bg-blue-50 transition-colors">
                                <!-- Picture -->
                                <td class="px-4 py-3">
                                    <img src="../doc/assets/images/users/<?php echo $row->doc_dpic;?>" 
                                        alt="profile" 
                                        class="h-12 w-12 rounded-full border border-gray-300 object-cover shadow-sm">
                                </td>
                                <!-- Name -->
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    <?php echo $row->doc_fname; ?> <?php echo $row->doc_lname; ?>
                                </td>
                                <!-- Email -->
                                <td class="px-4 py-3 text-gray-600">
                                    <?php echo $row->doc_email; ?>
                                </td>
                                <!-- Department -->
                                <td class="px-4 py-3 text-gray-700 font-medium">
                                    <?php echo $row->doc_dept; ?>
                                </td>
                                <!-- Action -->
                                <td class="px-2 py-2 text-center w-9">
                                    <a href="his_admin_view_single_employee.php?doc_id=<?php echo $row->doc_id; ?>&doc_number=<?php echo $row->doc_number; ?>"
                                    class="inline-flex items-center px-2 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                                    <i class="mdi mdi-eye mr-1 text-lg"></i> View
                                    </a>
                                </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    </div>

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

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i class="dripicons-cross noti-icon"></i>
                </a>
                <h5 class="m-0 text-white">Settings</h5>
            </div>
            <div class="slimscroll-menu">
                <!-- User box -->
                <div class="user-box">
                    <div class="user-img">
                        <img src="assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle img-fluid">
                        <a href="javascript:void(0);" class="user-edit"><i class="mdi mdi-pencil"></i></a>
                    </div>
            
                    <h5><a href="javascript: void(0);">Geneva Kennedy</a> </h5>
                    <p class="text-muted mb-0"><small>Admin Head</small></p>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h5 class="pl-3">Basic Settings</h5>
                <hr class="mb-0" />

                <div class="p-3">
                    <div class="checkbox checkbox-primary mb-2">
                        <input id="Rcheckbox1" type="checkbox" checked>
                        <label for="Rcheckbox1">
                            Notifications
                        </label>
                    </div>
                    <div class="checkbox checkbox-primary mb-2">
                        <input id="Rcheckbox2" type="checkbox" checked>
                        <label for="Rcheckbox2">
                            API Access
                        </label>
                    </div>
                    <div class="checkbox checkbox-primary mb-2">
                        <input id="Rcheckbox3" type="checkbox">
                        <label for="Rcheckbox3">
                            Auto Updates
                        </label>
                    </div>
                    <div class="checkbox checkbox-primary mb-2">
                        <input id="Rcheckbox4" type="checkbox" checked>
                        <label for="Rcheckbox4">
                            Online Status
                        </label>
                    </div>
                    <div class="checkbox checkbox-primary mb-0">
                        <input id="Rcheckbox5" type="checkbox" checked>
                        <label for="Rcheckbox5">
                            Auto Payout
                        </label>
                    </div>
                </div>

                <!-- Timeline -->
                <hr class="mt-0" />
                <h5 class="px-3">Messages <span class="float-right badge badge-pill badge-danger">25</span></h5>
                <hr class="mb-0" />
                <div class="p-3">
                    <div class="inbox-widget">
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="assets/images/users/user-2.jpg" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Tomaslau</a></p>
                            <p class="inbox-item-text">I've finished it! See you so...</p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="assets/images/users/user-3.jpg" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Stillnotdavid</a></p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="assets/images/users/user-4.jpg" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Kurafire</a></p>
                            <p class="inbox-item-text">Nice to meet you</p>
                        </div>

                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="assets/images/users/user-5.jpg" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Shahedk</a></p>
                            <p class="inbox-item-text">Hey! there I'm available...</p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="assets/images/users/user-6.jpg" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Adhamdannaway</a></p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                        </div>
                    </div> <!-- end inbox-widget -->
                </div> <!-- end .p-3-->

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Plugins js-->
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <script src="assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.time.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.selection.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.crosshair.js"></script>

        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>