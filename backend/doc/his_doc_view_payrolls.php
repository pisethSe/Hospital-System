<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $doc_id = $_SESSION['doc_id'];
  $doc_number = $_SESSION['doc_number']; // ✅ added
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>My Payrolls</title>
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

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    
                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Payroll</a></li>
                                        <li class="breadcrumb-item active">View Payroll</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">My Payrolls</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End Page Title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="w-full border-collapse border" data-page-size="7">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border p-2 text-black font-semibold">#</th>
                                                <th class="border p-2 text-black font-semibold">My Name</th>
                                                <th class="border p-2 text-black font-semibold">My Number</th>
                                                <th class="border p-2 text-black font-semibold">Payroll Number</th>
                                                <th class="border p-2 text-black font-semibold">My Salary</th>
                                                <th class="border p-2 text-black font-semibold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $pay_doc_number = $_SESSION['doc_number'];
                                            $ret="SELECT * FROM hmisphp.his_payrolls WHERE pay_doc_number = ?";
                                            $stmt= $mysqli->prepare($ret);
                                            $stmt->bind_param('s',$pay_doc_number);
                                            $stmt->execute();
                                            $res=$stmt->get_result();
                                            $cnt=1;
                                            while($row=$res->fetch_object()) {
                                        ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="border p-2 text-black"><?php echo $cnt;?></td>
                                                <td class="border p-2 text-black"><?php echo $row->pay_doc_name;?></td>
                                                <td class="border p-2 text-black"><?php echo $row->pay_doc_number;?></td>
                                                <td class="border p-2 text-black"><?php echo $row->pay_number;?></td>
                                                <td class="border p-2 text-black">$ <?php echo $row->pay_emp_salary;?></td>
                                                <td class="border p-2 text-black">
                                                    <a href="his_doc_view_single_payroll.php?pay_number=<?php echo $row->pay_number;?>" 
                                                       class="inline-block bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                                        <i class="fas fa-eye mr-1"></i> View | Print Payroll
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php $cnt++; } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-gray-100">
                                                <td colspan="8" class="border p-2">
                                                    <div class="flex justify-end">
                                                        <ul class="flex pagination rounded"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer -->
            <?php include('assets/inc/footer.php');?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/libs/footable/footable.all.min.js"></script>
    <script src="assets/js/pages/foo-tables.init.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
