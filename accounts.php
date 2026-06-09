<?php include 'db.php'; ?>

<h1>Accounts Portal</h1>

<?php
$res=$conn->query("SELECT * FROM fees");
while($f=$res->fetch_assoc()){
    echo "Student ID: ".$f['student_id']." | Due: ".$f['due']."<br>";
}
?>