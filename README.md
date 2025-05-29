# Product Catalog Application

This is a simple web application for managing a product catalog. It allows users to view, filter, sort, and add products to an online catalog.

## Features

* **Product Listing:** Displays a grid of products with images, names, descriptions, prices, categories, and stock information[cite: 2].
* **Filtering by Category:** Users can filter products by selecting a specific category from a dropdown menu. An "All Categories" option is available to view all products[cite: 2].
* **Sorting by Price:** Products can be sorted by price in ascending (Low to High) or descending (High to Low) order. This sorting can be combined with category filtering[cite: 2, 3].
* **Add New Product:** A dedicated page (`add_product.php`) allows administrators to add new products to the catalog with fields for name, description, price, stock, category, and an optional image URL[cite: 3, 4].
* **Form Validation:** Basic server-side validation is implemented for the add product form[cite: 4].
* **Responsive Design:** The product display adapts to different screen sizes[cite: 2].

## Technologies Used

* **Backend:** PHP
* **Database:** MySQL
* **Frontend:** HTML, CSS

## Database Structure

The application uses a MySQL database with the following tables:

### `categories` table [cite: 4]
* `id`: INT (Primary Key, Auto-Increment)
* `nom`: VARCHAR(50) (Category Name)
* `description`: TEXT (Category Description)

### `produits` table [cite: 4]
* `id`: INT (Primary Key, Auto-Increment)
* `nom`: VARCHAR(100) (Product Name)
* `description`: TEXT (Product Description)
* `prix`: DECIMAL(10,2) (Product Price)
* `stock`: INT (Available Stock)
* `id_categorie`: INT (Foreign Key referencing `categories.id`)
* `image`: VARCHAR(255) (Image URL, optional)
* `date_ajout`: TIMESTAMP (Automatically set on product addition)

## Setup Instructions

1.  **Database Setup:**
    * Create a MySQL database named `product_catalog`.
    * Execute the following SQL commands to create the tables:

        ```sql
        CREATE TABLE categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(50) NOT NULL,
            description TEXT
        );

        CREATE TABLE produits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            prix DECIMAL(10,2) NOT NULL,
            stock INT NOT NULL,
            id_categorie INT NOT NULL,
            image VARCHAR(255),
            date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_categorie) REFERENCES categories(id)
        );

        -- Optional: Insert some initial data
        INSERT INTO categories (nom, description) VALUES
        ('Electronics', 'Electronic gadgets and devices'),
        ('Books', 'Various books and literature'),
        ('Home & Garden', 'Products for home improvement and gardening');

        INSERT INTO produits (nom, description, prix, stock, id_categorie, image) VALUES
        ('Smartphone X', 'Latest model smartphone with advanced features.', 699.99, 50, 1, '[https://example.com/images/phone_x.jpg](https://example.com/images/phone_x.jpg)'),
        ('The Great Novel', 'A captivating story of adventure and discovery.', 15.00, 120, 2, '[https://example.com/images/novel.jpg](https://example.com/images/novel.jpg)'),
        ('Smart Speaker Pro', 'Voice-controlled smart speaker with premium sound.', 129.99, 30, 1, '[https://example.com/images/speaker_pro.jpg](https://example.com/images/speaker_pro.jpg)'),
        ('Gardening Tool Set', 'Essential tools for every gardener.', 45.50, 80, 3, '[https://example.com/images/garden_tools.jpg](https://example.com/images/garden_tools.jpg)');
        ```

2.  **Project Files:**
    * Place `index.php`, `add_product.php`, and `style.css` in your web server's document root (e.g., `htdocs` for Apache/XAMPP or `www` for Nginx).

3.  **Database Connection (PHP):**
    * Open `index.php` and `add_product.php`.
    * Update the database connection details at the top of each file:
        ```php
        $servername = "localhost";
        $username = "root"; // Replace with your MySQL username
        $password = ""; // Replace with your MySQL password
        $dbname = "product_catalog";
        ```

4.  **Default Image:**
    * If you don't provide an `image` URL for a product, the application will attempt to display `default_image.jpg`. Make sure you have a `default_image.jpg` file in the same directory as your PHP scripts, or update the path accordingly.

## How to Run

1.  Start your web server (e.g., Apache/Nginx) and MySQL server.
2.  Open your web browser and navigate to `http://localhost/index.php` (or your project's URL) to view the product catalog.
3.  Click the "Add New Product" button on the `index.php` page, or directly navigate to `http://localhost/add_product.php` to add new products.

## Project Structure
.
├── index.php           # Product Catalog display, filter, and sort
├── add_product.php     # Form for adding new products
└── style.css           # Styling for the application