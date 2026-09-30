<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
if (isset($_GET['delete_pres_number'])) {
    $id = $_GET['delete_pres_number']; // keep as string
    $adn = "DELETE FROM hmisphp.his_prescriptions WHERE pres_number=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id); // 's' for string
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        $success = "Prescription Records Deleted";
    } else {
        $err = "Try Again Later";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmacy</a></li>
                                    <li class="breadcrumb-item active">Manage Prescriptions</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Manage Prescriptions</h4>
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
                                        <div class="mb-4">
                                            <input id="demo-foo-search" type="text" placeholder="Search"
                                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                                                   autocomplete="on">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0"
                                       data-page-size="7">
                                    <thead class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold">
                                    <tr>
                                        <th class="px-4 py-2 text-left">#</th>
                                        <th class="px-4 py-2 text-left">Patient Name
                                        </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">
                                            Patient Number
                                        </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">
                                            Address
                                        </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">
                                            Ailment
                                        </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">
                                            Age
                                        </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">
                                            Category
                                        </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">
                                            Action
                                        </th>
                                    </tr>
                                    </thead>

                                    <?php
                                    /*
                                        *get details of allpatients
                                        *
                                    */
                                    $ret = "SELECT * FROM  hmisphp.his_prescriptions ORDER BY RAND() ";
                                    //sql code to get to ten docs  randomly
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();//ok
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        ?>

                                        <tbody class="border border-gray-300 ">
                                        <tr class="hover:bg-gray-100 text-black border-b border-gray-300">
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $cnt; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pres_pat_name; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pres_pat_number; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pres_pat_addr; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pres_pat_ailment; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pres_pat_age; ?>
                                                Years
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300">
                                                <?php
                                                $colors = ['OutPatient' => 'green', 'InPatient' => 'blue', 'Waiting' => 'yellow'];
                                                $color = $colors[$row->pres_pat_type] ?? 'gray';
                                                ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                <?php echo $row->pres_pat_type; ?>
            </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                <div class="flex flex-wrap gap-2">
                                                    <!-- View -->
                                                    <a href="his_admin_view_single_pres.php?pres_number=<?php echo $row->pres_number; ?>"
                                                       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i> View
                                                    </a>

                                                    <!-- Update -->
                                                    <a href="his_admin_upate_single_pres.php?pres_number=<?php echo $row->pres_number; ?>"
                                                       class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-600">
                                                        <i class="fas fa-eye-dropper mr-1"></i> Update
                                                    </a>

                                                    <!-- Delete -->
                                                    <a href="his_admin_manage_presc.php?delete_pres_number=<?php echo $row->pres_number; ?>"
                                                       class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">
                                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                        </tbody>

                                        <?php $cnt = $cnt + 1;
                                    } ?>
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