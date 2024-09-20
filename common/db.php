<?php
$host="localhost";
$username="root";
$password=null;
$databse="discuss";

$conn=new mysqli($host,$username,$password,$databse);

if ($conn->connect_error) {
    die("not connected with DB ". $conn->connect_error);
}
?>