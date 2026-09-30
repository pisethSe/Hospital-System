<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
//$aid=$_SESSION['ad_id'];
$doc_id = $_SESSION['doc_id']
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>view patients </title>
</head>
<style>

</style>

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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                    <li class="breadcrumb-item active">View Patients</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Patient Details</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row " >
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title"></h4>
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
                                        <div class="form-group">
                                            <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="min-w-full border-collapse border border-gray-200" data-page-size="7">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">#</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider" data-toggle="true">Patient Name</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden sm:table-cell">Patient Number</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden md:table-cell">Patient Address</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden md:table-cell">Patient Phone</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden sm:table-cell">Patient Age</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden sm:table-cell">Patient Status</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden sm:table-cell">Room Number</th>

                                        <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-black uppercase tracking-wider hidden sm:table-cell">Action</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    /*
                                        *get details of allpatients
                                        *
                                    */
                                    $ret="SELECT * FROM  hmisphp.his_patients  ";
                                    //sql code to get to ten docs  randomly
                                    $stmt= $mysqli->prepare($ret) ;
                                    $stmt->execute() ;//ok
                                    $res=$stmt->get_result();
                                    $cnt=1;
                                    while($row=$res->fetch_object())
                                    {
                                        ?>
                                        <tbody>
                                        <tr class="hover:bg-gray-50">
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black"><?php echo $cnt;?></td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black font-medium"><?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?></td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden sm:table-cell"><?php echo $row->pat_number;?></td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden md:table-cell"><?php echo $row->pat_addr;?></td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden md:table-cell"><?php echo $row->pat_phone;?></td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden sm:table-cell"><?php echo $row->pat_age;?> Years</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden sm:table-cell"><?php
                                                $colors = ['OutPatient' => 'green', 'InPatient' => 'blue', 'Waiting' => 'yellow'];
                                                $color = $colors[$row->pat_type] ?? 'gray';
                                                ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800">
                                                <?php echo $row->pat_type; ?></td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden sm:table-cell"><?php echo $row->pat_room_number;?></td>

                                            <td class="border border-gray-300 px-4 py-2 text-sm text-black hidden sm:table-cell">
                                                <a href="his_doc_view_single_patient.php?pat_id=<?php echo $row->pat_id;?>&&pat_number=<?php echo $row->pat_number;?>&&pat_name=<?php echo $row->pat_fname;?>_<?php echo $row->pat_lname;?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php  $cnt = $cnt +1 ; }?>
                                    <tfoot>
                                    <tr class="active bg-gray-50">
                                        <td  colspan="8" class="border border-gray-300 px-4 py-2">
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