<?php

$hostname="localhost";
$username="root";
$password="";
$dbname="studentregisteration";

$conn = new mysqli($hostname,$username,$password,$dbname);
if($conn->connect_error){
    die("".$conn->connect_error);
}

?>