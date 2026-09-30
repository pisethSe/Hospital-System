
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
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>view patients</title>
</head>
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
                                            <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
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
                        <div class="flex flex-wrap">
                            <div class="w-full">
                                <div class="bg-white rounded-lg shadow-md">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4 text-black">Fill all fields</h4>
                                        <!-- Add Pharmaceutical Form -->
                                        <form method="post">
                                            <div class="flex flex-wrap ">
                                                <div class="w-full md:w-1/2 px-2 mb-4">
                                                    <label for="inputEmail4" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Name</label>
                                                    <input type="text" required name="phar_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" id="inputEmail4">
                                                </div>
                                                <div class="w-full md:w-1/2 px-2 mb-4">
                                                    <label for="inputPassword4" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Quantity (Box)</label>
                                                    <input required type="text" name="phar_qty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" id="inputPassword4">
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap -mx-2">
                                                <div class="w-full md:w-1/2 px-2 mb-4">
                                                    <label for="pharCategory" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Category</label>
                                                    <select id="pharCategory" required name="phar_cat" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black">
                                                        <!-- Fetch All Pharmaceutical Categories -->
                                                        <?php
                                                        $ret = "SELECT * FROM hmisphp.his_pharmaceuticals_categories ORDER BY RAND()";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute();
                                                        $res = $stmt->get_result();
                                                        while ($row = $res->fetch_object()) {
                                                            ?>
                                                            <option class="text-black"><?php echo $row->pharm_cat_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="w-full md:w-1/2 px-2 mb-4">
                                                    <label for="pharVendor" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Vendor</label>
                                                    <select id="pharVendor" required name="phar_vendor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black">
                                                        <?php
                                                        $ret = "SELECT * FROM hmisphp.his_vendor ORDER BY RAND()";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute();
                                                        $res = $stmt->get_result();
                                                        while ($row = $res->fetch_object()) {
                                                            ?>
                                                            <option class="text-black"><?php echo $row->v_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="pharBarcode" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Barcode (EAN-8)</label>
                                                <?php
                                                $length = 10;
                                                $phar_bcode = substr(str_shuffle('0123456789'), 1, $length);
                                                ?>
                                                <input required type="text" value="<?php echo $phar_bcode; ?>" name="phar_bcode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" id="pharBarcode">
                                            </div>

                                            <div class="mb-6">
                                                <label for="editor" class="block mb-2 text-sm font-medium text-black">Pharmaceutical Description</label>
                                                <textarea required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-black" name="phar_desc" id="editor"></textarea>

                                            </div>

                                            <button type="submit" name="add_pharmaceutical" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                                Add Pharmaceutical
                                            </button>
                                        </form>
                                    </div>
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