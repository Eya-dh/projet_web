<?php 
// Inclure la configuration
require_once 'config.php';

// Vérifier si l'ID est passé et est un nombre entier
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    echo "ID de produit invalide ou manquant.";
    exit;
}

// Requête préparée pour éviter les injections SQL
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // "i" pour integer
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si le produit existe
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Produit non trouvé.";
    exit;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['name']); ?></title>
    <link rel="stylesheet" href="detail.css">
</head>
<body>
    <div class="product-details">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        <h1><?php echo htmlspecialchars($row['name']); ?></h1>
        <p><b>
            <?php 
                // Remplacer chaque point et chaque virgule par un point ou une virgule suivis d'un retour à la ligne <br>
                $description = htmlspecialchars($row['description']);
                $description = str_replace([","], [ ",<br>"], $description); 
                echo $description; 
            ?>
            </b>
        </p>
        <h3><?php echo htmlspecialchars($row['price']); ?> $</h3>
        <a href="index.php?#Product" class="back-btn">Back</a>
    </div>
</body>
</html>
