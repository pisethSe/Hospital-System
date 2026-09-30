<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['add_pharmaceutical_category'])) {
    $pharm_cat_name = $_POST['pharm_cat_name'];
    $pharm_cat_vendor = $_POST['pharm_cat_vendor'];
    $pharm_cat_desc = $_POST['pharm_cat_desc'];


    //sql to insert captured values
    $query = "INSERT INTO hmisphp.his_pharmaceuticals_categories (pharm_cat_name, pharm_cat_vendor, pharm_cat_desc) VALUES (?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $pharm_cat_name, $pharm_cat_vendor, $pharm_cat_desc);
    $stmt->execute();
    /*
    *Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
    *echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
    */
    //declare a varible which will be passed to alert function
    if ($stmt) {
        $success = "Pharmaceutical Category Added";
    } else {
        $err = "Please Try Again Or Try Later";
    }


}
?>
<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">

<!--Head-->
<?php include('assets/inc/head.php'); ?>
<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?php include("assets/inc/nav.php"); ?>
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
                                    <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmaceuticals</a></li>
                                    <li class="breadcrumb-item active">Add Pharmaceutical Category</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Create A Pharmaceutical Category</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <!-- Form row -->
                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h4 class="text-xl font-semibold mb-4">Fill all fields</h4>
                        <!-- Add Patient Form -->
                        <form method="post" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="inputEmail4" class="block text-base font-medium text-gray-700 mb-1">
                                        Medicine Type
                                    </label>
                                    <input type="text"
                                           required
                                           name="pharm_cat_name"
                                           id="inputEmail4"
                                           class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>


                                <div>
                                    <label for="inputState" class="block text-base font-medium text-gray-700 mb-1">Pharmaceutical
                                        Vendor</label>
                                    <select id="inputState" required name="pharm_cat_vendor"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-4 py-2 text-base focus:ring-indigo-500 focus:border-indigo-500">
                                        <?php
                                        $ret = "SELECT * FROM hmisphp.his_vendor ORDER BY RAND()";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                            ?>
                                            <option><?php echo $row->v_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>


                            <div>
                                <label for="editor" class="block text-sm font-medium text-gray-700">Pharmaceutical
                                    Category Description</label>
                                <textarea required name="pharm_cat_desc" id="editor"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          rows="4"></textarea>
                            </div>

                            <div>
                                <button type="submit" name="add_pharmaceutical_category"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Add Category
                                </button>
                            </div>

                        </form>
                    </div>
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