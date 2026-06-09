<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location:login.php");
    exit;
}

$user = $_SESSION['user'];
$teacher_id = $user['id'];

/* GPA + GRADE FUNCTION */
function getResult($marks)
{

    if ($marks >= 80) return ["gpa" => 5.00, "grade" => "A+"];
    if ($marks >= 70) return ["gpa" => 4.00, "grade" => "A"];
    if ($marks >= 60) return ["gpa" => 3.50, "grade" => "A-"];
    if ($marks >= 50) return ["gpa" => 3.00, "grade" => "B"];
    if ($marks >= 40) return ["gpa" => 2.00, "grade" => "C"];

    return ["gpa" => 0.00, "grade" => "F"];
}

/* RESULT UPLOAD */
if (isset($_POST['upload_result'])) {

    $sid = $_POST['student_id'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];
    $term = $_POST['term'];

    $result = getResult($marks);
    $gpa = $result["gpa"];
    $grade = $result["grade"];

    $conn->query("
    INSERT INTO results(student_id,subject,marks,gpa,grade,term)
    VALUES('$sid','$subject','$marks','$gpa','$grade','$term')
    ");

    echo "<script>alert('Result Uploaded (GPA: $gpa | Grade: $grade)');</script>";
}

/* ATTENDANCE SUBMIT (FIXED) */
if (isset($_POST['submit_attendance'])) {

    $date = $_POST['att_date'];
    $att = $_POST['att'];

    foreach ($att as $student_id => $status) {

        $conn->query("
        INSERT INTO attendance(student_id,date,status)
        VALUES('$student_id','$date','$status')
        ");
    }

    echo "<script>
        alert('Attendance Submitted Successfully');
        window.location='teacher.php';
    </script>";

    exit;
}
?>

<?php

if (isset($_POST['update_fee'])) {

    $fee_id = $_POST['fee_id'];

    $new_paid = $_POST['new_paid'];

    $fee = $conn->query("
    SELECT * FROM student_fees
    WHERE id=$fee_id
    ")->fetch_assoc();

    $total = $fee['total_fee'];

    $updated_paid = $fee['paid_fee'] + $new_paid;

    $due = $total - $updated_paid;

    if ($due <= 0) {
        $status = "Paid";
        $due = 0;
    } else {
        $status = "Unpaid";
    }

    $conn->query("
    UPDATE student_fees
    SET
    paid_fee='$updated_paid',
    due_fee='$due',
    status='$status'
    WHERE id=$fee_id
    ");

    echo "<script>alert('Fee Updated Successfully');</script>";
}

?>

<link rel="stylesheet" href="dashboard.css">

<!-- SIDEBAR -->
<div class="sidebar">

    <h2>Teacher Panel</h2>

    <a href="index.php">🏠 Homepage</a>
    <a href="teacher.php">📊 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>

</div>

<!-- TOPBAR -->
<div class="topbar">
    <h3>Welcome <?= $user['name'] ?></h3>
</div>

<div class="content">

    <!-- PROFILE -->
    <div class="card">

        <h2>👨‍🏫 My Profile</h2>

        <?php

        $p = $conn->query("
SELECT * FROM teacher_profile
WHERE user_id=$teacher_id
")->fetch_assoc();

        if ($p) {

            echo "
<b>Subject:</b> {$p['subject']} <br>
<b>Phone:</b> {$p['phone']} <br>
<b>Address:</b> {$p['address']}
";
        } else {
            echo "Profile not assigned yet.";
        }

        ?>

    </div>

    <div class="card">

        <h2>💰 My Salary</h2>

        <?php

        $salary = $conn->query("
SELECT * FROM teacher_salary
WHERE teacher_id=$teacher_id
ORDER BY id DESC
");

        if ($salary->num_rows > 0) {

            while ($s = $salary->fetch_assoc()) {

                echo "
        <div class='inner-card'>

        <b>Month:</b> {$s['month']} <br>

        <b>Salary:</b> ৳{$s['salary']} <br>

        <b>Status:</b> {$s['payment_status']}

        </div>
        ";
            }
        } else {

            echo "No salary information available.";
        }

        ?>

    </div>

    <!-- RESULT UPLOAD -->
    <div class="card">

        <h2>📥 Upload Result</h2>

        <form method="post">

            <input type="number" name="student_id" placeholder="Student ID" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <input type="number" name="marks" placeholder="Marks" required>
            <input type="text" name="term" placeholder="Term (Mid/Final)" required>

            <button name="upload_result">Upload Result</button>

        </form>

    </div>

    <!-- STUDENT LIST -->
    <div class="card">

        <h2>👥 Student List</h2>

        <?php

        $students = $conn->query("
SELECT id, name FROM users
WHERE role='student' AND status='approved'
");

        while ($s = $students->fetch_assoc()) {

            $id = $s['id'];

            $att = $conn->query("
SELECT COUNT(*) as total
FROM attendance
WHERE student_id=$id AND status='Present'
")->fetch_assoc()['total'];

            echo "
<div class='inner-card'>
<b>ID:</b> {$s['id']} <br>
<b>Name:</b> {$s['name']} <br>
<b>Total Attendance:</b> {$att}
</div>
";
        }

        ?>

    </div>

    <!-- ATTENDANCE -->
    <div class="card">

        <h2>📋 Take Attendance</h2>

        <form method="post">

            <input type="date" name="att_date" required>

            <br><br>

            <?php

            $students = $conn->query("
SELECT id, name FROM users
WHERE role='student' AND status='approved'
");

            while ($s = $students->fetch_assoc()) {

                echo "
<div style='margin-bottom:10px; padding:10px; background:#0f172a; border-radius:8px;'>

<b>{$s['name']}</b> (ID: {$s['id']})

<br>

<label>
<input type='radio' name='att[{$s['id']}]' value='Present' required> Present
</label>

<label>
<input type='radio' name='att[{$s['id']}]' value='Absent'> Absent
</label>

</div>
";
            }

            ?>

            <button name="submit_attendance">Submit Attendance</button>

        </form>

    </div>

    <!-- CLASS ROUTINE -->
    <div class="card">

        <h2>📚 Class Routine</h2>

        <?php

        $r = $conn->query("SELECT * FROM class_routine");

        while ($row = $r->fetch_assoc()) {
            echo "<div class='inner-card'>
    {$row['class']} - {$row['day']} - {$row['subject']} - {$row['time']}
    </div>";
        }

        ?>

    </div>

    <!-- EXAM ROUTINE -->
    <div class="card">

        <h2>📝 Exam Routine</h2>

        <?php

        $r = $conn->query("SELECT * FROM exam_routine");

        while ($row = $r->fetch_assoc()) {
            echo "<div class='inner-card'>
    {$row['class']} - {$row['subject']} - {$row['exam_date']} - {$row['exam_time']}
    </div>";
        }

        ?>



        <div class="card">

            <h2>💳 Update Student Fees</h2>

            <?php

            $fees = $conn->query("
SELECT * FROM student_fees
ORDER BY id DESC
");

            while ($f = $fees->fetch_assoc()) {

                echo "
<div class='inner-card'>

<b>Fee ID:</b> {$f['id']} <br>

<b>Student ID:</b> {$f['student_id']} <br>

<b>Month:</b> {$f['month']} <br>

<b>Total:</b> ৳{$f['total_fee']} <br>

<b>Paid:</b> ৳{$f['paid_fee']} <br>

<b>Due:</b> ৳{$f['due_fee']} <br>

<b>Status:</b> {$f['status']}

<form method='post' style='margin-top:10px;'>

<input type='hidden' name='fee_id' value='{$f['id']}'>

<input type='number'
name='new_paid'
placeholder='Add Payment'
required>

<button name='update_fee'>
Update Fee
</button>

</form>

</div>
";
            }

            ?>

        </div>

    </div>