<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

$doc_id = $_SESSION['doc_id'];

/* 
   Doctors cannot delete pharmaceutical category records.
   Uncomment the code below if you want to allow deletion.

   if(isset($_GET['delete_pharm_cat'])) {
        $id = intval($_GET['delete_pharm_cat']);
        $adn = "DELETE FROM his_pharmaceuticals_categories WHERE pharm_cat_id=?";
        $stmt = $mysqli->prepare($adn);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();	 

        if($stmt) {
            $success = "Pharmaceutical Category Record Deleted";
        } else {
            $err = "Try Again Later";
        }
    }
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manage Pharmaceutical Categories</title>
</head>
<?php include('assets/inc/head.php'); ?>

<body class="text-black">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- End Topbar -->

        <!-- Sidebar -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- End Sidebar -->

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">

                <div class="container-fluid">

                   <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li>
                                            <li class="breadcrumb-item active">Manage Pharmaceutical Categories</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Pharmaceutical Categories </h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                    <!-- Table Section -->
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full px-4">
                            <div class="bg-white rounded-lg shadow-md p-6 mb-6">

                                <!-- Search Bar -->
                                <div class="mb-4 flex justify-between flex-wrap items-center">
                                    <div class="hidden">
                                        <select id="demo-foo-filter-status" 
                                            class="border border-gray-300 rounded px-3 py-1 text-sm 
                                                   focus:outline-none focus:ring-2 focus:ring-blue-200 
                                                   focus:border-blue-500 text-black">
                                            <option value="">Show all</option>
                                            <option value="Discharged">Discharged</option>
                                            <option value="OutPatients">OutPatients</option>
                                            <option value="InPatients">InPatients</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input id="demo-foo-search" type="text" placeholder="Search" 
                                            class="border border-gray-300 rounded px-3 py-1 text-sm 
                                                   focus:outline-none focus:ring-2 focus:ring-blue-200 
                                                   focus:border-blue-500 text-black" autocomplete="on">
                                    </div>
                                </div>

                                <!-- Table -->
                                <div class="overflow-x-auto">
                                    <table id="demo-foo-filtering" 
                                           class="w-full border-collapse border border-gray-200" 
                                           data-page-size="7">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="border px-4 py-2">#</th>
                                                <th class="border px-4 py-2" data-toggle="true">Category Name</th>
                                                <th class="border px-4 py-2 hidden sm:table-cell">Category Vendor</th>
                                                <th class="border px-4 py-2 hidden sm:table-cell">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM hmisphp.his_pharmaceuticals_categories ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;

                                            while($row = $res->fetch_object()) { ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="border px-4 py-2"><?php echo $cnt; ?></td>
                                                    <td class="border px-4 py-2"><?php echo $row->pharm_cat_name; ?></td>
                                                    <td class="border px-4 py-2 hidden sm:table-cell"><?php echo $row->pharm_cat_vendor; ?></td>
                                                    <td class="border px-4 py-2 hidden sm:table-cell">
                                                        <div class="flex flex-wrap gap-2">
                                                            <!-- View Button -->
                                                            <a href="his_doc_view_single_pharm_category.php?pharm_cat_id=<?php echo $row->pharm_cat_id; ?>" 
                                                                class="flex items-center justify-center px-3 py-1 rounded text-sm font-medium 
                                                                       bg-blue-500 text-white hover:bg-blue-600">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 
                                                                           8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 
                                                                           7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                View
                                                            </a>

                                                            <!-- Update Button -->
                                                            <a href="his_doc_update_single_pharm_category.php?pharm_cat_name=<?php echo $row->pharm_cat_name; ?>" 
                                                                class="flex items-center justify-center px-3 py-1 rounded text-sm font-medium 
                                                                       bg-yellow-500 text-white hover:bg-yellow-600">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 
                                                                           2h11a2 2 0 002-2v-5m-1.414-9.414a2 
                                                                           2 0 112.828 2.828L11.828 15H9v-2.828
                                                                           l8.586-8.586z"/>
                                                                </svg>
                                                                Update
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php $cnt++; } ?>
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="4" class="border px-4 py-2">
                                                    <div class="flex justify-end">
                                                        <ul class="pagination pagination-rounded flex space-x-1 mt-2 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- End Table -->

                            </div>
                        </div>
                    </div>
                    <!-- End Table Section -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- End Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
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
