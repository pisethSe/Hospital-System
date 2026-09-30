<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $doc_id=$_SESSION['doc_id'];
  /*
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
            $success = "Vehicle Removed";
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                            <li class="breadcrumb-item active">Discharge Patients</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Discharge Patients</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="flex flex-wrap -mx-4">
                            <div class="w-full px-4">
                                <div class="bg-white rounded-lg shadow-md p-6">
                                    <h4 class="text-lg font-semibold mb-4 text-black">Patient Management System</h4>

                                    <!-- Search and Filter Section -->
                                    <div class="mb-4">
                                        <div class="flex flex-wrap -mx-2">
                                            <div class="w-full px-2 flex flex-col sm:flex-row items-center gap-2">
                                                <div class="w-full sm:w-auto">
                                                    <input id="patient-search" type="text"
                                                           placeholder="Search patients..."
                                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm text-black">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Patients Table -->
                                    <div class="overflow-x-auto">
                                        <table class="w-full border-collapse border border-gray-200">
                                            <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left">ID
                                                </th>
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left">
                                                    Patient Name
                                                </th>
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left hidden md:table-cell">
                                                    Contact
                                                </th>
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left hidden lg:table-cell">
                                                    Address
                                                </th>
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left">Type
                                                </th>
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left">
                                                    Status
                                                </th>
                                                <th class="border border-gray-200 px-4 py-2 text-black text-left">
                                                    Actions
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $query = "SELECT * FROM hmisphp.his_patients ORDER BY 
                      CASE WHEN pat_discharge_status = 'Discharged' THEN 1 ELSE 0 END, 
                      pat_date_joined DESC";
                                            $stmt = $mysqli->prepare($query);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $count = 1;

                                            while ($patient = $result->fetch_object()):
                                                $isDischarged = $patient->pat_discharge_status == 'Discharged';
                                                ?>
                                                <tr class="<?= $isDischarged ? 'bg-gray-50' : '' ?> hover:bg-gray-100 transition-colors">
                                                    <td class="border border-gray-200 px-4 py-2 text-black"><?= $patient->pat_id ?></td>
                                                    <td class="border border-gray-200 px-4 py-2 text-black font-medium">
                                                        <?= htmlspecialchars($patient->pat_fname . ' ' . $patient->pat_lname) ?>
                                                        <div class="md:hidden text-sm text-gray-600 mt-1">
                                                            <?= htmlspecialchars($patient->pat_phone) ?>
                                                        </div>
                                                    </td>
                                                    <td class="border border-gray-200 px-4 py-2 text-black hidden md:table-cell">
                                                        <?= htmlspecialchars($patient->pat_phone) ?>
                                                    </td>
                                                    <td class="border border-gray-200 px-4 py-2 text-black hidden lg:table-cell">
                                                        <?= htmlspecialchars($patient->pat_addr) ?>
                                                    </td>
                                                    <td class="border border-gray-200 px-4 py-2 text-black">
                <span class="inline-block px-2 py-1 rounded-full text-xs
                  <?= $patient->pat_type == 'InPatient' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                  <?= $patient->pat_type ?>
                </span>
                                                    </td>
                                                    <td class="border border-gray-200 px-4 py-2 text-black">
                                                        <?php if ($isDischarged): ?>
                                                            <div class="flex items-center">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                      Discharged
                    </span>
                                                                <?php if ($patient->pat_discharge_date): ?>
                                                                    <span class="ml-2 text-xs text-gray-500">
                        <?= date('M d, Y', strtotime($patient->pat_discharge_date)) ?>
                      </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                    Active
                  </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="border border-gray-200 px-4 py-2 text-black">
                                                        <div class="flex space-x-2">
                                                            <?php if (!$isDischarged && $patient->pat_type == 'InPatient'): ?>
                                                                <a href="his_doc_discharge_single_patient.php?pat_id=<?= $patient->pat_id ?>"
                                                                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm inline-block transition-colors">
                                                                    Discharge
                                                                </a>
                                                            <?php elseif ($isDischarged): ?>
                                                                <span class="text-gray-400 text-sm">Discharged</span>
                                                            <?php endif; ?>
                                                            <a href="patient_details.php?id=<?= $patient->pat_id ?>"
                                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm inline-block transition-colors">
                                                                View
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $count++;
                                            endwhile;
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Empty State -->
                                    <?php if ($count === 1): ?>
                                        <div class="text-center py-8 text-gray-500">
                                            No patients found in the system.
                                        </div>
                                    <?php endif; ?>
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