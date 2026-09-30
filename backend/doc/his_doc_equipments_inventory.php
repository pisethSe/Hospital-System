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
<?php include('assets/inc/head.php');?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
                <?php include('assets/inc/nav.php');?>
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                                            <li class="breadcrumb-item active">Equipments | Assets Inventory</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 


                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <h4 class="header-title">Assets | Equipments Inventory</h4>
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-12 text-sm-center form-inline" >
                                                <div class="form-group mr-2" style="display:none">
                                                    <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                        <option value="">Show all</option>
                                                        <option value="Discharged">Discharged</option>
                                                        <option value="OutPatients">OutPatients</option>
                                                        <option value="InPatients">InPatients</option>
                                                    </select>
                                                </div>
                                                <div class="">
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

                                    <div class="overflow-x-auto text-sm">
                                        <table id="demo-foo-filtering" class="w-full border-collapse" data-page-size="7">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-2 py-2 ">#</th>
                                                <th class="px-2 py-2 ">Name</th>
                                                <th class="px-2 py-2 ">Code</th>
                                                <th class="px-2 py-2 ">Vendor</th>
                                                <th class="px-2 py-2 ">Department</th>
                                                <th class="px-2 py-2 ">Qty</th>
                                                <th class="px-2 py-2 ">Action</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                            $ret = "SELECT * FROM hmisphp.his_equipments ORDER BY RAND()";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while($row = $res->fetch_object()) {
                                                ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-2 py-2 text-black border-b"><?php echo $cnt;?></td>
                                                    <td class="px-2 py-2 font-medium text-black border-b"><?php echo $row->eqp_name; ?></td>
                                                    <td class="px-2 py-2 text-black border-b hidden sm:table-cell"><?php echo $row->eqp_code; ?></td>
                                                    <td class="px-2 py-2 text-black border-b hidden sm:table-cell"><?php echo $row->eqp_vendor; ?></td>
                                                    <td class="px-2 py-2 text-black border-b hidden sm:table-cell"><?php echo $row->eqp_dept; ?></td>
                                                    <td class="px-2 py-2 text-black border-b hidden sm:table-cell"><?php echo $row->eqp_qty; ?></td>
                                                    <td class="px-2 py-2 text-black border-b hidden sm:table-cell">
                                                        <a href="his_doc_view_single_eqp.php?eqp_code=<?php echo urlencode($row->eqp_code); ?>"
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue text-white hover:bg-gray-100">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View
                                                    </a>


                                                    </td>
                                                </tr>
                                                <?php $cnt = $cnt + 1; } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="7" class="px-2 py-2 border-t">
                                                    <div class="flex justify-end">
                                                        <ul class="flex space-x-1 pagination pagination-rounded justify-end footable-pagination text-xs"></ul>
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

        <!-- Footable js -->
        <script src="assets/libs/footable/footable.all.min.js"></script>

        <!-- Init js -->
        <script src="assets/js/pages/foo-tables.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>