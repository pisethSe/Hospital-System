<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_payroll']))
		{
			$pay_number = $_POST['pay_number'];
			$pay_doc_name = $_POST['pay_doc_name'];
            //$pres_pat_type = $_POST['pres_pat_type'];
            $pay_doc_number = $_POST['pay_doc_number'];
            $pay_doc_email = $_POST['pay_doc_email'];
            $pay_emp_salary = $_POST['pay_emp_salary'];
            $pay_descr = $_POST['pay_descr'];
            //$mdr_pat_ailment = $_POST['mdr_pat_ailment'];
            //sql to insert captured values
			$query="INSERT INTO  hmisphp.his_payrolls  (pay_number, pay_doc_name, pay_doc_number, pay_doc_email, pay_emp_salary, pay_descr) VALUES(?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssss', $pay_number, $pay_doc_name, $pay_doc_number, $pay_doc_email, $pay_emp_salary, $pay_descr);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Payroll Record Addded";
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
                $doc_number = $_GET['doc_number'];
                $ret="SELECT  * FROM hmisphp.his_docs WHERE doc_number=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$doc_number);
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
                                                <li class="breadcrumb-item active">Add Payroll Record</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Add Employee Payroll Record</h4>
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
                                                <div class="flex flex-wrap -mx-3 mb-4">
                                                    <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                                                        <label for="pay_doc_name" class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
                                                        <input type="text" id="pay_doc_name" name="pay_doc_name" value="<?php echo $row->doc_fname;?> <?php echo $row->doc_lname;?>"
                                                               readonly required
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Employee Name">
                                                    </div>

                                                    <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                                                        <label for="pay_doc_email" class="block text-sm font-medium text-gray-700 mb-1">Employee Email</label>
                                                        <input type="text" id="pay_doc_email" name="pay_doc_email" value="<?php echo $row->doc_email;?>"
                                                               readonly required
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Employee Email">
                                                    </div>

                                                    <div class="w-full md:w-1/3 ">
                                                        <label for="pay_doc_number" class="block text-sm font-medium text-gray-700 mb-1">Employee Number</label>
                                                        <input type="text" id="pay_doc_number" name="pay_doc_number" value="<?php echo $row->doc_number;?>"
                                                               readonly required
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Employee Number">
                                                    </div>
                                                </div>


                                                <div class="flex flex-wrap -mx-3 mb-4">
                                                    <div class="w-full px-3">
                                                        <label for="pay_emp_salary" class="block text-sm font-medium text-gray-700 mb-1">Employee Salary ($)</label>
                                                        <input type="text" id="pay_emp_salary" name="pay_emp_salary" required
                                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="Enter Salary">
                                                    </div>
                                                </div>

                                                <?php }?>
                                                <hr>
                                                <div class="form-row">
                                                    
                                            
                                                    <div class="form-group col-md-2" style="display:none">
                                                        <?php 
                                                            $length = 5;    
                                                            $pay_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                        ?>
                                                        <label for="inputZip" class="col-form-label">Payroll Record Number</label>
                                                        <input type="text" name="pay_number" value="<?php echo $pay_no;?>" class="form-control" id="inputZip">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Payroll Description</label>
                                                        <textarea   type="text" class="form-control" name="pay_descr" id="editor"> </textarea>
                                                </div>

                                                <button type="submit" name="add_payroll"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                                                    Add Payroll Record
                                                </button>


                                            </form>
                                            <!--End Patient Form-->
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card-->
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