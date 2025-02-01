<?php
$servername = "localhost"; // Database host (usually localhost)
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (for local development, it's usually empty)

// Create a connection to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create the database
$sql = "CREATE DATABASE IF NOT EXISTS inventory";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}
$conn->select_db('inventory');

// SQL to create the inventory table
$table_sql = "CREATE TABLE IF NOT EXISTS inventory (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
)";

// Execute the query to create the table
if ($conn->query($table_sql) === TRUE) {
    echo "Table 'inventory' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Optionally, insert some sample data into the table
$insert_sql = "INSERT INTO inventory (item_name, category, quantity, price, image) VALUES
    ('Laptop', 'Electronics', 10, 999.99, 'uploads/laptop.jpg'),
    ('Headphones', 'Electronics', 50, 199.99, 'uploads/headphones.jpg'),
    ('Desk Chair', 'Furniture', 20, 79.99, 'uploads/desk_chair.jpg')";

if ($conn->query($insert_sql) === TRUE) {
    echo "Sample data inserted successfully.<br>";
} else {
    echo "Error inserting data: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>