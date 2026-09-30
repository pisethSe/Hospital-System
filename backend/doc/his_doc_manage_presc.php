<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $doc_id = $_SESSION['doc_id'];
  /*
  Doc cant delete a prescription
  Uncomment the code to enable
  if(isset($_GET['delete_pres_number']))
  {
        $id=intval($_GET['delete_pres_number']);
        $adn="DELETE FROM his_prescriptions WHERE pres_number=?";
        $stmt= $mysqli->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();	 
  
          if($stmt)
          {
            $success = "Prescription Records Deleted";
          }
            else
            {
                $err = "Try Again Later";
            }
    }
    */
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmacy</a></li>
                                            <li class="breadcrumb-item active">Manage Prescriptions</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Prescriptions</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="flex flex-wrap -mx-4">
                            <div class="w-full px-4">
                                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                                    <div class="mb-4">
                                        <div class="flex flex-wrap -mx-2">
                                            <div class="w-full px-2 text-center sm:text-left flex flex-col sm:flex-row items-center">
                                                <!-- Hidden filter dropdown -->
                                                <div class="mr-2 hidden">
                                                    <select id="demo-foo-filter-status" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                                                        <option value="">Show all</option>
                                                        <option value="Discharged">Discharged</option>
                                                        <option value="OutPatients">OutPatients</option>
                                                        <option value="InPatients">InPatients</option>
                                                    </select>
                                                </div>
                                                <!-- Search input -->
                                                <div class="mt-2 sm:mt-0">
                                                    <input id="demo-foo-search" type="text" placeholder="Search prescriptions..." class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500" autocomplete="on">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table id="demo-foo-filtering" class="w-full border-collapse border border-gray-200" data-page-size="7">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="border border-gray-200 px-4 py-2">#</th>
                                                <th class="border border-gray-200 px-4 py-2" data-toggle="true">Patient Name</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Patient Number</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Address</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Ailment</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Age</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Category</th>
                                                <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Action</th>
                                            </tr>
                                            </thead>
                                            <?php
                                            /*
                                                *get details of all prescriptions
                                                *
                                            */
                                            $ret="SELECT * FROM  hmisphp.his_prescriptions";
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
                                                    <td class="border border-gray-200 px-4 py-2"><?php echo $row->pres_pat_name;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pres_pat_number;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pres_pat_addr;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pres_pat_ailment;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pres_pat_age;?> Years</td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php
                                                        $colors = ['OutPatient' => 'green', 'InPatient' => 'blue', 'Waiting' => 'yellow'];
                                                        $color = $colors[$row->pres_pat_type] ?? 'gray';
                                                        ?>
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800"><?php echo $row->pres_pat_type;?></td>
                                                    <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell space-x-2">
                            <div class="flex flex-wrap gap-2">
                                <!-- View button (blue) -->
                                <a href="his_doc_view_single_pres.php?pres_number=<?php echo $row->pres_number;?>&&pres_id=<?php echo $row->pres_id;?>"
                                class="flex items-center justify-center px-2 py-1 rounded text-sm font-medium 
                                        bg-blue-500 text-white hover:bg-blue-600 
                                        w-full sm:w-auto sm:px-4 sm:py-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 
                                                8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 
                                                7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>

                                <!-- Update button (yellow) -->
                                <a href="his_doc_upate_single_pres.php?pres_number=<?php echo $row->pres_number;?>"
                                class="flex items-center justify-center px-2 py-1 rounded text-sm font-medium 
                                        bg-yellow-500 text-white hover:bg-yellow-600 
                                        w-full sm:w-auto sm:px-4 sm:py-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 
                                                2.5 0 113.536 3.536L6.5 21.036H3v-3.572 
                                                L16.732 3.732z"></path>
                                    </svg>
                                    Update
                                </a>
                            </div>


                                                        <!--
                                <a href="his_admin_manage_presc.php?delete_pres_number=<?php echo $row->pres_number;?>" class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </a>
                                -->
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