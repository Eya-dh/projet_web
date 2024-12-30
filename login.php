<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];
      header('location:moncompt.php');
   }else{
      $message[] = 'incorrect password or email!';
   }

}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login </title>
  <link rel="stylesheet" href="loginstyle.css">
</head>
<body>
 
 <div class="wrapper">
    <form action="" method="POST">
      <h2>Login</h2>
      
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>

      <div class="input-field">
        <input type="email" name="email" class="box" required>
        <label>Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" class="box" required>
        <label>Enter your password</label>
      </div>
      
      <input type="submit" name="submit" class="btn" value="login now">
      <div class="register">
        <p>Don't have an account? <a href="register.php">Register Now</a></p>
      </div>
    </form>
  </div>

</body>
</html>
   
