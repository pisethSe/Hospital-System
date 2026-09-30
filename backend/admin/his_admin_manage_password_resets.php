<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
if (isset($_GET['deleteRequest'])) {
    $id = intval($_GET['deleteRequest']);
    $adn = "DELETE FROM hmisphp.his_pwdresets WHERE  id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        $success = "Deleted";
    } else {
        $err = "Try Again Later";
    }
}
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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Password Resets</a></li>
                                    <li class="breadcrumb-item active">Manage</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Accounts Requesting For Password Resets</h4>
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
                                            <input
                                                    id="demo-foo-search"
                                                    type="text"
                                                    placeholder="Search"
                                                    autocomplete="on"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            />
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0"
                                       data-page-size="">
                                    <thead class="bg-gray-100 text-gray-700 text-sm">
                                    <tr>
                                        <th class="px-4 py-2 text-left">#</th>
                                        <th class="px-4 py-2 text-left">Email</th>
                                        <th class="px-4 py-2 text-left">Password Reset Token</th>
                                        <th class="px-4 py-2 text-left">Date Requested</th>
                                        <th class="px-4 py-2 text-left">Action</th>
                                    </tr>
                                    </thead>

                                    <?php

                                    $ret = "SELECT * FROM  hmisphp.his_pwdresets";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();//ok
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        //trim timestamp to DD-MM-YYYY Formart
                                        $requestedtime = $row->created_at;

                                        if ($row->status == 'Pending') {
                                            $action = "<td>
    <a href='his_admin_update_doc_password.php?email=$row->email&pwd=$row->pwd' 
       class='inline-flex items-center px-2 py-1 text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 transition-colors'>
        <i class='fas fa-edit mr-1'></i> Reset Password
    </a>
</td>";

                                        } else {
                                            $action = "<td>
    <a href='mailto:$row->email?subject=Password Reset Request&body=Token:$row->token, New Password=$row->pwd' 
       class='inline-flex items-center px-2 py-1 text-sm font-medium text-white bg-green-500 rounded hover:bg-green-600 transition-colors'>
        <i class='fas fa-envelope mr-1'></i> Send Mail
    </a>
</td>";

                                        }
                                        ?>

                                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700 text-black ">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2"><?php echo $cnt; ?></td>
                                            <td class="px-4 py-2"><?php echo $row->email; ?></td>
                                            <td class="px-4 py-2"><?php echo $row->token; ?></td>
                                            <td class="px-4 py-2">
                                                <?php echo date('d-M-Y h:i A', strtotime($requestedtime)); ?>
                                            </td>
                                            <?php echo $action; ?>
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