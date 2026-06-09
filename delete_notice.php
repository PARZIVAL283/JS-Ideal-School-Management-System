<?php
include 'db.php';

$id=$_GET['id'];

$conn->query("
DELETE FROM notices
WHERE id=$id
");

header("Location:admin.php");
?>