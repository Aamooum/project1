<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "product_catalog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addProduct($data) {
        $sql = "INSERT INTO produits (nom, description, prix, stock, id_categorie, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdiss", $data['nom'], $data['description'], $data['prix'], $data['stock'], $data['id_categorie'], $data['image']);
        return $stmt->execute();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

$product = new Product($conn);
$categories = $product->getAllCategories();

$error = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation
    if (empty($_POST["nom"])) {
        $error["nom"] = "Name is required";
    }
    if (empty($_POST["description"])) {
        $error["description"] = "Description is required";
    }
    if (empty($_POST["prix"])) {
        $error["prix"] = "Price is required";
    }
    if (empty($_POST["stock"])) {
        $error["stock"] = "Stock is required";
    }
    if (empty($_POST["id_categorie"])) {
        $error["id_categorie"] = "Category is required";
    }

    if (empty($error)) {
        $data = [
            'nom' => $_POST['nom'],
            'description' => $_POST['description'],
            'prix' => $_POST['prix'],
            'stock' => $_POST['stock'],
            'id_categorie' => $_POST['id_categorie'],
            'image' => $_POST['image']
        ];

        if ($product->addProduct($data)) {
            header("Location: index.php"); 
            exit();
        } else {
            echo "Error adding product.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Add New Product</h1>

    <form action="" method="post">
        <label for="nom">Product Name:</label>
        <input type="text" id="nom" name="nom" required>
        <?php if (isset($error["nom"])) echo "<p class='error'>" . $error["nom"] . "</p>"; ?>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <?php if (isset($error["description"])) echo "<p class='error'>" . $error["description"] . "</p>"; ?>

        <label for="prix">Price:</label>
        <input type="number" id="prix" name="prix" step="0.01" required>
        <?php if (isset($error["prix"])) echo "<p class='error'>" . $error["prix"] . "</p>"; ?>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>
        <?php if (isset($error["stock"])) echo "<p class='error'>" . $error["stock"] . "</p>"; ?>

        <label for="id_categorie">Category:</label>
        <select name="id_categorie" id="id_categorie" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>">
                    <?php echo $cat['nom']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($error["id_categorie"])) echo "<p class='error'>" . $error["id_categorie"] . "</p>"; ?>

        <label for="image">Image URL:</label>
        <input type="text" id="image" name="image">

        <input type="submit" value="Add Product">
        <a href="index.php" style="display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; transition: background-color 0.3s ease;">Back to Catalog</a>
    </form>

</body>

</html>
