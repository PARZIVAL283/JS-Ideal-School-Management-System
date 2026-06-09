<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit;
}

/* APPROVE USER */
if (isset($_GET['approve'])) {

    $id = $_GET['approve'];

    $conn->query("
    UPDATE users
    SET status='approved'
    WHERE id=$id
    ");

    echo "<script>alert('User Approved Successfully');</script>";
}

/* DELETE USER */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $conn->query("
    DELETE FROM users
    WHERE id=$id
    ");

    echo "<script>alert('User Deleted Successfully');</script>";
}

/* ADD CLASS ROUTINE */
if (isset($_POST['add_class'])) {

    $conn->query("
    INSERT INTO class_routine(class,subject,day,time)
    VALUES(
        '{$_POST['class']}',
        '{$_POST['subject']}',
        '{$_POST['day']}',
        '{$_POST['time']}'
    )
    ");

    echo "<script>alert('Class Routine Added');</script>";
}

/* ADD EXAM ROUTINE */
if (isset($_POST['add_exam'])) {

    $conn->query("
    INSERT INTO exam_routine(class,subject,exam_date,exam_time)
    VALUES(
        '{$_POST['class']}',
        '{$_POST['subject']}',
        '{$_POST['date']}',
        '{$_POST['time']}'
    )
    ");

    echo "<script>alert('Exam Routine Added');</script>";
}

/* ASSIGN STUDENT PROFILE */
if (isset($_POST['add_profile'])) {

    $conn->query("
    INSERT INTO student_profile(user_id,class,roll,phone,address)
    VALUES(
    '{$_POST['user_id']}',
    '{$_POST['class']}',
    '{$_POST['roll']}',
    '{$_POST['phone']}',
    '{$_POST['address']}'
    )
    ");

    echo "<script>alert('Student Profile Assigned');</script>";
}

/* ASSIGN TEACHER PROFILE */
if (isset($_POST['add_teacher_profile'])) {

    $conn->query("
    INSERT INTO teacher_profile(user_id,subject,phone,address)
    VALUES(
    '{$_POST['user_id']}',
    '{$_POST['subject']}',
    '{$_POST['phone']}',
    '{$_POST['address']}'
    )
    ");

    echo "<script>alert('Teacher Profile Assigned');</script>";
}

/* SAVE SALARY */
if(isset($_POST['save_salary'])){

    $tid = $_POST['teacher_id'];
    $salary = $_POST['salary'];
    $month = $_POST['month'];
    $status = $_POST['payment_status'];

    $conn->query("
    INSERT INTO teacher_salary(teacher_id,salary,month,payment_status)
    VALUES('$tid','$salary','$month','$status')
    ");

    echo "<script>alert('Salary Updated Successfully');</script>";
}

/* ADD NOTICE */
if (isset($_POST['add_notice'])) {

    $notice = $_POST['notice'];

    $conn->query("
    INSERT INTO notices(notice)
    VALUES('$notice')
    ");

    echo "<script>alert('Notice Added Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="dashboard.css">

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h2>Admin Panel</h2>

    <a href="index.php">🏠 Homepage</a>

    <a href="admin.php">📊 Dashboard</a>

    <a href="logout.php">🚪 Logout</a>

</div>

<!-- TOPBAR -->
<div class="topbar">

    <h3>Welcome Admin</h3>

</div>

<!-- CONTENT -->
<div class="content">

<!-- PENDING USERS -->
<div class="card">

    <h2>⏳ Pending Registrations</h2>

    <?php

    $pending = $conn->query("
    SELECT * FROM users
    WHERE status='pending'
    ");

    while ($u = $pending->fetch_assoc()) {

    ?>

        <div class="inner-card">

            <b>ID:</b> <?= $u['id'] ?> <br>
            <b>Name:</b> <?= $u['name'] ?> <br>
            <b>Email:</b> <?= $u['email'] ?> <br>
            <b>Role:</b> <?= $u['role'] ?>

            <br><br>

            <a href="?approve=<?= $u['id'] ?>">Approve</a>

            <a href="?delete=<?= $u['id'] ?>">Delete</a>

        </div>

    <?php } ?>

</div>

<!-- ALL STUDENTS -->
<div class="card">

    <h2>🎓 All Students</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php

        $students = $conn->query("
        SELECT * FROM users
        WHERE role='student'
        AND status='approved'
        ");

        while ($s = $students->fetch_assoc()) {

        ?>

        <tr>

            <td><?= $s['id'] ?></td>

            <td><?= $s['name'] ?></td>

            <td><?= $s['email'] ?></td>

            <td>
                <a href="?delete=<?= $s['id'] ?>">Delete</a>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

<!-- ALL TEACHERS -->
<div class="card">

    <h2>👨‍🏫 All Teachers</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php

        $teachers = $conn->query("
        SELECT * FROM users
        WHERE role='teacher'
        AND status='approved'
        ");

        while ($t = $teachers->fetch_assoc()) {

        ?>

        <tr>

            <td><?= $t['id'] ?></td>

            <td><?= $t['name'] ?></td>

            <td><?= $t['email'] ?></td>

            <td>
                <a href="?delete=<?= $t['id'] ?>">Delete</a>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

<!-- CLASS ROUTINE -->
<div class="card">

    <h2>📚 Manage Class Routine</h2>

    <form method="post">

        <input type="text" name="class" placeholder="Class (1-10)" required>

        <input type="text" name="subject" placeholder="Subject" required>

        <input type="text" name="day" placeholder="Day" required>

        <input type="text" name="time" placeholder="Time" required>

        <button name="add_class">Add Class Routine</button>

    </form>

</div>

<!-- EXAM ROUTINE -->
<div class="card">

    <h2>📝 Manage Exam Routine</h2>

    <form method="post">

        <input type="text" name="class" placeholder="Class" required>

        <input type="text" name="subject" placeholder="Subject" required>

        <input type="date" name="date" required>

        <input type="text" name="time" placeholder="Exam Time" required>

        <button name="add_exam">Add Exam Routine</button>

    </form>

</div>

<!-- STUDENT PROFILE -->
<div class="card">

    <h2>👤 Assign Student Profile</h2>

    <form method="post">

        <input type="number" name="user_id" placeholder="Student User ID" required>

        <input type="text" name="class" placeholder="Class (1-10)" required>

        <input type="text" name="roll" placeholder="Roll" required>

        <input type="text" name="phone" placeholder="Phone">

        <input type="text" name="address" placeholder="Address">

        <button name="add_profile">Save Profile</button>

    </form>

</div>

<!-- TEACHER PROFILE -->
<div class="card">

    <h2>👨‍🏫 Assign Teacher Profile</h2>

    <form method="post">

        <input type="number" name="user_id" placeholder="Teacher User ID" required>

        <input type="text" name="subject" placeholder="Subject" required>

        <input type="text" name="phone" placeholder="Phone">

        <input type="text" name="address" placeholder="Address">

        <button name="add_teacher_profile">Save Teacher Profile</button>

    </form>

</div>

<!-- SALARY -->
<div class="card">

    <h2>💰 Manage Teacher Salary</h2>

    <form method="post">

        <input type="number" name="teacher_id" placeholder="Teacher ID" required>

        <input type="number" name="salary" placeholder="Salary Amount" required>

        <input type="text" name="month" placeholder="Month" required>

        <select name="payment_status">

            <option value="Paid">Paid</option>

            <option value="Unpaid">Unpaid</option>

        </select>

        <button name="save_salary">Save Salary</button>

    </form>

</div>

<!-- NOTICE BOARD -->
<div class="card">

    <h2>📢 Manage Notice Board</h2>

    <form method="post">

        <textarea
        name="notice"
        placeholder="Write Notice..."
        required></textarea>

        <br><br>

        <button name="add_notice">
            Add Notice
        </button>

    </form>

    <hr>

    <h3>All Notices</h3>

    <?php

    $notices = $conn->query("
    SELECT * FROM notices
    ORDER BY id DESC
    ");

    while ($n = $notices->fetch_assoc()) {

    ?>

        <div class="notice-box">

            <?= $n['notice'] ?>

            <br><br>

            <small>
                <?= $n['created_at'] ?>
            </small>

            <br><br>

            <a href="delete_notice.php?id=<?= $n['id'] ?>">
                Delete
            </a>

        </div>

    <?php } ?>

</div>

</div>

</body>

</html>