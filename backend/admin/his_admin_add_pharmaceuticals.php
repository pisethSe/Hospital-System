
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_pharmaceutical']))
		{
			$phar_name = $_POST['phar_name'];
			$phar_desc = $_POST['phar_desc'];
            $phar_qty = $_POST['phar_qty'];
            $phar_cat = $_POST['phar_cat'];
            $phar_bcode = $_POST['phar_bcode'];
            $phar_vendor = $_POST['phar_vendor'];
                
            //sql to insert captured values
			$query="INSERT INTO hmisphp.his_pharmaceuticals (phar_name, phar_bcode, phar_desc, phar_qty, phar_cat, phar_vendor) VALUES (?,?,?,?,?,?)";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssss', $phar_name, $phar_bcode, $phar_desc, $phar_qty, $phar_cat, $phar_vendor);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Pharmaceutical  Added";
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
                                            <li class="breadcrumb-item active">Add Pharmaceutical</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Create A Pharmaceutical</h4>
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
                                        <form method="post" class="space-y-6">
                                            <!-- Row 1: Name & Quantity -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label for="inputEmail4" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical Name</label>
                                                    <input type="text" required name="phar_name" id="inputEmail4"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>

                                                <div>
                                                    <label for="inputPassword4" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical Quantity (Box)</label>
                                                    <input type="text" required name="phar_qty" id="inputPassword4"
                                                           class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>

                                            <!-- Row 2: Category & Vendor -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label for="inputState" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical Category</label>
                                                    <select id="inputState" required name="phar_cat"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        <?php
                                                        $ret="SELECT * FROM hmisphp.his_pharmaceuticals_categories ";
                                                        $stmt= $mysqli->prepare($ret);
                                                        $stmt->execute();
                                                        $res=$stmt->get_result();
                                                        while($row=$res->fetch_object()) {
                                                            ?>
                                                            <option><?php echo $row->pharm_cat_name;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="inputState" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical Vendor</label>
                                                    <select id="inputState" required name="phar_vendor"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        <?php
                                                        $ret="SELECT * FROM hmisphp.his_vendor ";
                                                        $stmt= $mysqli->prepare($ret);
                                                        $stmt->execute();
                                                        $res=$stmt->get_result();
                                                        while($row=$res->fetch_object()) {
                                                            ?>
                                                            <option><?php echo $row->v_name;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Barcode -->
                                            <div>
                                                <label for="inputPassword4" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical Barcode (Cambodia 885)</label>
                                                <?php
                                                $length = 10;
                                                $phar_bcode = substr(str_shuffle('0123456789'), 0, $length);
                                                ?>
                                                <input type="text" required name="phar_bcode" value="<?php echo $phar_bcode;?>" id="inputPassword4"
                                                       class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>

                                            <!-- Description -->
                                            <div>
                                                <label for="editor" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical Description</label>
                                                <textarea name="phar_desc" required id="editor"
                                                          class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="4"></textarea>
                                            </div>

                                            <!-- Submit Button -->
                                            <div>
                                                <button type="submit" name="add_pharmaceutical"
                                                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Add Pharmaceutical
                                                </button>
                                            </div>
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