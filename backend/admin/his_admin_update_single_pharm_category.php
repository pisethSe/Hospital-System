
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['update_pharmaceutical_category']))
		{
			$pharm_cat_name = $_GET['pharm_cat_name'];
			$pharm_cat_vendor = $_POST['pharm_cat_vendor'];
			$pharm_cat_desc=$_POST['pharm_cat_desc'];
            
            
            //sql to update captured values
			$query="UPDATE  hmisphp.his_pharmaceuticals_categories SET  pharm_cat_vendor=?, pharm_cat_desc=? WHERE pharm_cat_name = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sss',   $pharm_cat_vendor, $pharm_cat_desc, $pharm_cat_name);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Pharmaceutical Category Upadated ";
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
                $pharm_cat_name=$_GET['pharm_cat_name'];
                $ret="SELECT  * FROM hmisphp.his_pharmaceuticals_categories WHERE pharm_cat_name=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$pharm_cat_name);
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
                                            <li class="breadcrumb-item active">Manage Pharmaceutical Category</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><?php echo $row->pharm_cat_name;?></h4>
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
                                            <!-- Hidden Pharmaceutical Category Name -->
                                            <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0 hidden">
                                                <label for="pharmCatName" class="block text-sm font-medium text-gray-700 mb-1">Pharmaceutical Category Name</label>
                                                <input type="text"
                                                       id="pharmCatName"
                                                       name="pharm_cat_name"
                                                       value="<?php echo $row->pharm_cat_name; ?>"
                                                       required
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>

                                            <!-- Pharmaceutical Category Vendor -->
                                            <div class="w-full px-2">
                                                <label for="pharmCatVendor" class="block text-sm font-medium text-gray-700 mb-1">Pharmaceutical Category Vendor</label>
                                                <input type="text"
                                                       id="pharmCatVendor"
                                                       name="pharm_cat_vendor"
                                                       value="<?php echo $row->pharm_cat_vendor; ?>"
                                                       required
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>

                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Pharmaceutical Category Description</label>
                                                <textarea required="required" type="text" class="form-control" name="pharm_cat_desc" id="editor"><?php echo $row->pharm_cat_desc;?></textarea>
                                            </div>

                                            <button type="submit"
                                                    name="update_pharmaceutical_category"
                                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                                Update Category
                                            </button>

                                        </form>
                                     
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
                <?php }?>

        </div>
        <!-- END wrapper -->
        <!--Load CK EDITOR Javascript-->
        <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
        <script type="text/javascript">
        CKEDITOR.replace('editor')
        </script>
       
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

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