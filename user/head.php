<?php session_start(); 

// initialize the $msg variable
$msg = '';


if (count($_SESSION)==0) {
    header('location:../sign-in.php');
}

if (!isset($_SESSION['user_id'])) {
    header('location:../sign-in.php');
}


if (isset($_POST['btn-sign-out'])) {
    //  delete all data in the session 
    session_unset();
    header('location:../sign-in.php');
}





   // establish connection to DB
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "social_v";

   // Create connection
   $connection = new mysqli($servername, $username, $password, $dbname);

   // Check connection
   if ($connection->connect_error) {
     die("Connection failed: ". $connection->connect_error);
   }


   

// fetch current user data
$user_id = $_SESSION['user_id'];
$user_first_name = $_SESSION['user_first_name'];
$user_last_name = $_SESSION['user_last_name'];
$user_email = $_SESSION['user_email'];
$user_picture = $_SESSION['user_picture'];