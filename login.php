<?php
session_start();
include 'db.php';

$admin_email = "admin@jsideal.com";
$admin_pass  = "1234";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    // ADMIN LOGIN
    if($email == $admin_email && $password == $admin_pass){

        $_SESSION['admin'] = true;

        header("Location: admin.php");
        exit;
    }

    // USER LOGIN
    $res = $conn->query("
        SELECT * FROM users
        WHERE email='$email'
        AND password='$password'
    ");

    $user = $res->fetch_assoc();

    // IF USER EXISTS
    if($user){

        // CHECK APPROVAL
        if($user['status'] != 'approved'){

            echo "<script>alert('Wait for Admin Approval');</script>";

        } else {

            $_SESSION['user'] = $user;

            // STUDENT LOGIN
            if($user['role'] == 'student'){

                header("Location: student.php");
                exit;
            }

            // TEACHER LOGIN
            if($user['role'] == 'teacher'){

                header("Location: teacher.php");
                exit;
            }
        }

    } else {

        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login - JS Ideal School ERP</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="overlay">

<div class="glass">

<form method="post">

<h2>Login</h2>

<input 
type="email" 
name="email" 
placeholder="Email"
required
>

<input 
type="password" 
name="password" 
placeholder="Password"
required
>

<button name="login">Login</button>

<br><br>

<a href="index.php">Back to Home</a>

</form>

</div>

</div>

</body>
</html>