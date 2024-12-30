<?php
include 'config.php';
if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');
   if(mysqli_num_rows($select) > 0){
      $message[] = 'user already exist!';
   }else{
      mysqli_query($conn, "INSERT INTO `user_form`(name, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
      $message[] = 'registered successfully!';
      header('location:login.php');
   }
}

?>
<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
    <link rel="stylesheet" href="registerstyle.css" />
  </head>
  
  <body>
  <?php
    if(isset($_SESSION['popup'])){
      echo '<div class="popup-message">
              <p>' . $_SESSION['popup'] . '</p>
              <a href="login.php" class="btn">Login Now</a>
            </div>';
      unset($_SESSION['popup']);  // Clear the session message after displaying it
    }

    if(isset($message)){
      foreach($message as $message){
          echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
      }
    }
    ?>
    <section class="container">
      <header>Registration Form</header>
      <form action="register.php" class="form" method="post">
        <div class="input-box">
          <label>Full Name</label>
          <input type="text" name="name" placeholder="Enter full name" required class="box"/>
        </div>

        <div class="input-box">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="Enter email address" required class="box"/>
        </div>

        <div class="column">
            <div class="input-box">
            <label>Password</label>
            <input type="password" name="password" placeholder="Ente your password" maxlength="8" minlength="3" required class="box" />
          </div>
          <div class="input-box">
            <label>Phone Number</label>
            <input type="tel" placeholder="Enter phone number" required class="box" />
          </div>
        </div>
       
        
        <!--<button>Submit</button>-->
        <input type="submit" name="submit" class="btn" value="register now">
        <p>already have an account? <a href="login.php">login now</a></p>

      </form>
    </section>
  </body>
</html>
<body>
