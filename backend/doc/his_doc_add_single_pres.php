<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
if (isset($_POST['add_patient_presc'])) {
    $pres_pat_name = $_POST['pres_pat_name'];
    $pres_pat_number = $_POST['pres_pat_number'];
    $pres_pat_type = $_POST['pres_pat_type'];
    $pres_pat_addr = $_POST['pres_pat_addr'];
    $pres_pat_age = $_POST['pres_pat_age'];
    $pres_number = $_POST['pres_number'];
    $pres_ins = $_POST['pres_ins'];
    $pres_pat_ailment = $_POST['pres_pat_ailment'];
    //sql to insert captured values
    $query = "INSERT INTO  hmisphp.his_prescriptions  (pres_pat_name, pres_pat_number, pres_pat_type, pres_pat_addr, pres_pat_age, pres_number, pres_pat_ailment, pres_ins) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $pres_pat_name, $pres_pat_number, $pres_pat_type, $pres_pat_addr, $pres_pat_age, $pres_number, $pres_pat_ailment, $pres_ins);
    $stmt->execute();
    /*
    *Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
    *echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
    */
    //declare a varible which will be passed to alert function
    if ($stmt) {
        $success = "Patient Prescription Addded";
    } else {
        $err = "Please Try Again Or Try Later";
    }


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Patient Prescription</title>
</head>
<?php include('assets/inc/head.php'); ?>
<body>

<div id="wrapper">
    <?php include("assets/inc/nav.php"); ?>
    <?php include("assets/inc/sidebar.php"); ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <?php
                $pat_number = $_GET['pat_number'];
                $ret = "SELECT  * FROM hmisphp.his_patients WHERE pat_number=?";
                $stmt = $mysqli->prepare($ret);
                $stmt->bind_param('s', $pat_number);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res->fetch_object();
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Fill all fields</h4>
                                <!--Add Patient Form-->
                                <form method="post">
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                            <input type="text" required="required" readonly name="pres_pat_name"
                                                   value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>"
                                                   class="form-control" id="inputEmail4"
                                                   placeholder="Patient's First Name">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="inputPassword4" class="col-form-label">Patient Age</label>
                                            <input required="required" type="text" readonly name="pres_pat_age"
                                                   value="<?php echo $row->pat_age; ?>" class="form-control"
                                                   id="inputPassword4" placeholder="Patient`s Last Name">
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="form-group col-md-4">
                                            <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                            <input type="text" required="required" readonly name="pres_pat_number"
                                                   value="<?php echo $row->pat_number; ?>" class="form-control"
                                                   id="inputEmail4" placeholder="DD/MM/YYYY">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4" class="col-form-label">Patient Address</label>
                                            <input required="required" type="text" readonly name="pres_pat_addr"
                                                   value="<?php echo $row->pat_addr; ?>" class="form-control"
                                                   id="inputPassword4" placeholder="Patient`s Age">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4" class="col-form-label">Patient Type</label>
                                            <input required="required" readonly type="text" name="pres_pat_type"
                                                   value="<?php echo $row->pat_type; ?>" class="form-control"
                                                   id="inputPassword4" placeholder="Patient`s Age">
                                        </div>

                                    </div>

                                    <div class="form-group ">
                                        <label for="inputCity" class="col-form-label">Patient Ailment</label>
                                        <input required="required" type="text" value="<?php echo $row->pat_ailment; ?>"
                                               name="pres_pat_ailment" class="form-control" id="inputCity">
                                    </div>
                                    <hr>
                                    <div class="form-row">


                                        <div class="form-group col-md-2" style="display:none">
                                            <?php
                                            $length = 5;
                                            $pres_no = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                            ?>
                                            <label for="inputZip" class="col-form-label">Prescription Number</label>
                                            <input type="text" name="pres_number" value="<?php echo $pres_no; ?>"
                                                   class="form-control" id="inputZip">
                                        </div>
                                    </div>

                                    <!-- Prescription Instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prescription</label>
                                        <textarea name="pres_ins" id="editor" required
                                                  class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                  rows="4"></textarea>
                                    </div>


<!--                                    < Add another medicine -->
<!--                                    <button type="button" onclick="addMedicine()"-->
<!--                                            class="btn btn-sm bg-yellow-500 mt-2 mb-3 hover:text-white hover:bg-yellow-600">-->
<!--                                        + Add Another Medicine-->
<!--                                    </button>-->


                                    <button type="submit" name="add_patient_presc"
                                            class="btn btn-sm bg-blue-500 mt-2 mb-3 hover:text-white hover:bg-blue-600">
                                        Add Prescription
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include('assets/inc/footer.php'); ?>

    </div>
</div>
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
<script>
    function addMedicine() {
        let container = document.getElementById('medicines-container');
        let clone = container.firstElementChild.cloneNode(true);
        // Clear inputs
        clone.querySelectorAll("input, select").forEach(el => el.value = "");
        container.appendChild(clone);
    }

    function removeMedicine(button) {
        let container = document.getElementById('medicines-container');
        if (container.children.length > 1) { // Keep at least one
            button.parentElement.remove();
        } else {
            alert("At least one medicine is required!");
        }
    }
</script>

</body>
</html>
