<?php
session_start();
include('assets/inc/config.php');//get configuration file
if(isset($_POST['admin_login']))
{
    $ad_email=$_POST['ad_email'];
    $ad_pwd=sha1(md5($_POST['ad_pwd']));//double encrypt to increase security
    $stmt=$mysqli->prepare("SELECT ad_email ,ad_pwd , ad_id FROM hmisphp.his_admin WHERE ad_email=? AND ad_pwd=? ");//sql to log in user
    $stmt->bind_param('ss',$ad_email,$ad_pwd);//bind fetched parameters
    $stmt->execute();//execute bind
    $stmt -> bind_result($ad_email,$ad_pwd,$ad_id);//bind result
    $rs=$stmt->fetch();
    $_SESSION['ad_id']=$ad_id;//Assign session to admin id
    if($rs)
    {
        header("location:his_admin_dashboard.php");
    }
    else
    {
        $err = "Access Denied. Please Check Your Credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Hospital Management System - Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Hospital Management System - Admin Login Portal" name="description" />
<meta content="MartDevelopers" name="author" />
<link rel="shortcut icon" href="assets/images/logo.png">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    background: url('assets/images/login3.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
body::before {
    content: "";
    position: fixed;
    top:0; left:0; right:0; bottom:0;
    background: rgba(0,0,0,0.35);
    z-index: -1;
}
</style>
</head>
<body class="min-h-screen flex items-center justify-center py-8">
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full md:w-2/3 lg:w-1/2 xl:w-1/3">
            <div class="bg-white/80 rounded-lg overflow-hidden shadow-lg backdrop-blur-md p-8">
                
                <div class="text-center mb-6">
                    <a href="index.php" class="inline-block">
                        <!-- <img src="assets/images/login3.jpg" alt="Hospital Logo" class="mx-auto w-20 h-20 rounded-full shadow-md"> -->
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800 mt-4">Admin Login</h1>
                    <p class="text-gray-600 mt-2">Enter your email and password to access admin panel</p>
                </div>

                <?php if(isset($err)) { ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Login Failed</p>
                    <p><?php echo $err; ?></p>
                </div>
                <?php } ?>

                <form method="post">
                    <div class="mb-4">
                        <label for="ad_email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Email Address
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="ad_email" name="ad_email" type="email" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="password" name="ad_pwd" type="password" placeholder="Enter your password" required>
                    </div>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200" 
                            type="submit" name="admin_login">
                        <i class="fas fa-sign-in-alt mr-2"></i> Log In
                    </button>
                </form>

                <div class="text-center mt-6">
                    <a class="inline-block text-sm text-blue-500 hover:text-blue-800 font-medium" href="his_admin_pwd_reset.php">
                        <i class="fas fa-key mr-1"></i> Forgot your password?
                    </a>
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
