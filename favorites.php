<?php
// Include database connection
include 'config.php';

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user's ID

// Fetch favorite products of the user
$select_favorites = mysqli_query($conn, "SELECT * FROM `favorites` WHERE user_id = '$user_id'") or die('query failed');

// Handle add to favorites
if (isset($_POST['add_to_favorites'])) {
    $product_id = $_POST['product_id'];
    // Check if product is already in favorites
    $select_favorites_check = mysqli_query($conn, "SELECT * FROM `favorites` WHERE product_id = '$product_id' AND user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($select_favorites_check) > 0) {
        $_SESSION['popup'] = 'exists';
    } else {
        mysqli_query($conn, "INSERT INTO `favorites`(user_id, product_id) VALUES('$user_id', '$product_id')") or die('query failed');
        $_SESSION['popup'] = 'success';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorite Products</title>
    <link rel="stylesheet" href="favoritestyle.css">
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
   <section class="menu">
      <h1><span>Favorites</span></h1>

      <div class="menu_box">
         <?php
            // Check if the user has favorite products
            if (mysqli_num_rows($select_favorites) > 0) {
               while ($fetch_favorite = mysqli_fetch_assoc($select_favorites)) {
                  $product_id = $fetch_favorite['product_id'];

                  // Fetch product details
                  $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('query failed');
                  $fetch_product = mysqli_fetch_assoc($select_product);
         ?>
         <div class="menu_card">
            <div class="menu_image">
               <img src="<?php echo $fetch_product['image']; ?>" alt="<?php echo $fetch_product['name']; ?>">
            </div>
            <div class="menu_info">
               <h4><?php echo $fetch_product['name']; ?></h4>
               <h3>$<?php echo $fetch_product['price']; ?>/-</h3>
               <form method="post" action="remove_favorite.php">
                     <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                     <button type="submit" name="remove_from_favorites" class="menu_btn">Remove</button>
               </form>
            </div>
         </div>
         <?php
            }
         } else {
            echo "<p>No favorite products yet.</p>";
         }
         ?>
      </div>
   </section>

   
</body>
</html>
