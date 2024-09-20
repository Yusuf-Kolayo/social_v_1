<?php

require ('head.php');

  // var_dump($_SESSION);

  // check if the user is logged in

  if (isset($_POST['btn_post'])) {  // if button is submitted

    // get form data
    $content  = $_POST['inp_content'];
    $user_id  = $_SESSION['user_id'];
    $timestamp = time();


    if (
      strlen($content)>0
    ) {

            // insert in the table
            $sql = "INSERT INTO posts (user_id,content,timestamp) VALUES(?,?,?)";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, 'sss', $user_id,$content,$timestamp);
            mysqli_stmt_execute($stmt);
            $row = mysqli_stmt_affected_rows($stmt);

            // check for number of rows inserted
            // $row = mysqli_affected_rows($connection);   
            if ($row>0) {
              $alert_class = 'alert-primary';
              $msg = 'posted successfully!';
            } else if ($row==0) {
                $alert_class = 'alert-danger';
                $msg = 'something went wrong';
            }

      
    } else {
       $alert_class = 'alert-danger';
       $msg     = 'Please fill all the required fields';
    }
     

}
?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>SocialV | Responsive Bootstrap 5 Admin Dashboard Template</title>
      
      <link rel="shortcut icon" href="../assets/images/favicon.ico" />
      <link rel="stylesheet" href="../assets/css/libs.min.css">
      <link rel="stylesheet" href="../assets/css/socialv.css?v=4.0.0">
      <link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" href="../assets/vendor/remixicon/fonts/remixicon.css">
      <link rel="stylesheet" href="../assets/vendor/vanillajs-datepicker/dist/css/datepicker.min.css">
      <link rel="stylesheet" href="../assets/vendor/font-awesome-line-awesome/css/all.min.css">
      <link rel="stylesheet" href="../assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
      
  </head>
  <body class="  ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">


      <!-- left side bar -->
      <?php require 'partials/left_side_nav.php'; ?>
      

      <!-- top nav bar  -->
      <?php require 'partials/top_nav.php'; ?>
    
      
      <!-- right nav bar  -->
      <?php require 'partials/right_side_nav.php'; ?>
        
        
        
      <!-- content page  -->
      <div id="content-page" class="content-page">
          <div class="container">
            <div class="row">
                <div class="col-lg-8 row m-0 p-0 d-block">
                  <div class="col-sm-12">
                      <div style="height: 320px;" id="post-modal-data" class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                              <h4 class="card-title">Create Post</h4>
                            </div>
                        </div>
                        <div class="card-body">
                          <?php 
                            // show error messages if there is one
                            if (strlen($msg)>0) {
                                echo '<div class="alert p-2 '.$alert_class.' border">'.$msg.'</div>';
                            }
                          ?>
                           <form action="" method="post">
                                <div class="d-flex align-items-center">
                                    <div class="user-img">
                                        <img src="../assets/images/user/1.jpg" alt="userimg" class="avatar-60 rounded-circle">
                                    </div>
                                    <div class="post-text ms-3 w-100">
                                        <textarea name="inp_content" rows="5" class="form-control rounded" placeholder="Write something here..." style="border:none;"></textarea>
                                    </div>
                                  </div>
                                  <hr>
                                  <ul class=" post-opt-block d-flex list-inline m-0 p-0 flex-wrap">
                                    <li class="me-3 mb-md-0 mb-2">
                                        <button type="submit" name="btn_post" class="btn btn-soft-primary">
                                            Submit
                                        </button>
                                    </li>
                                  </ul>
                              </div>

                           </form>
                      </div>
                  </div>



                  <div class="col-sm-12">


                    <?php
                         // fetch data from posts table

                         // create an sql statement
                         $sql = "SELECT * FROM posts ORDER BY timestamp DESC";
                         // execute the sql statement
                         $result = mysqli_query($connection, $sql);
                         // check if the result is not empty
                         while($row = mysqli_fetch_assoc($result)) {

                          $user_id = $row['user_id'];
                          $content = $row['content'];
                          $timestamp = $row['timestamp'];

                          // format timestamp
                          $date_time = date('d F Y H:i', $timestamp);

                            // get user details using user_id
                            $user_sql = "SELECT * FROM users WHERE id=$user_id";
                            $user_result = mysqli_query($connection, $user_sql);
                            $user_row = mysqli_fetch_assoc($user_result);
                            $first_name = $user_row['first_name'];
                            $last_name  = $user_row['last_name'];

                            echo '
                               <div class="card card-block card-stretch card-height">
                                  <div class="card-body">
                                      <div class="user-post-data">
                                        <div class="d-flex justify-content-between">
                                            <div class="me-3">
                                              <img class="rounded-circle img-fluid" src="../assets/images/user/02.jpg" alt="">
                                            </div>
                                            <div class="w-100">
                                              <div class="d-flex justify-content-between">
                                                  <div class="">
                                                    <h5 class="mb-0 d-inline-block">'.$first_name.'</h5>
                                                    <p class="mb-0 text-primary">'.$date_time.'</p>
                                                  </div>
                                                  <div class="card-post-toolbar">
                                                    <div class="dropdown">
                                                        <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                                        <i class="ri-more-fill"></i>
                                                        </span>
                                                        <div class="dropdown-menu m-0 p-0">
                                                          <a class="dropdown-item p-3" href="#">
                                                              <div class="d-flex align-items-top">
                                                                <i class="ri-save-line h4"></i>
                                                                <div class="data ms-2">
                                                                    <h6>Save Post</h6>
                                                                    <p class="mb-0">Add this to your saved items</p>
                                                                </div>
                                                              </div>
                                                          </a>
                                                          <a class="dropdown-item p-3" href="#">
                                                              <div class="d-flex align-items-top">
                                                                <i class="ri-close-circle-line h4"></i>
                                                                <div class="data ms-2">
                                                                    <h6>Hide Post</h6>
                                                                    <p class="mb-0">See fewer posts like this.</p>
                                                                </div>
                                                              </div>
                                                          </a>
                                                          <a class="dropdown-item p-3" href="#">
                                                              <div class="d-flex align-items-top">
                                                                <i class="ri-user-unfollow-line h4"></i>
                                                                <div class="data ms-2">
                                                                    <h6>Unfollow User</h6>
                                                                    <p class="mb-0">Stop seeing posts but stay friends.</p>
                                                                </div>
                                                              </div>
                                                          </a>
                                                          <a class="dropdown-item p-3" href="#">
                                                              <div class="d-flex align-items-top">
                                                                <i class="ri-notification-line h4"></i>
                                                                <div class="data ms-2">
                                                                    <h6>Notifications</h6>
                                                                    <p class="mb-0">Turn on notifications for this post</p>
                                                                </div>
                                                              </div>
                                                          </a>
                                                        </div>
                                                    </div>
                                                  </div>
                                              </div>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="mt-3">
                                        <p>'.nl2br($content).'</p>
                                      </div>
                                </div>
                            </div>
                            ';
                            
                         } 

                    ?>

                 


                </div>
                </div>



                <div class="col-lg-4">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Stories</h4>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul class="media-story list-inline m-0 p-0">
                            <li class="d-flex mb-3 align-items-center">
                              <i class="ri-add-line"></i>
                              <div class="stories-data ms-3">
                                  <h5>Creat Your Story</h5>
                                  <p class="mb-0">time to story</p>
                              </div>
                            </li>
                            <li class="d-flex mb-3 align-items-center active">
                              <img src="../assets/images/page-img/s2.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Anna Mull</h5>
                                  <p class="mb-0">1 hour ago</p>
                              </div>
                            </li>
                            <li class="d-flex mb-3 align-items-center">
                              <img src="../assets/images/page-img/s3.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Ira Membrit</h5>
                                  <p class="mb-0">4 hour ago</p>
                              </div>
                            </li>
                            <li class="d-flex align-items-center">
                              <img src="../assets/images/page-img/s1.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Bob Frapples</h5>
                                  <p class="mb-0">9 hour ago</p>
                              </div>
                            </li>
                        </ul>
                        <a href="#" class="btn btn-primary d-block mt-3">See All</a>
                      </div>
                  </div>
                  <div class="card">
                      <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Events</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center">
                            <div class="dropdown">
                              <div class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                  <i class="ri-more-fill h4"></i>
                              </div>
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="">
                                  <a class="dropdown-item" href="#"><i class="ri-eye-fill me-2"></i>View</a>
                                  <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill me-2"></i>Delete</a>
                                  <a class="dropdown-item" href="#"><i class="ri-pencil-fill me-2"></i>Edit</a>
                                  <a class="dropdown-item" href="#"><i class="ri-printer-fill me-2"></i>Print</a>
                                  <a class="dropdown-item" href="#"><i class="ri-file-download-fill me-2"></i>Download</a>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul class="media-story list-inline m-0 p-0">
                            <li class="d-flex mb-4 align-items-center ">
                              <img src="../assets/images/page-img/s4.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Web Workshop</h5>
                                  <p class="mb-0">1 hour ago</p>
                              </div>
                            </li>
                            <li class="d-flex align-items-center">
                              <img src="../assets/images/page-img/s5.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Fun Events and Festivals</h5>
                                  <p class="mb-0">1 hour ago</p>
                              </div>
                            </li>
                        </ul>
                      </div>
                  </div>
                  <div class="card">
                      <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Upcoming Birthday</h4>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul class="media-story list-inline m-0 p-0">
                            <li class="d-flex mb-4 align-items-center">
                              <img src="../assets/images/user/01.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Anna Sthesia</h5>
                                  <p class="mb-0">Today</p>
                              </div>
                            </li>
                            <li class="d-flex align-items-center">
                              <img src="../assets/images/user/02.jpg" alt="story-img" class="rounded-circle img-fluid">
                              <div class="stories-data ms-3">
                                  <h5>Paul Molive</h5>
                                  <p class="mb-0">Tomorrow</p>
                              </div>
                            </li>
                        </ul>
                      </div>
                  </div>
                  <div class="card">
                      <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Suggested Pages</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center">
                            <div class="dropdown">
                              <div class="dropdown-toggle" id="dropdownMenuButton01" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                  <i class="ri-more-fill h4"></i>
                              </div>
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton01">
                                  <a class="dropdown-item" href="#"><i class="ri-eye-fill me-2"></i>View</a>
                                  <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill me-2"></i>Delete</a>
                                  <a class="dropdown-item" href="#"><i class="ri-pencil-fill me-2"></i>Edit</a>
                                  <a class="dropdown-item" href="#"><i class="ri-printer-fill me-2"></i>Print</a>
                                  <a class="dropdown-item" href="#"><i class="ri-file-download-fill me-2"></i>Download</a>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul class="suggested-page-story m-0 p-0 list-inline">
                            <li class="mb-3">
                              <div class="d-flex align-items-center mb-3">
                                  <img src="../assets/images/page-img/42.png" alt="story-img" class="rounded-circle img-fluid avatar-50">
                                  <div class="stories-data ms-3">
                                    <h5>Iqonic Studio</h5>
                                    <p class="mb-0">Lorem Ipsum</p>
                                  </div>
                              </div>
                              <img src="../assets/images/small/img-1.jpg" class="img-fluid rounded" alt="Responsive image">
                              <div class="mt-3"><a href="#" class="btn d-block"><i class="ri-thumb-up-line me-2"></i> Like Page</a></div>
                            </li>
                            <li class="">
                              <div class="d-flex align-items-center mb-3">
                                  <img src="../assets/images/page-img/42.png" alt="story-img" class="rounded-circle img-fluid avatar-50">
                                  <div class="stories-data ms-3">
                                    <h5>Cakes & Bakes </h5>
                                    <p class="mb-0">Lorem Ipsum</p>
                                  </div>
                              </div>
                              <img src="../assets/images/small/img-2.jpg" class="img-fluid rounded" alt="Responsive image">
                              <div class="mt-3"><a href="#" class="btn d-block"><i class="ri-thumb-up-line me-2"></i> Like Page</a></div>
                            </li>
                        </ul>
                      </div>
                  </div>
                </div>
                <div class="col-sm-12 text-center">
                  <img src="../assets/images/page-img/page-load-loader.gif" alt="loader" style="height: 100px;">
                </div>
            </div>
          </div>
      </div>


    </div>

    <!-- Wrapper End-->


    <!-- the page footer -->
    <?php require 'partials/footer.php'; ?>

  </body>
</html>