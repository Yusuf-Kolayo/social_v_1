<?php session_start();

   // initialize the $msg variable as empty string
   $msg = '';   $alert_class = '';


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


  // check if button submits to server
  if (isset($_POST['btn-sign-in'])) {

     // get form data
     $email    = $_POST['email'];
     $password = $_POST['password'];

     // check if the form data is not empty
     if (strlen($email)>0&&strlen($password)>0) {
        

            // check for the prescence of email in the DB
            $sql = "SELECT * FROM users WHERE email=?";
            // prepare and bind the SQL statement to protect against SQL injections
            $stmt = mysqli_prepare($connection, $sql);
            // bind parameters
            mysqli_stmt_bind_param($stmt, 's', $email);
            // execute the statement
            mysqli_stmt_execute($stmt);
            // fetch the result  
            $rs = mysqli_stmt_get_result($stmt);
            // count the number of rows
            $n_row = mysqli_num_rows($rs);  

            if ($n_row>0) {
                // check if the password is valid
                $row = mysqli_fetch_assoc($rs);
                $db_password = $row['password'];

                if (password_verify($password, $db_password)) {
                    // set session variables
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_first_name'] = $row['first_name'];
                    $_SESSION['user_last_name'] = $row['last_name'];  // fetch the last name from the database and store it in the session variable
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['user_picture'] = $row['picture'];

                    // redirect to dashboard
                    header("Location: user/dashboard.php");
                    exit();
                } else {
                    $msg = 'Invalid email or password';
                    $alert_class = 'alert-danger';
                }
            } else {

            }
     } else {
        $msg = 'Please fill all fields to continue';
        $alert_class = 'alert-danger';
     }
  }



?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>SocialV | Responsive Bootstrap 4 Admin Dashboard Template</title>
      
      <link rel="shortcut icon" href="assets/images/favicon.ico" />
      <link rel="stylesheet" href="assets/css/libs.min.css">
      <link rel="stylesheet" href="assets/css/socialv.css?v=4.0.0">
      <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" href="assets/vendor/remixicon/fonts/remixicon.css">
      <link rel="stylesheet" href="assets/vendor/vanillajs-datepicker/dist/css/datepicker.min.css">
      <link rel="stylesheet" href="assets/vendor/font-awesome-line-awesome/css/all.min.css">
      <link rel="stylesheet" href="assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
      
  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    
      <div class="wrapper">
    <section class="sign-in-page">
        <div id="container-inside">
            <div id="circle-small"></div>
            <div id="circle-medium"></div>
            <div id="circle-large"></div>
            <div id="circle-xlarge"></div>
            <div id="circle-xxlarge"></div>
        </div>
        <div class="container p-0">
            <div class="row no-gutters">
                <div class="col-md-6 text-center pt-5">
                    <div class="sign-in-detail text-white">
                        <a class="sign-in-logo mb-5" href="#"><img src="assets/images/logo-full.png" class="img-fluid" alt="logo"></a>
                        <div class="sign-slider overflow-hidden ">
                            <ul  class="swiper-wrapper list-inline m-0 p-0 ">
                                <li class="swiper-slide">
                                    <img src="assets/images/login/1.png" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Find new friends</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </li>
                                <li class="swiper-slide">
                                    <img src="assets/images/login/2.png" class="img-fluid mb-4" alt="logo"> 
                                    <h4 class="mb-1 text-white">Connect with the world</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </li>
                                <li class="swiper-slide">
                                    <img src="assets/images/login/3.png" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Create new events</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 bg-white pt-5 pt-5 pb-lg-0 pb-5">
                    <div class="sign-in-from">
                        <h1 class="mb-0">Sign in</h1>
                        <p>Enter your email address and password to access admin panel.</p>
                        <?php 
                           // show error messages if there is one
                           if (strlen($msg)>0) {
                               echo '<div class="alert p-2 '.$alert_class.' border">'.$msg.'</div>';
                           }
                        ?>
                        <form class="mt-4" method="post" action="">
                            <div class="form-group">
                                <label class="form-label" for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control mb-0" name="email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="exampleInputPassword1">Password</label>
                                <a href="#" class="float-end">Forgot password?</a>
                                <input type="password" class="form-control mb-0" name="password" placeholder="Password">
                            </div>
                            <div class="d-inline-block w-100">
                                <!-- <div class="form-check d-inline-block mt-2 pt-1">
                                    <input type="checkbox" class="form-check-input" id="customCheck11">
                                    <label class="form-check-label" for="customCheck11">Remember Me</label>
                                </div> -->
                                <button name="btn-sign-in" type="submit" class="btn btn-primary float-end">Sign in</button>
                            </div>
                            <div class="sign-info">
                                <span class="dark-color d-inline-block line-height-2">Don't have an account? <a href="sign-up.html">Sign up</a></span>
                                <ul class="iq-social-media">
                                    <li><a href="#"><i class="ri-facebook-box-line"></i></a></li>
                                    <li><a href="#"><i class="ri-twitter-line"></i></a></li>
                                    <li><a href="#"><i class="ri-instagram-line"></i></a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>   
      </div>
    
    <!-- Backend Bundle JavaScript -->
    <script src="assets/js/libs.min.js"></script>
    <!-- slider JavaScript -->
    <script src="assets/js/slider.js"></script>
    <!-- masonry JavaScript --> 
    <script src="assets/js/masonry.pkgd.min.js"></script>
    <!-- SweetAlert JavaScript -->
    <script src="assets/js/enchanter.js"></script>
    <!-- SweetAlert JavaScript -->
    <script src="assets/js/sweetalert.js"></script>
    <!-- app JavaScript -->
    <script src="assets/js/charts/weather-chart.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>
    <script src="assets/js/lottie.js"></script>
    
  </body>
</html>