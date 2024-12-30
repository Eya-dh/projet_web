<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:cart.php');
   exit();
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Your Cart</title>
   <link rel="stylesheet" href="cartstyle.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
   <nav>
      <div class="logo">
         <a href="moncompt.php"><img src="image/logo.jpg"></a>
      </div>
      <div class="user-profile">
         <?php
            $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select_user) > 0){
               $fetch_user = mysqli_fetch_assoc($select_user);
            }
         ?>

         <h3> <span><?php echo $fetch_user['name']; ?></span></h3>
      </div>
      
      <div class="icon">
         <form method="GET" action="search.php">
            <input type="text" name="search" placeholder="Search products..." required>
            <button type="submit">
               <i class="fa-solid fa-magnifying-glass"></i>
            </button>
         </form>
         <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
         <a href="favorites.php"><i class="fa-solid fa-heart"></i></a>
         <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
         </a>
      </div>
   </nav>
   <br><br><br><br>
   <section class="shopping-cart">
      <h1>Your Shopping Cart</h1>
      <table>
         <thead>
            <tr>
               <th>Image</th>
               <th>Name</th>
               <th>Price</th>
               <th>Quantity</th>
               <th>Total</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
               $grand_total = 0;
               if(mysqli_num_rows($cart_query) > 0){
                  while($fetch_cart = mysqli_fetch_assoc($cart_query)){
            ?>
            <tr>
               <td><img src="<?php echo $fetch_cart['image']; ?>" height="100"></td>
               <td><?php echo $fetch_cart['name']; ?></td>
               <td>$<?php echo $fetch_cart['price']; ?></td>
               <td><?php echo $fetch_cart['quantity']; ?></td>
               <td>$<?php echo $fetch_cart['price'] * $fetch_cart['quantity']; ?></td>
               <td>
                  <a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" class="remove-btn" onclick="return confirm('Remove item from cart?');">Remove</a>
               </td>
            </tr>
            <?php
                  $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
                  }
               }else{
                  echo '<tr><td colspan="6">No items in cart</td></tr>';
               }
            ?>
         </tbody>
      </table>
      <div class="total">
         <h3>Total: $<?php echo $grand_total; ?></h3>
         <a href="cart.php?delete_all=true" class="clear-btn" onclick="return confirm('Clear all items?');">Clear Cart</a>
      </div>
            </section>
</body>
</html>
