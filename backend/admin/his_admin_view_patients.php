<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>

<!DOCTYPE html>
<html lang="en">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">View Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Patient Details</h4>
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
                                            <input type="text"
                                                   id="demo-foo-search"
                                                   placeholder="Search"
                                                   autocomplete="on"
                                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering"
                                       class="min-w-full border border-black text-black mb-4 rounded-lg "
                                       data-page-size="7">
                                    <thead class="bg-gray-100 text-gray-700  text-sm font-semibold">
                                    <tr>
                                        <th class="px-4 py-2 text-left">No</th>
                                        <th class="px-4 py-2 text-left">Name</th>
                                        <th class="px-4 py-2 text-left text-wrap">Number Patient</th>
                                        <th class="px-4 py-2 text-left text-wrap">Patient Ailment </th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">Address</th>
                                        <th class="px-4 py-2 text-left hidden sm:table-cell">Phone</th>
                                        <th class="px-4 py-2 text-left">Age</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                        <th class="px-4 py-2 text-left">Date In</th>
                                        <!--                                                <th data-hide="phone">Action</th>-->
                                    </tr>
                                    </thead>
                                    <?php
                                    $ret = "SELECT * FROM hmisphp.his_patients ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    // Get total rows
                                    $total = $res->num_rows;
                                    $cnt = $total; // Start numbering from total

                                    while ($row = $res->fetch_object()) {
                                        ?>
                                        <tbody class="border border-gray-300">
                                        <tr class="hover:bg-gray-100 text-black border-b border-gray-300">
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $cnt; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pat_fname . ' ' . $row->pat_lname; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pat_number; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300">
                                                <?php echo !empty($row->pat_ailment) ? $row->pat_ailment : 'NA'; ?>
                                            </td>


                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pat_addr; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pat_phone; ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300"><?php echo $row->pat_age; ?> Years</td>
                                            <td class="px-4 py-2 whitespace-nowrap border border-gray-300">
                                                <?php
                                                $colors = [
                                                        'OutPatient' => 'green',
                                                        'InPatient' => 'blue',
                                                        'Waiting' => 'yellow'
                                                ];
                                                $color = $colors[$row->pat_type] ?? 'gray';
                                                ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                <?php echo $row->pat_type; ?>
            </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm hidden sm:table-cell"><?php echo date("d-M-Y h:iA", strtotime($row->pat_date_joined)); ?></td>


                                        </tr>
                                        </tbody>

                                        <?php
                                        $cnt--; // Decrement for next row
                                    }
                                    ?>


                                    <tfoot>
                                    <tr class="active">
                                        <?php $total_patients = $res->num_rows; ?>
                                        <div class="text-black font-bold mb-8">
                                            <?php echo "Total patients: " . $total_patients; ?>
                                        </div>
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