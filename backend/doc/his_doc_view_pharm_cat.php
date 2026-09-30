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

    <body style="color: black">

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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmaceuticals</a></li>
                                            <li class="breadcrumb-item active">View Pharmaceutical Category</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Pharmaceutical Categories</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="flex flex-wrap">
                            <div class="w-full">
                                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                                    <h4 class="text-lg font-semibold mb-4"></h4>
                                    <div class="mb-4">
                                        <div class="flex flex-wrap">
                                            <div class="w-full text-center sm:text-left flex flex-col sm:flex-row items-center">
                                                <div class="mr-2 hidden">
                                                    <select id="demo-foo-filter-status" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                                                        <option value="">Show all</option>
                                                        <option value="Discharged">Discharged</option>
                                                        <option value="OutPatients">OutPatients</option>
                                                        <option value="InPatients">InPatients</option>
                                                    </select>
                                                </div>
                                                <div class="mt-2 sm:mt-0">
                                                    <input id="demo-foo-search" type="text" placeholder="Search" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500" autocomplete="on">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table id="demo-foo-filtering" class="w-full border-collapse border border-gray-200" data-page-size="7">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="border border-gray-200 px-4 py-2">#</th>
                                                <th class="border border-gray-200 px-4 py-2" data-toggle="true">Category Name</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Category Vendor</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Action</th>
                                            </tr>
                                            </thead>
                                            <?php
                                            /*
                                                *get details of allpatients
                                                *
                                            */
                                            $ret="SELECT * FROM  hmisphp.his_pharmaceuticals_categories";
                                            $stmt= $mysqli->prepare($ret) ;
                                            $stmt->execute() ;//ok
                                            $res=$stmt->get_result();
                                            $cnt=1;
                                            while($row=$res->fetch_object())
                                            {
                                                ?>

                                                <tbody>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="border border-gray-200 px-4 py-2"><?php echo $cnt;?></td>
                                                    <td class="border border-gray-200 px-4 py-2"><?php echo $row->pharm_cat_name;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pharm_cat_vendor;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell">
                                                        <a href="his_doc_view_single_pharm_category.php?pharm_cat_id=<?php echo $row->pharm_cat_id;?>" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded inline-flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <?php  $cnt = $cnt +1 ; }?>
                                            <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="8" class="border border-gray-200 px-4 py-2">
                                                    <div class="flex justify-end">
                                                        <ul class="flex space-x-1 pagination pagination-rounded justify-end footable-pagination mt-2 mb-0"></ul>
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