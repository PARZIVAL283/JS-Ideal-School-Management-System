<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>

<head>

<title>JS Ideal School ERP</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<!-- HERO SECTION -->

<div class="hero">

    <div class="overlay">

        <div class="hero-content">

            <h1>JS Ideal School</h1>

            <p>
                Smart Education • Smart Management • Better Future
            </p>

            <div class="menu">

                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <a href="admin.php">Admin Portal</a>

            </div>

        </div>

    </div>

</div>

<!-- NOTICE BOARD -->

<section class="section">

<h2>📢 Notice Board</h2>

<div class="notice-board">

<?php

$notices=$conn->query("
SELECT * FROM notices
ORDER BY id DESC
");

while($n=$notices->fetch_assoc()){

?>

<div class="notice">

<?= $n['notice'] ?>

<br><br>

<small>
<?= $n['created_at'] ?>
</small>

</div>

<?php } ?>

</div>

</section>

<!-- ABOUT -->

<section class="section">

<h2>🏛 About JS Ideal School</h2>

<div class="about">

<p>

JS Ideal School is dedicated to academic excellence,
discipline, and smart digital education management.
This ERP system helps students, teachers and administration
work together efficiently.

</p>

</div>

</section>

<!-- FOOTER -->

<footer>

<p>
© 2026 JS Ideal School ERP System | Developed by QUAD CORE
</p>

</footer>

</body>

</html>