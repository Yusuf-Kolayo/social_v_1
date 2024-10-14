<?php  require '../partials/hd.php';  



  if (count($_POST)>0) {
     if (isset($_POST['post_id'])) {

          // get the number of likes
          $sql_x = "SELECT COUNT(*) as likes FROM likes WHERE post_id =?";
          $stmt_x = mysqli_prepare($connection, $sql_x);
          mysqli_stmt_bind_param($stmt_x, 's', $post_id);
          mysqli_stmt_execute($stmt_x);
          $rs_x = mysqli_stmt_get_result($stmt_x);
          $n_row_x = mysqli_fetch_assoc($rs_x);
          // count the number of likes
          $like_count = $n_row_x['likes'];


        $post_id = (int) $_POST['post_id'];
        $timestamp = time();

        // check if this person has liked this post before
        $sql = "SELECT * FROM likes WHERE user_id=? AND post_id=?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $user_id, $post_id);
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $n_row = mysqli_fetch_assoc($rs);

            if ($n_row>0) {
                // delete all likes of this person
                $sql = "DELETE FROM likes WHERE user_id=? AND post_id=?";
                $stmt = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($stmt, 'ss', $user_id, $post_id);
                mysqli_stmt_execute($stmt);
                $result = [
                    'likes' => $like_count,
                    'status'=>'success'
                ];
            } else {
                // insert in the likes table
                $sql = "INSERT INTO likes (user_id,post_id,timestamp) VALUES(?,?,?)";
                $stmt = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($stmt, 'sss', $user_id,$post_id,$timestamp);
                mysqli_stmt_execute($stmt);
                $row = mysqli_stmt_affected_rows($stmt);

                $result = [
                    'likes' => $like_count+1,
                    'status'=>'success'
                ];
            }

          echo json_encode($result);
     }
  }
