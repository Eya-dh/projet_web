<?php
include('config.php'); // Include your database connection

// Fetch products from the database
$query = "SELECT * FROM products"; // Assuming 'products' is your table name
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phoneshop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <section id="Home">
        <nav>
            <div class="logo">
                <img src="image/logo.jpg">
            </div>

            <ul>
                <li><a href="#Home">Home</a></li>
                <li><a href="#About">About us</a></li>
                <li><a href="#Product">product</a></li>
            </ul>

        

            <div class="icon">
                <form method="GET" action="search.php">
                    <input type="text" name="search" placeholder="Search products..."  required>
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>

                
            </div>
            <div class="login-btn">
                <a href="login.php" class="login">Login</a>
            </div>

        </nav>

        <div class="main">

            <div class="men_text">
                <h1>Phone<span>Shop</span><br>in a Easy Way</h1>
            </div>

            <div class="main_image">
                <img src="image/fond.png">
            </div>

        </div>
        <br><br><br>
        <p>
            Welcome to PhoneShop, your one-stop destination for premium smartphones. Explore a wide range of brands 
            including iPhone, Samsung, Xiaomi, Huawei, and Oppo. 
            Discover the perfect phone for your needs with the 
            latest features and cutting-edge technology. </p><br><br> 

        <div class="main_btn">
            <a href="register.php">Buy Now</a>
            <i class="fa-solid fa-angle-right"></i>
        </div>

    </section>

    
        <!--About-->

    <div class="about" id="About">
        <div class="about_main">

            <div class="image">
                <img src="image/about1.png">
            </div>

            <div class="about_text">
                <h1><span>About</span>Us</h1>
                <h3>Why Choose us?</h3>
                <b><p>
                At PhoneShop, we are passionate about providing our customers with the best smartphones at the best prices. 
                We believe that shopping for a phone should be easy, fast, and enjoyable. 
                That's why we offer a wide selection of the latest models from trusted brands like iPhone, Samsung, Xiaomi, Huawei, and Oppo, all in one place.
                Whether you're looking for performance, design, or camera quality, we have something for everyone. Our customer-first approach ensures that every shopping experience is seamless and satisfying. 
                Choose PhoneShop today for quality, convenience, and unbeatable deals!

                </b> </p>
            </div>

        </div>
    
            
    
    </div>
    


    <!-------------------------------------------------------------------------------Product-------------------------------------->


    <!-- Product Section -->
    <div class="menu" id="Product">
        <h1>Our<span>Products</span></h1>

        <div class="menu_box">
            <?php
            // Loop through the products and display each one
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="menu_card">
                    <div class="menu_image">
                        <a href="details.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"> <!-- Dynamically set image -->
                        </a>
                    </div>
                    <div class="small_card">
                    </div>
                    <div class="menu_info">
                        <h4><?php echo $row['name']; ?></h4> <!-- Display product name -->
                        <h3>$<?php echo $row['price']; ?></h3> <!-- Display product price -->
                        <a href="index.php" class="menu_btn">Buy Now</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>



    <!--Footer-->

    <footer>
        <div class="footer_main">

            <div class="footer_tag">
                <h2>Quick Link</h2>
                <p>Home</p>
                <p>About US</p>
                <p>product</p>
                <p>login</p>
                <p>signup</p>
            </div>

            <div class="footer_tag">
                <h2>Contact</h2>
                <p>+99 12 3456 789</p>
                <p>eyadhouib6@gmail.com</p>
                <p>eyadhouib361@gmail.com</p>
            </div>

            <div class="footer_tag">
                <h2>Our Service</h2>
                <p>Fast Delivery</p>
                <p>Easy Payments</p>
                <p>24 x 7 Service</p>
            </div>

            <div class="footer_tag">
                <h2>Follows</h2>
                <i class="fa-brands fa-facebook-f"></i>
                <i class="fa-brands fa-twitter"></i>
                <i class="fa-brands fa-instagram"></i>
                <i class="fa-brands fa-linkedin-in"></i>
            </div>

        </div>

    </footer>


    

    
</body>
</html>
<?php
    // Close the database connection
    mysqli_close($conn);
?>
