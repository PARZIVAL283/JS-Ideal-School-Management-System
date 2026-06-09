<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location:login.php");
    exit;
}

$user = $_SESSION['user'];
$id = $user['id'];

/* GET STUDENT PROFILE SAFELY */
$p = null;
$class = null;
$profile = $conn->query("
SELECT * FROM student_profile 
WHERE user_id=$id
");

if ($profile && $profile->num_rows > 0) {
    $p = $profile->fetch_assoc();
    $class = $p['class'];
}
?>

<link rel="stylesheet" href="dashboard.css">

<div class="sidebar">

    <h2>Student</h2>

    <a href="index.php">🏠 Homepage</a>
    <a href="student.php">📊 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>

</div>

<div class="topbar">
    <h3>Welcome <?= $user['name'] ?></h3>
</div>

<div class="content">

    <!-- PROFILE -->
    <div class="card">
        <h2>My Profile</h2>

        <?php if ($p) { ?>

            Class: <?= $p['class'] ?> <br>
            Roll: <?= $p['roll'] ?> <br>
            Phone: <?= $p['phone'] ?> <br>
            Address: <?= $p['address'] ?>

        <?php } else { ?>
            <p>Profile not assigned yet.</p>
        <?php } ?>

    </div>


    <div class="card">

        <h2>💳 My Accounts Information</h2>

        <?php

        $fees = $conn->query("
SELECT * FROM student_fees
WHERE student_id=$id
ORDER BY id DESC
");

        if ($fees->num_rows > 0) {

            while ($f = $fees->fetch_assoc()) {

                echo "
        <div class='inner-card'>

        <b>Month:</b> {$f['month']} <br>

        <b>Total Fee:</b> ৳{$f['total_fee']} <br>

        <b>Paid:</b> ৳{$f['paid_fee']} <br>

        <b>Due:</b> ৳{$f['due_fee']} <br>

        <b>Status:</b> {$f['status']}

        </div>
        ";
            }
        } else {

            echo "No fee records available.";
        }

        ?>

    </div>

    <!-- CLASS ROUTINE -->
    <div class="card">

        <h2>📚 Class Routine</h2>

        <?php if ($class) {

            $routine = $conn->query("
        SELECT * FROM class_routine
        WHERE class='$class'
        ");

            while ($r = $routine->fetch_assoc()) {
                echo "<div class='inner-card'>
            {$r['day']} - {$r['subject']} - {$r['time']}
            </div>";
            }
        } else {
            echo "<p>No class assigned yet.</p>";
        } ?>

    </div>

    <!-- EXAM ROUTINE -->
    <div class="card">

        <h2>📝 Exam Routine</h2>

        <?php if ($class) {

            $exam = $conn->query("
        SELECT * FROM exam_routine
        WHERE class='$class'
        ");

            while ($e = $exam->fetch_assoc()) {
                echo "<div class='inner-card'>
            {$e['subject']} - {$e['exam_date']} - {$e['exam_time']}
            </div>";
            }
        } else {
            echo "<p>No exam routine available.</p>";
        } ?>

    </div>

    <!-- EXAM REPORT -->
    <div class="card">

        <h2>📊 My Exam Report</h2>

        <?php

        $result = $conn->query("
    SELECT * FROM results
    WHERE student_id = $id
    ORDER BY id DESC
    ");

        if ($result && $result->num_rows > 0) {

            while ($r = $result->fetch_assoc()) {

                echo "
            <div class='inner-card'>
                <b>Subject:</b> {$r['subject']} <br>
                <b>Marks:</b> {$r['marks']} <br>
                <b>GPA:</b> {$r['gpa']} <br>
                <b>Term:</b> {$r['term']}
            </div>
            ";
            }
        } else {
            echo "<p>No results available yet.</p>";
        }

        ?>

    </div>

</div>