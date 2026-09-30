<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  //$aid=$_SESSION['ad_id'];
  $doc_id = $_SESSION['doc_id'];
  /*
  Doctor has no previledges to delete a patient record
  if(isset($_GET['delete']))
  {
        $id=intval($_GET['delete']);
        $adn="delete from his_patients where pat_id=?";
        $stmt= $mysqli->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();	 
  
          if($stmt)
          {
            $success = "Patients Records Deleted";
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

    <body style="color:black;">

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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                            <li class="breadcrumb-item active">Manage Patients</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage Patient Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h4 class="text-lg font-semibold mb-4"></h4>
                            <div class="mb-4">
                                <div class="flex flex-wrap -mx-2">
                                    <div class="w-full px-2 text-center sm:text-left flex flex-col sm:flex-row items-center gap-2">
                                        <div class="hidden">
                                            <label for="demo-foo-filter-status"></label><select id="demo-foo-filter-status" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                                                <option value="">Show all</option>
                                                <option value="Discharged">Discharged</option>
                                                <option value="OutPatients">OutPatients</option>
                                                <option value="InPatients">InPatients</option>
                                            </select>
                                        </div>
                                        <div>
                                            <input id="demo-foo-search" type="text" placeholder="Search" class="border border-gray-300 rounded-md px-3 py-1 text-sm" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table id="demo-foo-filtering" class="w-full border-collapse border border-gray-200" data-page-size="7">
                                    <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-200 px-4 py-2">#</th>
                                        <th class="border border-gray-200 px-4 py-2" data-toggle="true">Patient Name</th>
                                        <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Patient Number</th>
                                        <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Patient Address</th>
                                        <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Patient Status</th>
                                        <th class="border border-gray-200 px-4 py-2 hidden sm:table-cell">Action</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    /*
                                      *get details of allpatients
                                      *
                                    */
                                    $ret="SELECT * FROM  hmisphp.his_patients ORDER BY pat_id DESC ";
                                    //sql code to get to ten docs  randomly
                                    $stmt= $mysqli->prepare($ret) ;
                                    $stmt->execute() ;//ok
                                    $res=$stmt->get_result();
                                    $cnt=1;
                                    while($row=$res->fetch_object())
                                    {
                                        ?>
                                        <tbody>
                                        <tr>
                                            <td class="border border-gray-200 px-4 py-2"><?php echo $cnt;?></td>
                                            <td class="border border-gray-200 px-4 py-2"><?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?></td>
                                            <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pat_number;?></td>
                                            <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php echo $row->pat_addr;?></td>
                                            <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell"><?php
                                                $colors = ['OutPatient' => 'green', 'InPatient' => 'blue', 'Waiting' => 'yellow'];
                                                $color = $colors[$row->pat_type] ?? 'gray';
                                                ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                                                <?php echo $row->pat_type; ?></td>

                                            <td class="border border-gray-200 px-4 py-2 hidden sm:table-cell">
                                                <a href="his_doc_view_single_patient.php?pat_id=<?php echo $row->pat_id;?>&&pat_number=<?php echo $row->pat_number;?>" class="bg-blue-500 text-white px-2 py-1 rounded text-xs inline-block mr-1">
                                                    View
                                                </a>
                                                <a href="his_doc_update_single_patient.php?pat_id=<?php echo $row->pat_id; ?>&pat_number=<?php echo $row->pat_number; ?>"
                                                   class="bg-yellow-500 text-white px-2 py-1 rounded text-xs inline-block">
                                                    Update
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php  $cnt = $cnt +1 ; }?>
                                    <tfoot>
                                    <tr class="bg-gray-100">
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