<?php
    session_start();
    include('assets/inc/config.php');//get configuration file
    if(isset($_POST['doc_login']))
    {
        $doc_number = $_POST['doc_number'];
        //$doc_email = $_POST['doc_ea']
        $doc_pwd = sha1(md5($_POST['doc_pwd']));//double encrypt to increase security
        $stmt=$mysqli->prepare("SELECT doc_number, doc_pwd, doc_id FROM hmisphp.his_docs WHERE  doc_number=? AND doc_pwd=? ");//sql to log in user
        $stmt->bind_param('ss', $doc_number, $doc_pwd);//bind fetched parameters
        $stmt->execute();//execute bind
        $stmt -> bind_result($doc_number, $doc_pwd ,$doc_id);//bind result
        $rs=$stmt->fetch();
        $_SESSION['doc_id'] = $doc_id;
        $_SESSION['doc_number'] = $doc_number;//Assign session to doc_number id
        //$uip=$_SERVER['REMOTE_ADDR'];
        //$ldate=date('d/m/Y h:i:s', time());
        if($rs)
            {//if its sucessfull
                header("location:his_doc_dashboard.php");
            }

        else
            {
            #echo "<script>alert('Access Denied Please Check Your Credentials');</script>";
                $err = "Access Denied Please Check Your Credentials";
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Hospital Management System - Doctor Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Hospital Management System - Doctor Login Portal" name="description" />
    <meta content="MartDevelopers" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="assets/images/logo.png">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background: url('assets/images/login3.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full md:w-2/3 lg:w-1/2 xl:w-1/3">
                <div class="bg-white/80 rounded-lg overflow-hidden shadow-lg backdrop-blur-md">

    <div class="p-8">
        <div class="text-center mb-6">
            <a href="index.php" class="inline-block">
                <!-- <img src="assets/images/doc1.jpg" alt="Hospital Management System" class="mx-auto rounded-lg shadow-md w-full max-w-xs"> -->
            </a>
            <h1 class="text-2xl font-bold text-gray-800 mt-4">Doctor Login</h1>
            <p class="text-gray-600 mt-2">Access your medical dashboard</p>
        </div>

        <?php if(isset($err)) { ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <p class="font-bold">Login Failed</p>
            <p><?php echo $err; ?></p>
        </div>
        <?php } ?>

        <form method="post">
            <div class="mb-4">
                <label for="doc_number" class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-user-md mr-2 text-blue-500"></i>Doctor Number
                </label>
                <input class=" appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="doc_number" name="doc_number" type="text" placeholder="Enter your doctor number" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                </label>
                <input class=" appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    id="password" name="doc_pwd" type="password" placeholder="Enter your password" required>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200"
                    type="submit" name="doc_login">
                    <i class="fas fa-sign-in-alt mr-2"></i> Log In
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a class="inline-block text-sm text-blue-500 hover:text-blue-800 font-medium" href="his_doc_reset_pwd.php">
                <i class="fas fa-key mr-1"></i> Forgot your password?
            </a>
        </div>
    </div>
</div>


                <div class="text-center mt-6">
                    <p class="text-white text-sm font-medium">
                        &copy; <?php echo date('Y'); ?> Hospital Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Notifications -->
    <?php if(isset($success)) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $success; ?>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    <?php } ?>

    <?php if(isset($err)) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?php echo $err; ?>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
        });
    </script>
    <?php } ?>
</body>
</html>