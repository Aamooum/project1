<?php
// Database Setup (You can execute this SQL separately via phpMyAdmin or a similar tool)
/*
CREATE DATABASE IF NOT EXISTS `product_catalog`;

USE `product_catalog`;

CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(50) NOT NULL,
  `description` TEXT
);

CREATE TABLE `produits` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `prix` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL,
  `id_categorie` INT,
  `image` VARCHAR(255),
  `date_ajout` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_categorie`) REFERENCES `categories`(`id`)
);
*/

// Database Connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "product_catalog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Product Model Class
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllProducts($category_id = null, $sort_by_price = null) {
        $sql = "SELECT p.*, c.nom as category_name FROM produits p LEFT JOIN categories c ON p.id_categorie = c.id";

        if ($category_id) {
            $sql .= " WHERE p.id_categorie = " . $category_id;
        }

        if ($sort_by_price) {
            $sql .= " ORDER BY p.prix " . ($sort_by_price == 'asc' ? 'ASC' : 'DESC');
        }

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

$product = new Product($conn);
$categories = $product->getAllCategories();

$category_id = isset($_GET['category']) ? $_GET['category'] : null;
$sort_by_price = isset($_GET['sort']) ? $_GET['sort'] : null;
$products = $product->getAllProducts($category_id, $sort_by_price);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Product Catalog</h1>

    <form action="" method="get">
        <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo $cat['nom']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="sort" onchange="this.form.submit()">
            <option value="">Sort by Price</option>
            <?php $sort_by_price = isset($_GET['sort']) ? $_GET['sort'] : null; ?>
            <option value="asc" <?php echo ($sort_by_price == 'asc') ? 'selected' : ''; ?>>Price: Low to High</option>
            <option value="desc" <?php echo ($sort_by_price == 'desc') ? 'selected' : ''; ?>>Price: High to Low</option>
        </select>
        <a href="add_product.php" style="display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px; transition: background-color 0.3s ease; ">Add New Product</a>
    </form>

    <div class="product-grid">
        <?php foreach ($products as $prod): ?>
            <div class="product">
                <div class="product-image-container">
                    <img src="<?php echo $prod['image'] ? $prod['image'] : 'default_image.jpg'; ?>" alt="<?php echo $prod['nom']; ?>">
                </div>
                <h2><?php echo $prod['nom']; ?></h2>
                <p class="description"><?php echo substr($prod['description'], 0, 100); ?>...</p>
                <p class="price">Price: $<?php echo $prod['prix']; ?></p>
                <p class="category">Category: <?php echo $prod['category_name']; ?></p>
                <p class="stock">Stock: <?php echo $prod['stock']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>