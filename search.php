<?php
include 'config.php'; // Inclut la connexion à la base de données

// Vérifie si une recherche a été soumise
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM products WHERE name LIKE '%$search_query%'";
    $result = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));
} else {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="Searchstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <section id="search">
        <nav>
            <div class="logo">
                <a href="index.php">
                    <img src="image/logo.jpg">
                </a>
            </div>

            <div class="icon">
                <form method="GET" action="search.php">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>" required>
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </nav>

        <div class="menu">
            <h1>Search Results <span>for "<?php echo htmlspecialchars($search_query); ?>"</span></h1>
            <div class="menu_box">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="menu_card">
                            <div class="menu_image">
                                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                            </div>
                            <div class="menu_info">
                                <h4><?php echo $row['name']; ?></h4>
                                <p>$<?php echo $row['price']; ?></p>
                                <a href="register.php" class="menu_btn">Buy Now</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No products found for your search.</p>";
                }
                ?>
            </div>
        </div>
    </section>
</body>
</html>
