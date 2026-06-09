<?php

$conn = new mysqli("localhost", "root", "", "js_school");

if ($conn->connect_error) {
    die("Database connection failed");
}

?>