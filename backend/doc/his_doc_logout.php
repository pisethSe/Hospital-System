<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Logout - Hospital Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden font-sans">

<!-- Animated Background -->
<div class="absolute inset-0 bg-cover bg-center animate-zoom"
     style="background-image: url('assets/images/login3.jpg');"></div>
<div class="absolute inset-0 bg-black bg-opacity-50"></div>

<!-- Card -->
<div class="relative bg-white/90 shadow-2xl rounded-3xl p-8 max-w-md w-full text-center backdrop-blur-md hover:scale-105 transition-transform duration-700 animate-fadeIn">

    <!-- Logo -->
    <div class="mb-6">
        <a href="his_doc_logout.php">
            <!-- <img src="assets/images/logo.png" alt="Logo" class="mx-auto h-20"> -->
        </a>
    </div>

    <!-- Checkmark Animation -->
    <div class="flex justify-center mb-6">
        <div class="bg-green-100 rounded-full w-24 h-24 flex items-center justify-center animate-checkPulse">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" class="w-16 h-16 text-green-500">
                <circle class="path circle" fill="none" stroke="currentColor" stroke-width="6" cx="65.1" cy="65.1"
                        r="62.1"/>
                <polyline class="path check" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round"
                          points="100.2,40.2 51.5,88.8 29.8,67.5"/>
            </svg>
        </div>
    </div>

    <!-- Message -->
    <h2 class="text-3xl font-bold text-gray-800 mb-2">See you again!</h2>
    <p class="text-gray-600 mb-6">You are now successfully signed out.</p>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="index.php"
           class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold transition transform hover:scale-105">Log
            In</a>
        <a href="../../index.php"
           class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full font-semibold transition transform hover:scale-105">Home
            Page</a>
    </div>

    <!-- Footer -->
    <p class="text-gray-400 text-sm mt-8">&copy; 2025 Hospital Management System. All rights reserved.</p>
</div>

<style>
    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out forwards;
    }

    @keyframes checkPulse {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.2);
            opacity: 1;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-checkPulse {
        animation: checkPulse 0.8s ease-out forwards, pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes zoom {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .animate-zoom {
        animation: zoom 20s ease-in-out infinite;
    }
</style>

</body>
</html>
