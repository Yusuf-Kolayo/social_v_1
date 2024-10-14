<?php

require ('partials/head.php');

//   var_dump($_SESSION);

if (isset($_POST['btn_update_profile'])) {
    // get form data
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $address  = $_POST['address'];

    $website  = $_POST['website'];
    $social_link  = $_POST['social_link'];

    $birth_date  = $_POST['birth_date'];
    $birth_year  = $_POST['birth_year'];

    $gender    = $_POST['gender'];
    $interest  = $_POST['interest'];
    $language  = $_POST['language'];   


    
    if (
      strlen($first_name)>0&&
      strlen($last_name)>0&&
      strlen($email)>0&&
      strlen($phone)>0&&
      strlen($address)>0
    ) {

       // check for the prescence of email in the DB
       $sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, address=?, website=?,social_link=?,birth_date=?,birth_year=?,gender=?,interest=?,language=? WHERE id = '$user_id'";
       // prepare and bind the SQL statement to protect against SQL injections
       $stmt = mysqli_prepare($connection, $sql);
       // bind parameters
       mysqli_stmt_bind_param($stmt, 'ssssssssssss', $first_name, $last_name, $email, $phone, $address, $website, $social_link, $birth_date, $birth_year, $gender, $interest, $language);
       // execute the statement
       mysqli_stmt_execute($stmt);
         // count the number of rows
       $n_row = mysqli_stmt_affected_rows($stmt);

       if ($n_row) {
                  // check for the prescence of email in the DB
            $sql_x = "SELECT * FROM users WHERE id=?";
            // prepare and bind the SQL statement to protect against SQL injections
            $stmt_x = mysqli_prepare($connection, $sql_x);
            // bind parameters
            mysqli_stmt_bind_param($stmt_x, 's', $user_id);
            // execute the statement
            mysqli_stmt_execute($stmt_x);
            // fetch the result  
            $rs_x = mysqli_stmt_get_result($stmt_x);
            // count the number of rows
            $n_row_x = mysqli_fetch_assoc($rs_x);  

             // set session variables
               $_SESSION = array_merge($_SESSION, $n_row_x);


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


         $msg = "Profile updated successfully.";
         $alert_class = 'alert-success';
       } else {
         $msg = "Error updating profile.";
         $alert_class = 'alert-danger';
       }

    } else {
      $msg = "Please fill in all fields.";
      $alert_class = 'alert-danger';
    }

    
}




if (isset($_POST['btn_update_dp'])) {

    // get form data
    $picture = $_FILES['user_dp']['name'];
    $picture_tmp_name = $_FILES['user_dp']['tmp_name'];
    $picture_size = $_FILES['user_dp']['size'];
    $picture_type = $_FILES['user_dp']['type'];

    // fetch the user id from session
    $user_id = $_SESSION['id'];

    // fetch old picture
     $user_sql = "SELECT picture FROM users WHERE id=$user_id";
     $user_result = mysqli_query($connection, $user_sql);
     $user_row = mysqli_fetch_assoc($user_result);
     $user_old_picture = $user_row['picture'];

    // check for allowed picture type
    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/webp');
    if (!in_array($picture_type, $allowed_types)) {
      $msg = "Invalid picture format. Only JPEG, JPG, PNG, and WebP are allowed.";
      $alert_class = 'alert-danger';
    } else {
      // check for picture size
      if ($picture_size > 1048576) {
        $msg = "Picture size should not exceed 1MB.";
        $alert_class = 'alert-danger';
      } else {
         // modify picture final file name
         $user_picture = $user_id.'_'.time().'.'.pathinfo($picture, PATHINFO_EXTENSION);

         // upload the picture
         $upload_result = move_uploaded_file($picture_tmp_name, '../assets/images/users_dp/'.$user_picture);

         if ($upload_result) {
            // delete the old picture
            if ($user_old_picture!=null && file_exists('../assets/images/users_dp/'.$user_old_picture)) {
               unlink('../assets/images/users_dp/'.$user_old_picture);
            }

             // update the picture in the database
             $sql_update_dp = "UPDATE users SET picture='$user_picture' WHERE id=$user_id";
             $update_dp_result = mysqli_query($connection, $sql_update_dp);

             if ($update_dp_result) {
                $msg = "Profile picture updated successfully.";
                $alert_class = 'alert-success';
                $_SESSION['picture'] = $user_picture;
             } else {
                $msg = "Error updating profile picture.";
                $alert_class = 'alert-danger';
             }
         }
      }
   }
}


if ($user_picture==null) {
   $user_dp_html = '<img src="../assets/images/avatar_dummy.png" alt="profile-img" class="avatar-130 img-fluid" />';
} else {
   $user_dp_html = '<img src="../assets/images/users_dp/'.$user_picture.'" alt="profile-img" class="avatar-130 img-fluid" />';
}
?>



      <!-- left side bar -->
      <?php require 'partials/left_side_nav.php'; ?>
      

      <!-- top nav bar  -->
      <?php require 'partials/top_nav.php'; ?>
    
      
      <!-- right nav bar  -->
      <?php require 'partials/right_side_nav.php'; ?>
        
        
       


      <div id="content-page" class="content-page">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="card">
                     <div class="card-body profile-page p-0">
                        <div class="profile-header">
                           <div class="position-relative">
                              <img src="../assets/images/page-img/profile-bg1.jpg" alt="profile-bg" class="rounded img-fluid">
                              <ul class="header-nav list-inline d-flex flex-wrap justify-end p-0 m-0">
                                 <li><a href="#"><i class="ri-pencil-line"></i></a></li>
                                 <li><a href="#"><i class="ri-settings-4-line"></i></a></li>
                              </ul>
                           </div>
                           <div class="user-detail text-center mb-3">
                              <div class="profile-img">
                                 <?=$user_dp_html?>
                                <div class="">
                                    <button class="btn btn-primary m-1 d-block mx-auto btn-sm" data-bs-toggle="modal" data-bs-target="#dp-update-modal">Update DP</button>
                                    <div class="modal fade" id="dp-update-modal" tabindex="-1"  aria-labelledby="post-modalLabel" aria-hidden="true" >
                                    <div class="modal-dialog  modal-xs modal-fullscreen-sm-down">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h5 class="modal-title" id="post-modalLabel">Update Profile</h5>
                                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ri-close-fill"></i></button>
                                          </div>
                                          <div class="modal-body">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                      <div class="text-center pb-2">
                                                         <?=$user_dp_html?>
                                                      </div>
                                                      <input type="file" class="form-control my-2" name="user_dp">
                                                      <button type="submit" name="btn_update_dp" class="btn btn-primary d-block w-100 mt-3">Update DP</button>
                                                </form>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                </div>
                              </div>
                              <div class="profile-detail">
                                 <h3 class=""><?php echo "$user_first_name $user_last_name"; ?></h3>
                              </div>
                           </div>
                           <div class="profile-info p-3 d-flex align-items-center justify-content-between position-relative">
                              <div class="social-links">
                                 <ul class="social-data-block d-flex align-items-center justify-content-between list-inline p-0 m-0">
                                    <li class="text-center pe-3">
                                       <a href="#"><img src="../assets/images/icon/08.png" class="img-fluid rounded" alt="facebook"></a>
                                    </li>
                                    <li class="text-center pe-3">
                                       <a href="#"><img src="../assets/images/icon/09.png" class="img-fluid rounded" alt="Twitter"></a>
                                    </li>
                                    <li class="text-center pe-3">
                                       <a href="#"><img src="../assets/images/icon/10.png" class="img-fluid rounded" alt="Instagram"></a>
                                    </li>
                                    <li class="text-center pe-3">
                                       <a href="#"><img src="../assets/images/icon/11.png" class="img-fluid rounded" alt="Google plus"></a>
                                    </li>
                                    <li class="text-center pe-3">
                                       <a href="#"><img src="../assets/images/icon/12.png" class="img-fluid rounded" alt="You tube"></a>
                                    </li>
                                    <li class="text-center md-pe-3 pe-0">
                                       <a href="#"><img src="../assets/images/icon/13.png" class="img-fluid rounded" alt="linkedin"></a>
                                    </li>
                                 </ul>
                              </div>
                              <div class="social-info">
                                 <ul class="social-data-block d-flex align-items-center justify-content-between list-inline p-0 m-0">
                                    <li class="text-center ps-3">
                                       <h6>Posts</h6>
                                       <p class="mb-0">690</p>
                                    </li>
                                    <li class="text-center ps-3">
                                       <h6>Followers</h6>
                                       <p class="mb-0">206</p>
                                    </li>
                                    <li class="text-center ps-3">
                                       <h6>Following</h6>
                                       <p class="mb-0">100</p>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <?php 
                     // show error messages if there is one
                     if (strlen($msg)>0) {
                           echo '<div class="alert p-2 '.$alert_class.' border">'.$msg.'</div>';
                     }
                  ?>

                  <div class="card">
                     <div class="card-body p-0">
                        <div class="user-tabing">
                           <ul class="nav nav-pills d-flex align-items-center justify-content-center profile-feed-items p-0 m-0">
                              <li class="nav-item col-12 col-sm-3 p-0">
                                 <a class="nav-link active" href="#pills-timeline-tab" data-bs-toggle="pill" data-bs-target="#timeline" role="button">Timeline</a>
                              </li>
                              <li class="nav-item col-12 col-sm-3 p-0">
                                 <a class="nav-link" href="#pills-about-tab" data-bs-toggle="pill" data-bs-target="#about" role="button">About</a>
                              </li>
                              <li class="nav-item col-12 col-sm-3 p-0">
                                 <a class="nav-link" href="#pills-friends-tab" data-bs-toggle="pill" data-bs-target="#friends" role="button">Friends</a>
                              </li>
                              <li class="nav-item col-12 col-sm-3 p-0">
                                 <a class="nav-link" href="#pills-photos-tab" data-bs-toggle="pill" data-bs-target="#photos" role="button">Photos</a>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-12">
                  <div class="tab-content">
                     <div class="tab-pane fade show active" id="timeline" role="tabpanel">
                        <div class="card-body p-0">
                           <div class="row">
                              <div class="col-lg-4">
                                 <div class="card">
                                    <div class="card-body">
                                       <a href="#"><span class="badge badge-pill bg-primary font-weight-normal ms-auto me-1"><i class="ri-star-line"></i></span> 27 Items for yoou</a>
                                    </div>
                                 </div>
                                 <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                       <div class="header-title">
                                          <h4 class="card-title">Life Event</h4>
                                       </div>
                                       <div class="card-header-toolbar d-flex align-items-center">
                                          <p class="m-0"><a href="javacsript:void();"> Create </a></p>
                                       </div>
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12">
                                             <div class="event-post position-relative">
                                                <a href="#"><img src="../assets/images/page-img/07.jpg" alt="gallary-image" class="img-fluid rounded"></a>
                                                <div class="job-icon-position">
                                                   <div class="job-icon bg-primary p-2 d-inline-block rounded-circle"><i class="ri-briefcase-line text-white"></i></div>
                                                </div>
                                                <div class="card-body text-center p-2">
                                                   <h5>Started New Job at Apple</h5>
                                                   <p>January 24, 2019</p>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-sm-12">
                                             <div class="event-post position-relative">
                                                <a href="#"><img src="../assets/images/page-img/06.jpg" alt="gallary-image" class="img-fluid rounded"></a>
                                                <div class="job-icon-position">
                                                   <div class="job-icon bg-primary p-2 d-inline-block rounded-circle"><i class="ri-briefcase-line text-white"></i></div>
                                                </div>
                                                <div class="card-body text-center p-2">
                                                   <h5>Freelance Photographer</h5>
                                                   <p class="mb-0">January 24, 2019</p>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                       <div class="header-title">
                                          <h4 class="card-title">Photos</h4>
                                       </div>
                                       <div class="card-header-toolbar d-flex align-items-center">
                                          <p class="m-0"><a href="javacsript:void();">Add Photo </a></p>
                                       </div>
                                    </div>
                                    <div class="card-body">
                                       <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g1.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g2.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g3.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g4.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g5.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g6.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g7.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g8.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                          <li class=""><a href="#"><img src="../assets/images/page-img/g9.jpg" alt="gallary-image" class="img-fluid" /></a></li>
                                       </ul>
                                    </div>
                                 </div>
                                 <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                       <div class="header-title">
                                          <h4 class="card-title">Friends</h4>
                                       </div>
                                       <div class="card-header-toolbar d-flex align-items-center">
                                          <p class="m-0"><a href="javacsript:void();">Add New </a></p>
                                       </div>
                                    </div>
                                    <div class="card-body">
                                       <ul class="profile-img-gallary p-0 m-0 list-unstyled">
                                          <li class="">
                                             <a href="#">
                                             <img src="../assets/images/user/05.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Anna Rexia</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/06.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Tara Zona</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/07.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Polly Tech</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/08.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Bill Emia</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/09.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Moe Fugga</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/10.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Hal Appeno </h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/07.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Zack Lee</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/06.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Terry Aki</h6>
                                          </li>
                                          <li class="">
                                             <a href="#"><img src="../assets/images/user/05.jpg" alt="gallary-image" class="img-fluid" /></a>
                                             <h6 class="mt-2 text-center">Greta Life</h6>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-8">
                                 <div id="post-modal-data" class="card">
                                    <div class="card-header d-flex justify-content-between">
                                       <div class="header-title">
                                          <h4 class="card-title">Create Post</h4>
                                       </div>
                                    </div>
                                    <div class="card-body">
                                       <div class="d-flex align-items-center">
                                          <div class="user-img">
                                             <img src="../assets/images/user/1.jpg" alt="userimg" class="avatar-60 rounded-circle">
                                          </div>
                                          <form class="post-text ms-3 w-100 "  data-bs-toggle="modal" data-bs-target="#post-modal" action="#">
                                             <input type="text" class="form-control rounded" placeholder="Write something here..." style="border:none;">
                                          </form>
                                       </div>
                                       <hr>
                                       <ul class=" post-opt-block d-flex list-inline m-0 p-0 flex-wrap">
                                             <li class="bg-soft-primary rounded p-2 pointer d-flex align-items-center me-3 mb-md-0 mb-2"><img src="../assets/images/small/07.png" alt="icon" class="img-fluid me-2"> Photo/Video</li>
                                             <li class="bg-soft-primary rounded p-2 pointer d-flex align-items-center me-3 mb-md-0 mb-2"><img src="../assets/images/small/08.png" alt="icon" class="img-fluid me-2"> Tag Friend</li>
                                             <li class="bg-soft-primary rounded p-2 pointer d-flex align-items-center me-3"><img src="../assets/images/small/09.png" alt="icon" class="img-fluid me-2"> Feeling/Activity</li>
                                             <li class="bg-soft-primary rounded p-2 pointer text-center">
                                                <div class="card-header-toolbar d-flex align-items-center">
                                                <div class="dropdown">
                                                   <div class="dropdown-toggle" id="post-option"   data-bs-toggle="dropdown">
                                                         <i class="ri-more-fill h4"></i>
                                                   </div>
                                                   <div class="dropdown-menu dropdown-menu-right" aria-labelledby="post-option" style="">
                                                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#post-modal">Check in</a>
                                                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#post-modal">Live Video</a>
                                                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#post-modal">Gif</a>
                                                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#post-modal">Watch Party</a>
                                                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#post-modal">Play with Friend</a>
                                                   </div>
                                                </div>
                                                </div>
                                             </li>
                                       </ul>
                                    </div>
                              </div>
                               
                                             <?php

                                                   
                                                   // fetch data from posts table

                                                   // create an sql statement
                                                   $sql = "SELECT * FROM posts WHERE user_id='$user_id' ORDER BY timestamp DESC";
                                                   // execute the sql statement
                                                   $result = mysqli_query($connection, $sql);
                                                   // check if the result is not empty
                                                   while($row = mysqli_fetch_assoc($result)) {

                                                   $user_id = $row['user_id'];
                                                   $content = $row['content'];
                                                      // shorten the content
                                                   if (strlen($content)>300) {
                                                      $content = substr($content, 0, 300). ' ..';
                                                   } 
                                                   
                                                   
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
                        </div>
                     </div>
                     <div class="tab-pane fade" id="about" role="tabpanel" >
                        <div class="card">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-3">
                                    <ul class="nav nav-pills basic-info-items list-inline d-block p-0 m-0">
                                       <li>
                                          <a class="nav-link active" href="#v-pills-basicinfo-tab" data-bs-toggle="pill" data-bs-target="#v-pills-basicinfo-tab" role="button">Contact and Basic Info</a>
                                       </li>
                                       <li>
                                          <a class="nav-link" href="#v-pills-family-tab" data-bs-toggle="pill" data-bs-target="#v-pills-family" role="button">Family and Relationship</a>
                                       </li>
                                       <li>
                                          <a class="nav-link" href="#v-pills-work-tab" data-bs-toggle="pill" data-bs-target="#v-pills-work-tab" role="button">Work and Education</a>
                                       </li>
                                       <li>
                                          <a class="nav-link" href="#v-pills-lived-tab" data-bs-toggle="pill" data-bs-target="#v-pills-lived-tab" role="button">Places You've Lived</a>
                                       </li>
                                       <li>
                                          <a class="nav-link" href="#v-pills-details-tab" data-bs-toggle="pill" data-bs-target="#v-pills-details-tab" role="button">Details About You</a>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="col-md-9 ps-4">
                                    <div class="tab-content" >
                                       <div class="tab-pane fade active show" id="v-pills-basicinfo-tab" role="tabpanel"  aria-labelledby="v-pills-basicinfo-tab">
                                          <h4>
                                             Contact Information
                                             <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#post-modal">Update Profile</button>
                                          </h4>

                                          <div class="modal fade" id="post-modal" tabindex="-1"  aria-labelledby="post-modalLabel" aria-hidden="true" >
                                             <div class="modal-dialog  modal-lg modal-fullscreen-sm-down">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <h5 class="modal-title" id="post-modalLabel">Update Profile</h5>
                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ri-close-fill"></i></button>
                                                   </div>
                                                   <div class="modal-body">
                                                        <form action="" method="post">
                                                            <div class="row">
                                                               <div class="col-3">
                                                                  <h6>Firstname</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="first_name" required value="<?=$user_first_name?>" class="form-control form-control-sm mb-1" />
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Lastname</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="last_name" required value="<?=$user_last_name?>" class="form-control form-control-sm mb-1"/>
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Email</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="email" name="email" required value="<?=$user_email?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Mobile</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="phone" required value="<?=$user_phone?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Address</h6>
                                                               </div>
                                                               <div class="col-9">    
                                                                  <input type="text" name="address" required value="<?=$user_address?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                            </div>
                                                            <h4 class="mt-3">Websites and Social Links</h4>
                                                            <hr>
                                                            <div class="row">
                                                               <div class="col-3">
                                                                  <h6>Website</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="website" value="<?=$user_website?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Social Link</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="social_link" value="<?=$user_social_link?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                            </div>
                                                            <h4 class="mt-3">Basic Information</h4>
                                                            <hr>
                                                            <div class="row">
                                                               <div class="col-3">
                                                                  <h6>Birth Date</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="birth_date" value="<?=$user_birth_date?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Birth Year</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="birth_year" value="<?=$user_birth_year?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>Gender</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                     <input type="text" name="gender" value="<?=$user_gender?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>interested in</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                  <input type="text" name="interest" value="<?=$user_interest?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                               <div class="col-3">
                                                                  <h6>language</h6>
                                                               </div>
                                                               <div class="col-9">
                                                                     <input type="text" name="language" value="<?=$user_language?>" class="form-control form-control-sm mb-1">
                                                               </div>
                                                            </div>
                                                            
                                                               <button type="submit" name="btn_update_profile" class="btn btn-primary d-block w-100 mt-3">Update Profile</button>
                                                        </form>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          
                                          
                                          <hr>
                                          <div class="row">
                                             <div class="col-3">
                                                <h6>Firstname</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_first_name?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Lastname</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_last_name?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Email</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_email?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Mobile</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_phone?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Address</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_address?></p>
                                             </div>
                                          </div>
                                          <h4 class="mt-3">Websites and Social Links</h4>
                                          <hr>
                                          <div class="row">
                                             <div class="col-3">
                                                <h6>Website</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_website?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Social Link</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_social_link?></p>
                                             </div>
                                          </div>
                                          <h4 class="mt-3">Basic Information</h4>
                                          <hr>
                                          <div class="row">
                                             <div class="col-3">
                                                <h6>Birth Date</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_birth_date?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Birth Year</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_birth_year?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>Gender</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_gender?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>interested in</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_interest?></p>
                                             </div>
                                             <div class="col-3">
                                                <h6>language</h6>
                                             </div>
                                             <div class="col-9">
                                                <p class="mb-0"><?=$user_language?></p>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="tab-pane fade" id="v-pills-family" role="tabpanel">
                                          <h4 class="mb-3">Relationship</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex mb-4 align-items-center">
                                                <div class="user-img img-fluid"><i class="ri-add-fill"></i></div>
                                                <div class="media-support-info ms-3">
                                                   <h6>Add Your Relationship Status</h6>
                                                </div>
                                             </li>
                                          </ul>
                                          <h4 class="mt-3 mb-3">Family Members</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex mb-4 align-items-center">
                                                <div class="user-img img-fluid"><i class="ri-add-fill"></i></div>
                                                <div class="media-support-info ms-3">
                                                   <h6>Add Family Members</h6>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/01.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>Paul Molive</h6>
                                                         <p class="mb-0">Brothe</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                             <li class="d-flex justify-content-between mb-4  align-items-center">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/02.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex flex-wrap justify-content-between">
                                                      <div class=" ms-3">
                                                         <h6>Anna Mull</h6>
                                                         <p class="mb-0">Sister</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/03.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>Paige Turner</h6>
                                                         <p class="mb-0">Cousin</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                          </ul>
                                       </div>
                                       <div class="tab-pane fade" id="v-pills-work-tab" role="tabpanel" aria-labelledby="v-pills-work-tab">
                                          <h4 class="mb-3">Work</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex justify-content-between mb-4  align-items-center">
                                                <div class="user-img img-fluid"><i class="ri-add-fill"></i></div>
                                                <div class="ms-3">
                                                   <h6>Add Work Place</h6>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/01.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>Themeforest</h6>
                                                         <p class="mb-0">Web Designer</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/02.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex flex-wrap justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>iqonicdesign</h6>
                                                         <p class="mb-0">Web Developer</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/03.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex flex-wrap justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>W3school</h6>
                                                         <p class="mb-0">Designer</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                          </ul>
                                          <h4 class="mb-3">Professional Skills</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex mb-4 align-items-center">
                                                <div class="user-img img-fluid"><i class="ri-add-fill"></i></div>
                                                <div class="ms-3">
                                                   <h6>Add Professional Skills</h6>
                                                </div>
                                             </li>
                                          </ul>
                                          <h4 class="mt-3 mb-3">College</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex mb-4 align-items-center">
                                                <div class="user-img img-fluid"><i class="ri-add-fill"></i></div>
                                                <div class="ms-3">
                                                   <h6>Add College</h6>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/01.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex flex-wrap justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>Lorem ipsum</h6>
                                                         <p class="mb-0">USA</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                          </ul>
                                       </div>
                                       <div class="tab-pane fade" id="v-pills-lived-tab" role="tabpanel" aria-labelledby="v-pills-lived-tab">
                                          <h4 class="mb-3">Current City and Hometown</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/01.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex flex-wrap justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>Georgia</h6>
                                                         <p class="mb-0">Georgia State</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                             <li class="d-flex mb-4 align-items-center justify-content-between">
                                                <div class="user-img img-fluid"><img src="../assets/images/user/02.jpg" alt="story-img" class="rounded-circle avatar-40"></div>
                                                <div class="w-100">
                                                   <div class="d-flex flex-wrap justify-content-between">
                                                      <div class="ms-3">
                                                         <h6>Atlanta</h6>
                                                         <p class="mb-0">Atlanta City</p>
                                                      </div>
                                                      <div class="edit-relation"><a href="#"><i class="ri-edit-line me-2"></i>Edit</a></div>
                                                   </div>
                                                </div>
                                             </li>
                                          </ul>
                                          <h4 class="mt-3 mb-3">Other Places Lived</h4>
                                          <ul class="suggestions-lists m-0 p-0">
                                             <li class="d-flex mb-4 align-items-center">
                                                <div class="user-img img-fluid"><i class="ri-add-fill"></i></div>
                                                <div class="ms-3">
                                                   <h6>Add Place</h6>
                                                </div>
                                             </li>
                                          </ul>
                                       </div>
                                       <div class="tab-pane fade" id="v-pills-details-tab" role="tabpanel" aria-labelledby="v-pills-details-tab">
                                          <h4 class="mb-3">About You</h4>
                                          <p>Hi, I’m Bni, I’m 26 and I work as a Web Designer for the iqonicdesign.</p>
                                          <h4 class="mt-3 mb-3">Other Name</h4>
                                          <p>Bini Rock</p>
                                          <h4 class="mt-3 mb-3">Favorite Quotes</h4>
                                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="friends" role="tabpanel">
                        <div class="card">
                           <div class="card-body">
                              <h2>Friends</h2>
                              <div class="friend-list-tab mt-2">
                                 <ul class="nav nav-pills d-flex align-items-center justify-content-left friend-list-items p-0 mb-2">
                                    <li>
                                       <a class="nav-link active" data-bs-toggle="pill" href="#pill-all-friends" data-bs-target="#all-feinds">All Friends</a>
                                    </li>
                                    <li>
                                       <a class="nav-link" data-bs-toggle="pill" href="#pill-recently-add" data-bs-target="#recently-add">Recently Added</a>
                                    </li>
                                    <li>
                                       <a class="nav-link" data-bs-toggle="pill" href="#pill-closefriends" data-bs-target="#closefriends"> Close friends</a>
                                    </li>
                                    <li>
                                       <a class="nav-link" data-bs-toggle="pill" href="#pill-home" data-bs-target="#home-town"> Home/Town</a>
                                    </li>
                                    <li>
                                       <a class="nav-link" data-bs-toggle="pill" href="#pill-following" data-bs-target="#following">Following</a>
                                    </li>
                                 </ul>
                                 <div class="tab-content">
                                    <div class="tab-pane fade active show" id="all-friends" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="row">
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Petey Cruiser</h5>
                                                            <p class="mb-0">15  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton01" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton01">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Anna Sthesia</h5>
                                                            <p class="mb-0">50  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton02" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton02">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Paul Molive</h5>
                                                            <p class="mb-0">10  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton03" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton03">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/08.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Gail Forcewind</h5>
                                                            <p class="mb-0">20  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton04" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton04">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/09.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Paige Turner</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton05" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton05">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/10.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>b Frapples</h5>
                                                            <p class="mb-0">6  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton06" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton06">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/13.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Walter Melon</h5>
                                                            <p class="mb-0">30  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton07" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton07">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/14.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Barb Ackue</h5>
                                                            <p class="mb-0">14  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton08" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton08">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/15.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Buck Kinnear</h5>
                                                            <p class="mb-0">16  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton09" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton09">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/16.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Ira Membrit</h5>
                                                            <p class="mb-0">22  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton10" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton10">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/17.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Shonda Leer</h5>
                                                            <p class="mb-0">10  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton11" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton11">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/18.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>ock Lee</h5>
                                                            <p class="mb-0">18  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton12" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton12">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/19.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Maya Didas</h5>
                                                            <p class="mb-0">40  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton13" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton13">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Rick O'Shea</h5>
                                                            <p class="mb-0">50  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton14" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton14">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Pete Sariya</h5>
                                                            <p class="mb-0">5  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton15" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton15">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Monty Carlo</h5>
                                                            <p class="mb-0">2  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton16" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton16">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/08.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Sal Monella</h5>
                                                            <p class="mb-0">0  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton17" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton17">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/09.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Sue Vaneer</h5>
                                                            <p class="mb-0">25  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton18" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton18">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/10.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Cliff Hanger</h5>
                                                            <p class="mb-0">18  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton19" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton19">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Barb Dwyer</h5>
                                                            <p class="mb-0">23  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton20" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton20">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Terry Aki</h5>
                                                            <p class="mb-0">8  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton21" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton21">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/13.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Cory Ander</h5>
                                                            <p class="mb-0">7  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton22" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton22">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/14.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Robin Banks</h5>
                                                            <p class="mb-0">14  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton23" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton23">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/15.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Jimmy Changa</h5>
                                                            <p class="mb-0">10  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton24" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton24">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/16.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Barry Wine</h5>
                                                            <p class="mb-0">18  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton25" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton25">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/17.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Poppa Cherry</h5>
                                                            <p class="mb-0">16  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton26" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton26">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/18.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Zack Lee</h5>
                                                            <p class="mb-0">33  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton27" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton27">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/19.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Don Stairs</h5>
                                                            <p class="mb-0">15  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton28" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton28">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Peter Pants</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton29" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton29">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Hal Appeno </h5>
                                                            <p class="mb-0">13  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton30" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton30">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="recently-add" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="row">
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Otto Matic</h5>
                                                            <p class="mb-0">4  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton31" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton31">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/08.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Moe Fugga</h5>
                                                            <p class="mb-0">16  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton32" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton32">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/09.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Tom Foolery</h5>
                                                            <p class="mb-0">14  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton33" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton33">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/10.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Bud Wiser</h5>
                                                            <p class="mb-0">16  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton34" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton34">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/15.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Polly Tech</h5>
                                                            <p class="mb-0">10  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton35" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton35">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/16.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Holly Graham</h5>
                                                            <p class="mb-0">8  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton36" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton36">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/17.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Tara Zona</h5>
                                                            <p class="mb-0">5  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton37" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton37">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/18.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Barry Cade</h5>
                                                            <p class="mb-0">20  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton38" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton38">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="closefriends" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="row">
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/19.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Bud Wiser</h5>
                                                            <p class="mb-0">32  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton39" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton39">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Otto Matic</h5>
                                                            <p class="mb-0">9  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton40" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton40">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Peter Pants</h5>
                                                            <p class="mb-0">2  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton41" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton41">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Zack Lee</h5>
                                                            <p class="mb-0">15  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton42" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton42">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/08.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Barry Wine</h5>
                                                            <p class="mb-0">36  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton43" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton43">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/09.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Robin Banks</h5>
                                                            <p class="mb-0">22  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton44" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton44">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/10.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Cory Ander</h5>
                                                            <p class="mb-0">18  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton45" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton45">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/15.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Moe Fugga</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton46" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton46">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/16.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Polly Tech</h5>
                                                            <p class="mb-0">30  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton47" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton47">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/17.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Hal Appeno</h5>
                                                            <p class="mb-0">25  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton48" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton48">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="home-town" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="row">
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/18.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Paul Molive</h5>
                                                            <p class="mb-0">14  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton49" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton49">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/19.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Paige Turner</h5>
                                                            <p class="mb-0">8  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton50" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton50">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Barb Ackue</h5>
                                                            <p class="mb-0">23  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton51" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton51">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Ira Membrit</h5>
                                                            <p class="mb-0">16  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton52" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton52">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Maya Didas</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton53" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton53">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="following" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="row">
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Maya Didas</h5>
                                                            <p class="mb-0">20  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton54" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton54">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Monty Carlo</h5>
                                                            <p class="mb-0">3  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton55" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton55">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Cliff Hanger</h5>
                                                            <p class="mb-0">20  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton56" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton56">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/08.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>b Ackue</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton57" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton57">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/09.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Bob Frapples</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton58" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton58">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/10.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Anna Mull</h5>
                                                            <p class="mb-0">6  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton59" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton59">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/15.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>ry Wine</h5>
                                                            <p class="mb-0">15  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton60" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton60">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/16.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Don Stairs</h5>
                                                            <p class="mb-0">12  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton61" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton61">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/17.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Peter Pants</h5>
                                                            <p class="mb-0">8  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton62" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton62">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/18.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Polly Tech</h5>
                                                            <p class="mb-0">18  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton63" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton63">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/19.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Tara Zona</h5>
                                                            <p class="mb-0">30  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton64" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton64">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/05.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Arty Ficial</h5>
                                                            <p class="mb-0">15  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton65" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton65">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/06.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Bill Emia</h5>
                                                            <p class="mb-0">25  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton66" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton66">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/07.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Bill Yerds</h5>
                                                            <p class="mb-0">9  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton67" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton67">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 col-lg-6 mb-3">
                                                <div class="iq-friendlist-block">
                                                   <div class="d-flex align-items-center justify-content-between">
                                                      <div class="d-flex align-items-center">
                                                         <a href="#">
                                                         <img src="../assets/images/user/08.jpg" alt="profile-img" class="img-fluid">
                                                         </a>
                                                         <div class="friend-info ms-3">
                                                            <h5>Matt Innae</h5>
                                                            <p class="mb-0">19  friends</p>
                                                         </div>
                                                      </div>
                                                      <div class="card-header-toolbar d-flex align-items-center">
                                                         <div class="dropdown">
                                                            <span class="dropdown-toggle btn btn-secondary me-2" id="dropdownMenuButton68" data-bs-toggle="dropdown" aria-expanded="true" role="button">
                                                            <i class="ri-check-line me-1 text-white"></i> Friend
                                                            </span>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton68">
                                                               <a class="dropdown-item" href="#">Get Notification</a>
                                                               <a class="dropdown-item" href="#">Close Friend</a>
                                                               <a class="dropdown-item" href="#">Unfollow</a>
                                                               <a class="dropdown-item" href="#">Unfriend</a>
                                                               <a class="dropdown-item" href="#">Block</a>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="photos" role="tabpanel">
                        <div class="card">
                           <div class="card-body">
                              <h2>Photos</h2>
                              <div class="friend-list-tab mt-2">
                                 <ul class="nav nav-pills d-flex align-items-center justify-content-left friend-list-items p-0 mb-2">
                                    <li>
                                       <a class="nav-link active" data-bs-toggle="pill" href="#pill-photosofyou" data-bs-target="#photosofyou">Photos of You</a>
                                    </li>
                                    <li>
                                       <a class="nav-link" data-bs-toggle="pill" href="#pill-your-photos" data-bs-target="#your-photos">Your Photos</a>
                                    </li>
                                 </ul>
                                 <div class="tab-content">
                                    <div class="tab-pane fade active show" id="photosofyou" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="d-grid gap-2 d-grid-template-1fr-13">
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/../assets/images/page-img/51.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/52.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/53.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/54.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/55.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/56.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/57.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/58.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/59.jpg" class="img-fluid rounded" alt="image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/60.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/61.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/62.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/63.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/64.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/65.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/51.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/52.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/53.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/54.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/55.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/56.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/57.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/58.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/59.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="your-photos" role="tabpanel">
                                       <div class="card-body p-0">
                                          <div class="d-grid gap-2 d-grid-template-1fr-13 ">
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/51.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/52.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/53.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/54.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/55.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/56.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/57.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/58.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/59.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                             <div class="">
                                                <div class="user-images position-relative overflow-hidden">
                                                   <a href="#">
                                                   <img src="../assets/images/page-img/60.jpg" class="img-fluid rounded" alt="Responsive image">
                                                   </a>
                                                   <div class="image-hover-data">
                                                      <div class="product-elements-icon">
                                                         <ul class="d-flex align-items-center m-0 p-0 list-inline">
                                                            <li><a href="#" class="pe-3 text-white"> 60 <i class="ri-thumb-up-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 30 <i class="ri-chat-3-line"></i> </a></li>
                                                            <li><a href="#" class="pe-3 text-white"> 10 <i class="ri-share-forward-line"></i> </a></li>
                                                         </ul>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="image-edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-12 text-center">
                  <img src="../assets/images/page-img/page-load-loader.gif" alt="loader" style="height: 100px;">
               </div>
            </div>
         </div>
      </div>








    <!-- the page footer -->
    <?php require 'partials/footer.php'; ?>

