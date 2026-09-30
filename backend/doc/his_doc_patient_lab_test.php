<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$doc_id = $_SESSION['doc_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>view patients</title>
</head>
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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                    <li class="breadcrumb-item active">Add Laboratory Test</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Laboratory Tests</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                            <h4 class="text-lg font-semibold mb-4"></h4>
                            <div class="mb-4">
                                <div class="flex flex-wrap items-center justify-between">
                                    <div class="w-full md:w-auto flex items-center space-x-2 mb-2 md:mb-0">
                                        <div class="form-group mr-2 hidden">
                                            <select id="demo-foo-filter-status"
                                                    class="custom-select custom-select-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Show all</option>
                                                <option value="Discharged">Discharged</option>
                                                <option value="OutPatients">OutPatients</option>
                                                <option value="InPatients">InPatients</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <input
                                                    id="demo-foo-search"
                                                    type="text"
                                                    placeholder="Search"
                                                    autocomplete="on"
                                                    class="w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            >
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table id="demo-foo-filtering" class="min-w-full border border-gray-200 mb-4" data-page-size="7">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b">#</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b" data-toggle="true">Patient Name</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b hidden sm:table-cell">Patient Number</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b hidden md:table-cell">Patient Address</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b hidden sm:table-cell">Patient Ailment</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b hidden xs:table-cell">Patient Age</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b hidden sm:table-cell">Patient Category</th>
                                        <th class="px-3 py-2 sm:px-4 sm:py-2 text-left text-xs font-medium text-black uppercase tracking-wider border-b">Action</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    /*
                                        *get details of allpatients
                                        *
                                    */
                                    $ret = "SELECT * FROM  hmisphp.his_patients  ";
                                    //sql code to get to ten docs  randomly
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();//ok
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        ?>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b"><?php echo $cnt; ?></td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm font-medium text-black border-b">
                                                <?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>
                                            </td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b hidden sm:table-cell">
                                                <?php echo $row->pat_number; ?>
                                            </td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b hidden md:table-cell">
                                                <?php echo $row->pat_addr; ?>
                                            </td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b hidden sm:table-cell">
                                                <?php echo $row->pat_ailment; ?>
                                            </td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b hidden xs:table-cell">
                                                <?php echo $row->pat_age; ?> Years
                                            </td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b hidden sm:table-cell">
                                                <?php echo $row->pat_type; ?>
                                            </td>
                                            <td class="px-3 py-2 sm:px-4 sm:py-2 whitespace-nowrap text-sm text-black border-b">
                                                <a href="his_doc_add_single_lab_test.php?pat_number=<?php echo $row->pat_number; ?>"
                                                   class="inline-flex items-center px-2 py-1 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-white" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    <span class="hidden xs:inline">Add Lab Test</span>
                                                    <span class="xs:hidden">Add</span>
                                                </a>

                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php $cnt = $cnt + 1;
                                    } ?>
                                    <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="8" class="px-4 py-3 text-right">
                                            <div class="flex justify-end">
                                                <ul class="pagination pagination-rounded justify-end footable-pagination mt-2 mb-0 flex space-x-1"></ul>
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