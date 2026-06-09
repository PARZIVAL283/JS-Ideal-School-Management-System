<?php include 'db.php'; ?>

<h1>Academic Portal</h1>

<?php
$res=$conn->query("SELECT * FROM results");
while($r=$res->fetch_assoc()){
    echo "Student: ".$r['student_id']." | GPA: ".$r['gpa']."<br>";
}
?>