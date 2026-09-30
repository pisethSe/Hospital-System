<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    
<?php include ('assets/inc/head.php');?>

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
            <?php
                $pres_number=$_GET['pres_number'];
                $pres_id = $_GET['pres_id'];
                $ret="SELECT  * FROM hmisphp.his_prescriptions WHERE pres_number = ? AND pres_id = ?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('ii',$pres_number,$pres_id);
                //$stmt->bind_param('i',$pres_id);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                //$cnt=1;
                while($row=$res->fetch_object())
                {
            ?>

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
                                                <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmaceuticals</a></li>
                                                <li class="breadcrumb-item active">View Prescriptions</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">#<?php echo $row->pres_number;?></h4>
                                    </div>
                                </div>
                            </div>     
                            <!-- end page title --> 

                            <div class="row">
                                <div class="col-12">
                                    <div class="max-w-4xl mx-auto bg-gradient-to-b from-white to-blue-50 rounded-2xl p-8 print:p-10 print:rounded-none print:bg-white border border-blue-100 print:text-[18px]">

                                        <!-- Header: Logo & Clinic Info -->
                                        <div class="flex justify-between items-center mb-8">
                                            <div class="flex items-center gap-4">
                                                <img src="./assets/images/logo.png" alt="Logo"
                                                     class="h-20 w-20 object-contain rounded-full border-2 border-blue-200">
                                                <div>
                                                    <h1 class="text-3xl font-bold text-blue-800">Happy Health Clinic</h1>
                                                    <p class="text-lg text-blue-500">123 Wellness St, City, Country</p>
                                                </div>
                                            </div>

                                            <!-- Print Button -->
                                            <button onclick="window.print()"
                                                    class="flex items-center gap-1 px-3 py-1 bg-gray-400 hover:bg-gray-500 text-white font-medium rounded-md shadow-sm print:hidden transition transform hover:-translate-y-0.5 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                                                     viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>

                                        </div>

                                        <!-- Patient Info -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 text-lg">
                                            <div>
                                                <h2 class="text-base font-semibold text-blue-600">Name</h2>
                                                <p class="text-2xl font-medium text-blue-900"><?= htmlspecialchars($row->pres_pat_name); ?></p>
                                            </div>
                                            <div>
                                                <h2 class="text-base font-semibold text-blue-600">Age</h2>
                                                <p class="text-2xl font-medium text-red-500"><?= htmlspecialchars($row->pres_pat_age); ?>
                                                    Years</p>
                                            </div>
                                            <div>
                                                <h2 class="text-base font-semibold text-blue-600">Patient Number</h2>
                                                <p class="text-2xl font-medium text-red-500"><?= htmlspecialchars($row->pres_pat_number); ?></p>
                                            </div>
                                            <div>
                                                <h2 class="text-base font-semibold text-blue-600">Category</h2>
                                                <span class="inline-block px-4 py-2 rounded-full text-white font-medium text-lg
        <?php if ($row->pres_pat_type == "Waiting") {
                                                    echo 'bg-yellow-400';
                                                } elseif ($row->pres_pat_type == "InPatient") {
                                                    echo 'bg-green-400';
                                                } else {
                                                    echo 'bg-blue-400';
                                                } ?>">
        <?= htmlspecialchars($row->pres_pat_type); ?>
      </span>
                                            </div>
                                            <div class="md:col-span-2">
                                                <h2 class="text-base font-semibold text-blue-600">Ailment / Room</h2>
                                                <p class="text-2xl font-medium text-red-500"><?= htmlspecialchars($row->pres_pat_ailment ?? 'NA'); ?></p>
                                            </div>
                                        </div>

                                        <hr class="my-6 border-blue-200">

                                        <!-- Prescription Instructions -->
                                        <div>
                                            <h2 class="text-2xl font-bold text-center text-blue-700 mb-4 border-b border-blue-200 pb-2">
                                                Prescription</h2>
                                            <p class="text-sm text-black whitespace-pre-wrap leading-relaxed">
                                                <?= ($row->pres_ins); ?>
                                            </p>
                                        </div>

                                        <!-- Footer: Doctor / Date -->
                                        <div class="mt-12 flex justify-between items-center print:mt-8 text-lg">
                                            <div>
                                                <p class="text-blue-700 font-medium">Doctor: ____________________</p>
                                            </div>
                                            <div>
<!--                                                <p class="text-blue-700 font-medium">Date: --><?php //= (new DateTime($row->pres_date))->format('Y-m-d H:i'); ?><!--</p>-->
                                                <p class="text-blue-700 font-medium">Date: <?= date('F j, Y g:i A', strtotime($row->pres_date)); ?></p>
                                                <!-- Output: August 30, 2025 1:31 PM -->
                                            </div>
                                        </div>

                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                            </div>
                            <!-- end row-->
                            
                        </div> <!-- container -->

                    </div> <!-- content -->

                    <!-- Footer Start -->
                        <?php include('assets/inc/footer.php');?>
                    <!-- end Footer -->

                </div>
            <?php }?>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>