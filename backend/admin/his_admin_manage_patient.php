<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "delete from hmisphp.his_patients where pat_id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        $success = "Patients Records Deleted";
    } else {
        $err = "Try Again Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<?php include('assets/inc/head.php'); ?>

<body style="color: black">

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?php include('assets/inc/nav.php'); ?>
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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">Manage Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Manage Patient Details</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title"></h4>
                            <div class="mb-2">
                                <div class="row">
                                    <div class="col-12 text-sm-center form-inline">
                                        <div class="form-group mr-2" style="display:none">
                                            <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                <option value="">Show all</option>
                                                <option value="Discharged">Discharged</option>
                                                <option value="OutPatients">OutPatients</option>
                                                <option value="InPatients">InPatients</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <input id="demo-foo-search" type="text" placeholder="Search"
                                                   autocomplete="on"
                                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                bg-white text-gray-700">
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering"
                                       class="min-w-full border border-gray-500 text-black mb-4 "
                                       data-page-size="7">
                                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold">
                                    <tr>
                                        <th class="px-4 py-2 text-left">#</th>
                                        <th class="px-4 py-2 text-left">Patient</th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">Number Patient</th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">Address</th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">Status Patient</th>
                                        <th class="px-4 py-2 text-left">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cnt = 1;
                                    // Get all patients, newest first
                                    $ret = "SELECT * FROM hmisphp.his_patients ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    // Get total rows
                                    $total = $res->num_rows;
                                    $cnt = $total; // Start numbering from total
                                    while ($row = $res->fetch_object()) {
                                    ?>
                                    <tr class="border-t border-gray-200 hover:bg-gray-50">
                                        <td class="px-4 py-2"><?php echo $cnt; ?></td>
                                        <td class="px-4 py-2"><?php echo $row->pat_fname . ' ' . $row->pat_lname; ?></td>
                                        <td class="px-4 py-2 hidden sm:table-cell"><?php echo $row->pat_number; ?></td>
                                        <td class="px-4 py-2 hidden sm:table-cell"><?php echo $row->pat_addr; ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <?php if ($row->pat_type == 'OutPatient'): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
            OutPatient
        </span>
                                            <?php elseif ($row->pat_type == 'InPatient'): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
            InPatient
        </span>
                                            <?php elseif ($row->pat_type == 'Waiting'): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
            Waiting
        </span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
            <?php echo $row->pat_type; ?>
        </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-4 py-2">
                                            <div class="flex flex-nowrap gap-2">
                                                <a href="his_admin_manage_patient.php?delete=<?php echo $row->pat_id; ?>"
                                                   class="px-2 py-1 bg-red-500 text-white rounded
                  hover:bg-red-600
                  text-xs sm:text-sm
                  flex items-center transition">
                                                    <i class="mdi mdi-trash-can-outline mr-1"></i> Delete
                                                </a>
                                                <a href="his_admin_view_single_patient.php?pat_id=<?php echo $row->pat_id; ?>&&pat_number=<?php echo $row->pat_number; ?>"
                                                   class="px-2 py-1 bg-blue-500 text-white rounded
                  hover:bg-blue-600
                  text-xs sm:text-sm
                  flex items-center transition">
                                                    <i class="mdi mdi-eye mr-1"></i> View
                                                </a>
                                                <a href="his_admin_update_single_patient.php?pat_id=<?php echo $row->pat_id; ?>"
                                                   class="px-2 py-1 bg-yellow-500 text-white rounded
                  hover:bg-yellow-600
                  text-xs sm:text-sm
                  flex items-center transition">
                                                    <i class="mdi mdi-check-box-outline mr-1"></i> Update
                                                </a>
                                            </div>
                                        </td>


                                    </tr>
                                    </tbody>
                                    <?php
                                    $cnt--; // Decrement for next row
                                    }
                                    ?>
                                    <tfoot>
                                    <tr class="active">
                                        <td colspan="8">
                                            <div class="text-right">
                                                <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                            </div>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div> <!-- end card-box -->
                    </div> <!-- end col -->
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


<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="assets/js/vendor.min.js"></script>

<!-- Footable js -->
<script src="assets/libs/footable/footable.all.min.js"></script>

<!-- Init js -->
<script src="assets/js/pages/foo-tables.init.js"></script>

<!-- App js -->
<script src="assets/js/app.min.js"></script>

</body>

</html>