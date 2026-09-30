<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
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
                                    <li class="breadcrumb-item active">Transfer Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Transfer Patients</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Patient's Awaiting Transfers</h4>
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
                                        <div class="mb-2">
                                            <input id="demo-foo-search" type="text" placeholder="Search"
                                                   autocomplete="on"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md
                bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Patient</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Patient Number
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Address
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Category
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Patient WalkIn
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $ret = "SELECT * FROM hmisphp.his_patients ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                    ?>
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $cnt; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->pat_fname . ' ' . $row->pat_lname; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell"><?php echo $row->pat_number; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell"><?php echo $row->pat_addr; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell">
                                            <?php
                                            $colors = ['OutPatient' => 'green', 'InPatient' => 'blue', 'Waiting' => 'yellow'];
                                            $color = $colors[$row->pat_type] ?? 'gray';
                                            ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                <?php echo $row->pat_type; ?>
            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell"><?php echo $row->created_at; ?></td>
                                        <td class="px-4 py-2 text-sm">
                                            <a href="his_admin_transfer_single_patient.php?pat_number=<?php echo $row->pat_number; ?>"
                                               class="bg-blue-500 hover:bg-blue-600
              text-white font-medium
              text-xs px-2 py-1
              rounded shadow-sm
              transition">
                                                Discharge Patient
                                            </a>
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
                <div class="row">
                    <div class="col-12">
                        <div class="bg-white shadow-md rounded-md p-4">
                            <h4 class="text-lg font-semibold mb-4">Transferred Patients</h4>
                            <hr class="my-2">

                            <div class="overflow-x-auto">
                                <table id="demo-foo-filtering"
                                       class="min-w-full border border-gray-200 divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Patient Name
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Patient Number
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Status
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            Discharge
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 hidden sm:table-cell">
                                            WalkOut Date
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $ret = "SELECT * FROM hmisphp.his_patient_transfers ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                    date_default_timezone_set('Asia/Phnom_Penh');
                                    ?>
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $cnt; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row->t_pat_name; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell"><?php echo $row->t_pat_number; ?></td>
                                        <!-- WalkIn Date -->
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell text-green-500">
                                            <?php echo $row->t_status; ?>
                                        </td>

                                        <!--                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell">-->
                                        <?php //echo $row->t_status; ?><!--</td>-->
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell"><?php echo $row->t_hospital; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 hidden sm:table-cell">
                                            <?php echo date('d/m/Y H:i', strtotime($row->t_date)); ?>
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