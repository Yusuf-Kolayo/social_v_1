<?php session_start(); 

// initialize the $msg variable
$msg = '';


if (count($_SESSION)==0) {
    header('location:../sign-in.php');
}

if (!isset($_SESSION['id'])) {
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
$user_id = $_SESSION['id'];
$user_first_name = $_SESSION['first_name'];
$user_last_name = $_SESSION['last_name'];
$user_email = $_SESSION['email'];
$user_picture = $_SESSION['picture'];
$user_phone = $_SESSION['phone'];
$user_address = $_SESSION['address'];
$user_website = $_SESSION['website'];
$user_social_link = $_SESSION['social_link'];
$user_birth_date = $_SESSION['birth_date'];
$user_birth_year = $_SESSION['birth_year'];
$user_gender = $_SESSION['gender'];
$user_interest = $_SESSION['interest'];
$user_language = $_SESSION['language'];

// var_dump($_SESSION);
