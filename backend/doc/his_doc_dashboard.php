<?php
global $mysqli;
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$doc_id = $_SESSION['doc_id'];
$doc_number = $_SESSION['doc_number'];

?>
<!DOCTYPE html>
<html lang="en">

<!--Head Code-->
<?php include("assets/inc/head.php"); ?>
<!--update link tailwind css -->
<script src="https://cdn.tailwindcss.com"></script>
<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?php include('assets/inc/nav.php'); ?>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include('assets/inc/sidebar.php'); ?>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <!-- start page title -->
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4">
                        <div class="mb-3 mt-3">
                            <h4 class="text-2xl font-semibold text-gray-800">Hospital-System Dashboard</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <!-- Total Patients -->
                    <div class="ml-2 bg-white rounded-2xl shadow-md hover:shadow-lg transition p-6 flex items-center">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center">
                            <i class="fab fa-accessible-icon text-2xl text-red-500"></i>
                        </div>
                        <div class="ml-auto text-right">
                            <?php
                            $result = "SELECT count(*) FROM hmisphp.his_patients";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($patient);
                            $stmt->fetch();
                            $stmt->close();
                            ?>
                            <h3 class="text-3xl font-bold text-gray-800"
                                data-plugin="counterup"><?php echo $patient; ?></h3>
                            <p class="text-gray-500 text-sm">Total Patients</p>
                        </div>
                    </div>

                    <!-- Waiting -->
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition p-6 flex items-center">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-yellow-100 border-2 border-yellow-500 flex items-center justify-center">
                            <i class="mdi mdi-clock-outline text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-auto text-right">
                            <?php
                            $result = "SELECT COUNT(*) FROM hmisphp.his_patients WHERE pat_type = 'Waiting'";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($choose_count);
                            $stmt->fetch();
                            $stmt->close();
                            ?>
                            <h3 class="text-3xl font-bold text-gray-800"
                                data-plugin="counterup"><?php echo $choose_count; ?></h3>
                            <p class="text-gray-500 text-sm">Waiting</p>
                        </div>
                    </div>

                    <!-- Inpatients -->
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition p-6 flex items-center">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center">
                            <i class="mdi mdi-hospital text-2xl text-red-600"></i>
                        </div>
                        <div class="ml-auto text-right">
                            <?php
                            $result = "SELECT COUNT(*) FROM hmisphp.his_patients WHERE pat_type = 'InPatient'";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($inpatient_count);
                            $stmt->fetch();
                            $stmt->close();
                            ?>
                            <h3 class="text-3xl font-bold text-gray-800"
                                data-plugin="counterup"><?php echo $inpatient_count; ?></h3>
                            <p class="text-gray-500 text-sm">In-Patient</p>
                        </div>
                    </div>

                    <!-- Outpatients -->
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition p-6 flex items-center">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-green-100 border-2 border-green-500 flex items-center justify-center">
                            <i class="mdi mdi-account-outline text-2xl text-green-500"></i>
                        </div>
                        <div class="ml-auto text-right">
                            <?php
                            $result = "SELECT COUNT(*) FROM hmisphp.his_patients WHERE pat_type = 'OutPatient'";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($outpatient_count);
                            $stmt->fetch();
                            $stmt->close();
                            ?>
                            <h3 class="text-3xl font-bold text-gray-800"
                                data-plugin="counterup"><?php echo $outpatient_count; ?></h3>
                            <p class="text-gray-500 text-sm">Out-Patient</p>
                        </div>
                    </div>

                    <!-- Transferred -->
                    <div class="bg-white rounded-2xl w-46 shadow-md hover:shadow-lg transition p-6 flex items-center">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-pink-100 border-2 border-pink-500 flex items-center justify-center">
                            <i class="mdi mdi-exit-run text-2xl text-pink-500"></i>
                        </div>
                        <div class="ml-auto text-right">
                            <?php
                            $result = "SELECT COUNT(*) FROM hmisphp.his_patients WHERE pat_walk_out_date IS NOT NULL";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($transferred_count);
                            $stmt->fetch();
                            $stmt->close();
                            ?>
                            <h3 class="text-3xl font-bold text-gray-800"
                                data-plugin="counterup"><?php echo $transferred_count; ?></h3>
                            <p class="text-gray-500 text-sm leading-tight">Discharged-Patient </p>
                        </div>
                    </div>
                </div>


                <!-- My Profile Card -->
                <!--                            <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-4">-->
                <!--                                <a href="his_doc_account.php">-->
                <!--                                    <div class="bg-white rounded-lg  p-4 hover:shadow-md transition-shadow">-->
                <!--                                        <div class="flex items-center">-->
                <!--                                            <div class="w-1/2">-->
                <!--                                                <div class="w-16 h-16 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center mx-auto">-->
                <!--                                                    <i class="fas fa-user-tag text-xl text-red-500"></i>-->
                <!--                                                </div>-->
                <!--                                            </div>-->
                <!--                                            <div class="w-1/2 text-right">-->
                <!--                                                <h3 class="text-2xl font-semibold text-black"></h3>-->
                <!--                                                <p class="text-black text-sm truncate">My Profile</p>-->
                <!--                                            </div>-->
                <!--                                        </div>-->
                <!--                                    </div>-->
                <!--                                </a>-->
                <!--                            </div>-->

                <!-- My Payroll Card -->
                <!--                            <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-4">-->
                <!--                                <a href="his_doc_view_payrolls.php">-->
                <!--                                    <div class="bg-white rounded-lg  p-4 hover:shadow-md transition-shadow">-->
                <!--                                        <div class="flex items-center">-->
                <!--                                            <div class="w-1/2">-->
                <!--                                                <div class="w-16 h-16 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center mx-auto">-->
                <!--                                                    <i class="mdi mdi-flask text-xl text-red-500"></i>-->
                <!--                                                </div>-->
                <!--                                            </div>-->
                <!--                                            <div class="w-1/2 text-right">-->
                <!--                                                <h3 class="text-2xl font-semibold text-black"></h3>-->
                <!--                                                <p class="text-black text-sm truncate">My Payroll</p>-->
                <!--                                            </div>-->
                <!--                                        </div>-->
                <!--                                    </div>-->
                <!--                                </a>-->
                <!--                            </div>-->
            </div>

            <!-- Patient Records Section -->
            <div class="flex flex-wrap m-2 mt-3 ml-3">
                <div class="w-full">
                    <div class="shadow-sm p-3 mb-5 bg-white rounded">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="mdi mdi-account-multiple text-red-500 text-2xl"></i>
                                Patient Records
                            </h4>
                            <!-- Search -->
<!--                            <div class="form-group">-->
<!--                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">-->
<!--                            </div>-->
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table id="demo-foo-filtering" class="min-w-full divide-y divide-gray-200"
                                   data-page-size="7">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Patient Name
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 hidden sm:table-cell">
                                        Patient Number
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 hidden sm:table-cell">
                                        Address
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 hidden sm:table-cell">
                                        Phone
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 hidden sm:table-cell">
                                        Age
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 hidden sm:table-cell">
                                        Category
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Action</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100 border border-2">
                                <?php
                                $ret = "SELECT * FROM hmisphp.his_patients";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                $cnt = 1;
                                while ($row = $res->fetch_object()) {
                                    ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-sm text-gray-800 border border-gray-200"><?php echo $cnt; ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-800 font-medium border border-gray-200"><?php echo $row->pat_fname; ?><?php echo $row->pat_lname; ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell border border-gray-200"><?php echo $row->pat_number; ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell border border-gray-200"><?php echo $row->pat_addr; ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell border border-gray-200"><?php echo $row->pat_phone; ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell border border-gray-200"><?php echo $row->pat_age; ?>
                                            Years
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <?php
                                            $colors = ['OutPatient' => 'green', 'InPatient' => 'blue', 'Waiting' => 'yellow'];
                                            $color = $colors[$row->pat_type] ?? 'gray';
                                            ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                <?php echo $row->pat_type; ?>
            </span>
                                        </td>

                                        <td class="px-4 py-3 text-center border border-gray-200">
                                            <a href="his_doc_view_single_patient.php?pat_id=<?php echo $row->pat_id; ?>&&pat_number=<?php echo $row->pat_number; ?>&&pat_name=<?php echo $row->pat_fname; ?>_<?php echo $row->pat_lname; ?>"
                                               class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition">
                                                <i class="mdi mdi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>

                                    <?php $cnt++;
                                } ?>
                                </tbody>
                                <tfoot>
                                <tr class="bg-gray-50">
                                    <td colspan="8" class="px-4 py-3">
                                        <div class="flex justify-end">
                                            <ul class="flex space-x-1 pagination pagination-rounded footable-pagination mt-2 mb-0"></ul>
                                        </div>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

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
                <img src="assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme"
                     class="rounded-circle img-fluid">
                <a href="javascript:void(0);" class="user-edit"><i class="mdi mdi-pencil"></i></a>
            </div>

            <h5><a href="javascript: void(0);">Geneva Kennedy</a></h5>
            <p class="text-muted mb-0"><small>Admin Head</small></p>
        </div>

        <!-- Settings -->
        <hr class="mt-0"/>
        <h5 class="pl-3">Basic Settings</h5>
        <hr class="mb-0"/>

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
        <hr class="mt-0"/>
        <h5 class="px-3">Messages <span class="float-right badge badge-pill badge-danger">25</span></h5>
        <hr class="mb-0"/>
        <div class="p-3">
            <div class="inbox-widget">
                <div class="inbox-item">
                    <div class="inbox-item-img"><img src="assets/images/users/user-2.jpg" class="rounded-circle" alt="">
                    </div>
                    <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Tomaslau</a></p>
                    <p class="inbox-item-text">I've finished it! See you so...</p>
                </div>
                <div class="inbox-item">
                    <div class="inbox-item-img"><img src="assets/images/users/user-3.jpg" class="rounded-circle" alt="">
                    </div>
                    <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Stillnotdavid</a></p>
                    <p class="inbox-item-text">This theme is awesome!</p>
                </div>
                <div class="inbox-item">
                    <div class="inbox-item-img"><img src="assets/images/users/user-4.jpg" class="rounded-circle" alt="">
                    </div>
                    <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Kurafire</a></p>
                    <p class="inbox-item-text">Nice to meet you</p>
                </div>

                <div class="inbox-item">
                    <div class="inbox-item-img"><img src="assets/images/users/user-5.jpg" class="rounded-circle" alt="">
                    </div>
                    <p class="inbox-item-author"><a href="javascript: void(0);" class="text-dark">Shahedk</a></p>
                    <p class="inbox-item-text">Hey! there I'm available...</p>
                </div>
                <div class="inbox-item">
                    <div class="inbox-item-img"><img src="assets/images/users/user-6.jpg" class="rounded-circle" alt="">
                    </div>
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
<script src="assets/js/pages/foo-tables.init.js"></script>
<script src="assets/libs/footable/footable.all.min.js"></script>


<!-- App js-->
<script src="assets/js/app.min.js"></script>

</body>

</html>