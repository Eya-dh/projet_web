<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
   exit();
};

// Si l'utilisateur est déconnecté et que la session est vide, redirigez-le vers la page de connexion.
if (!isset($_SESSION['user_id'])) {
   header('location: index.php');
   exit();
}


// À la déconnexion
if(isset($_GET['logout'])){
   session_unset();  // Libère toutes les variables de session
   session_destroy();  // Détruit la session
   header('location:login.php');
   exit();  // Ajoutez un exit pour éviter que le reste du code s'exécute
}


// Empêcher la mise en cache de la page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");


$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
if(mysqli_num_rows($select_user) > 0){
   $fetch_user = mysqli_fetch_assoc($select_user);
}


// Gestion de l'ajout au panier avec pop-up
if(isset($_POST['add_to_cart'])){
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_cart) > 0){
      $_SESSION['popup'] = 'exists';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $_SESSION['popup'] = 'success';
   }
   header('location:moncompt.php');
   exit();
}


if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'Cart quantity updated successfully!';
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:moncompt.php');
   exit();
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:moncompt.php');
   exit();
}
// Vérifier si l'utilisateur a ajouté un produit aux favoris
if(isset($_POST['add_to_favorites'])){
   $product_id = $_POST['product_id'];
   $select_favorites = mysqli_query($conn, "SELECT * FROM `favorites` WHERE product_id = '$product_id' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_favorites) > 0){
      echo "<script>alert('Product already in favorites!');</script>";
   } else {
      mysqli_query($conn, "INSERT INTO `favorites`(user_id, product_id) VALUES('$user_id', '$product_id')") or die('query failed');
      echo "<script>alert('Product added to favorites!');</script>";
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>PhoneShop - My Account</title>
   <link rel="stylesheet" href="comptstyle.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
   <section id="Home">
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

            <h3><span><?php echo $fetch_user['name']; ?></span></h3>
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

      <div class="container">
         
         <div class="products">
            <h1 class="heading">Our Products</h1>
            <div class="box-container">
               <?php
                  $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                  if(mysqli_num_rows($select_product) > 0){
                     while($fetch_product = mysqli_fetch_assoc($select_product)){
               ?>
               <div class="box">
                  <form method="post" action="">
                     <img src="<?php echo $fetch_product['image']; ?>" alt="">
                     <div class="name"><?php echo $fetch_product['name']; ?></div>
                     <div class="price">$<?php echo $fetch_product['price']; ?></div>
                     <input type="number" min="1" name="product_quantity" value="1">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                     <b><input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>"></b>
                     <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                     <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
                 
                     <!-- Le bouton favoris avec la classe active si déjà dans les favoris -->
                     <button type="submit" name="add_to_favorites" class="favorite-btn <?php echo $favorite_status; ?>">
                        <i class="fa-solid fa-heart"></i>
                     </button>
                     <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                  </form>
               </div>
               <?php
                  };
               };
               ?>
            </div>
         </div>
         

      </div>
   </section>

   <!-- Pop-up HTML -->
   <div id="popup" class="popup" style="display: none;">
      <div class="popup-content">
         <i class="fa-solid fa-check-circle"></i>
         <p>Product added to cart successfully</p>
         <button onclick="closePopup()">OK</button>
      </div>
   </div>

   <!-- JavaScript pour la pop-up -->
   <script>
      window.onload = function() {
         <?php
            if(isset($_SESSION['popup'])){
               if($_SESSION['popup'] == 'success'){
                  echo 'openPopup("Product added to cart successfully!");';
               } else if($_SESSION['popup'] == 'exists'){
                  echo 'openPopup("Product already in the cart!");';
               }
               unset($_SESSION['popup']); // Efface la session après affichage
            }
         ?>
      }

      function openPopup(message) {
         document.querySelector('.popup-content p').innerText = message;
         document.getElementById('popup').style.display = 'flex';
      }
      function closePopup() {
         document.getElementById('popup').style.display = 'none';
      }


   </script>

</body>
</html>
