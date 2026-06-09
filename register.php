<?php
include 'db.php';

if(isset($_POST['register'])){

    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $role=$_POST['role'];

    $conn->query("
    INSERT INTO users(name,email,password,role,status)
    VALUES('$name','$email','$password','$role','pending')
    ");

    echo "
    <script>

    alert('Registration Submitted! Approval Pending.');

    window.location='index.php';

    </script>
    ";
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Register - JS Ideal School ERP</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="overlay">

<div class="glass">

<form method="post">

<h2>Register</h2>

<input 
type="text" 
name="name" 
placeholder="Name"
required
>

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

<select name="role">

<option value="student">
Student
</option>

<option value="teacher">
Teacher
</option>

</select>

<button name="register">
Register
</button>

<br><br>

<a href="index.php">
Back to Homepage
</a>

</form>

</div>

</div>

</body>

</html>