<?php
session_start();
include("db_connect.php");

// ตรวจสอบการล็อกอิน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // ใช้ PDO สำหรับการเตรียมและรันคำสั่ง SQL
    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $input_username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบ username และ password แบบ case-sensitive
    if ($user && strcmp($input_username, $user['username']) == 0 && $input_password == $user['password']) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $input_username;
    header("Location: admin/dashboard.php");
    exit;
} else {
    $error = "username หรือ password ผิด";
}

}    
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&family=Prompt:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* body {
            background-image: url('images/login.png');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        } */
        /* body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        } */

        /* เพิ่มวิดีโอเป็นพื้นหลัง */
        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
            background-position: center ;
        }

        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border: 2px solid rgb(255, 255, 255, .2);
            border-radius: 10px;
            box-shadow: 0 0 90px rgb(0, 0, 0, .5);
            backdrop-filter: blur(5px);
            background: transparent;
            color: white;
        }

        .main-logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            max-width: 100%;
            height: auto;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .text-center {
            color: white;
        }

        .position-relative {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            top: 71%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

        .eye-icon:hover {
            color: #333;
        }
    </style>
</head>

<body>
        <video autoplay muted loop play-inline id="bg-video">
        <source src="images/vdo.mp4" type="video/mp4">
    </video>
    <section class="login-container">
        <div class="card login-card">
            <img class="main-logo" src="images/MAIN_LOGO_2.png" alt="LOGO" width="150" height="150">
            <h2 class="text-center">Admin Login</h2>

            <form method="post" action="" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div class="invalid-feedback">
                        โปรดใส่ username
                    </div>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">
                        โปรดใส่ password
                    </div>
                    <i class="fa fa-eye position-absolute eye-icon" id="togglePassword"></i>
                </div>

                <!-- แสดงข้อความแจ้งเตือนเมื่อเกิดข้อผิดพลาด -->
                <?php if (isset($error)): ?>
                <div class="error-msg"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn-primary">เข้าสู่ระบบ</button>
                    <button class="btn-back-button" onclick="window.location.href='index.php'">กลับหน้าหลัก</button>
                </div>
            </form>
        </div>
    </section>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // ตรวจสอบฟอร์มก่อนส่ง
        (function() {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // ฟังก์ชันเพื่อแสดง/ซ่อนรหัสผ่าน
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // เปลี่ยนไอคอนเมื่อกด
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
