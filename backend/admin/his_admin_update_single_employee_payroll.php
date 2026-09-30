<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['update_payroll']))
		{
			$pay_number = $_GET['pay_number'];
			$pay_doc_name = $_POST['pay_doc_name'];
            //$pres_pat_type = $_POST['pres_pat_type'];
            $pay_doc_number = $_POST['pay_doc_number'];
            $pay_doc_email = $_POST['pay_doc_email'];
            $pay_emp_salary = $_POST['pay_emp_salary'];
            $pay_descr = $_POST['pay_descr'];
            $pay_status = $_POST['pay_status'];
            //$mdr_pat_ailment = $_POST['mdr_pat_ailment'];
            //sql to insert captured values
			$query="UPDATE   hmisphp.his_payrolls SET pay_doc_name=?, pay_doc_number=?, pay_doc_email=?, pay_emp_salary=?, pay_descr=?, pay_status = ? WHERE pay_number = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sssssss',  $pay_doc_name, $pay_doc_number, $pay_doc_email, $pay_emp_salary, $pay_descr, $pay_status, $pay_number);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Payroll Record Updated ";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
		}
?>
<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">
    
    <!--Head-->
    <?php include('assets/inc/head.php');?>
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include("assets/inc/nav.php");?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include("assets/inc/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <?php
                $pay_number = $_GET['pay_number'];
                $ret="SELECT  * FROM hmisphp.his_payrolls WHERE pay_number=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$pay_number);
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
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Payrolls</a></li>
                                                <li class="breadcrumb-item active">Update Payroll Record</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Update Employee Payroll Record</h4>
                                    </div>
                                </div>
                            </div>     
                            <!-- end page title --> 
                            <!-- Form row -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title">Fill all fields</h4>
                                            <!--Add Patient Form-->
                                            <form method="post">
                                                <div class="form-row">

                                                    <!-- Employee Name -->
                                                    <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                        <label for="employeeName" class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
                                                        <input type="text"
                                                               required
                                                               readonly
                                                               name="pay_doc_name"
                                                               value="<?php echo $row->pay_doc_name; ?>"
                                                               id="employeeName"
                                                               placeholder="Patient's Name"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                    </div>

                                                    <!-- Employee Email -->
                                                    <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                                                        <label for="employeeEmail" class="block text-sm font-medium text-gray-700 mb-1">Employee Email</label>
                                                        <input type="text"
                                                               required
                                                               readonly
                                                               name="pay_doc_email"
                                                               value="<?php echo $row->pay_doc_email; ?>"
                                                               id="employeeEmail"
                                                               placeholder="Patient's Last Name"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                    </div>

                                                    <!-- Employee Number -->
                                                    <div class="w-full md:w-1/3 px-2">
                                                        <label for="employeeNumber" class="block text-sm font-medium text-gray-700 mb-1">Employee Number</label>
                                                        <input type="text"
                                                               required
                                                               readonly
                                                               name="pay_doc_number"
                                                               value="<?php echo $row->pay_doc_number; ?>"
                                                               id="employeeNumber"
                                                               placeholder="Patient's Number"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                    </div>

                                                </div>

                                                <div class="form-row">

                                                    <div class="form-group col-md-6">
                                                        <label for="employeeSalary" class="block text-sm font-medium text-gray-700 mb-1">Employee Salary ($)</label>
                                                        <input type="text"
                                                               required
                                                               name="pay_emp_salary"
                                                               value="<?php echo $row->pay_emp_salary; ?>"
                                                               id="employeeSalary"
                                                               placeholder="Enter Salary"
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="payrollStatus" class="block text-sm font-medium text-gray-700 mb-1">Payroll Status</label>
                                                        <select id="payrollStatus"
                                                                required
                                                                name="pay_status"
                                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                            <option>Choose</option>
                                                            <option>Paid</option>
                                                            <option>Unpaid</option>
                                                        </select>
                                                </div>


                                                </div>

                                                <hr>


                                                <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Payroll Description</label>
                                                        <textarea   type="text" class="form-control" name="pay_descr" id="editor"> <?php echo $row->pay_descr;?></textarea>
                                                </div>

                                                <button type="submit"
                                                        name="update_payroll"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                                    Update Payroll Record
                                                </button>

                                            </form>
                                            <!--End Patient Form-->
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card-->
                                </div> <!-- end col -->
                                <?php }?>
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
        <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
        <script type="text/javascript">
        CKEDITOR.replace('editor')
        </script>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

        <!-- Loading buttons js -->
        <script src="assets/libs/ladda/spin.js"></script>
        <script src="assets/libs/ladda/ladda.js"></script>

        <!-- Buttons init js-->
        <script src="assets/js/pages/loading-btn.init.js"></script>
        
    </body>

</html>