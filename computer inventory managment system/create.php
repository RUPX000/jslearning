<?php
$servername = "localhost"; 
$username = "root";
$password = ""; 


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "CREATE DATABASE IF NOT EXISTS inventory2008";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}
$conn->select_db('inventory2008');


$table_sql = "CREATE TABLE IF NOT EXISTS inventory2008 (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
)";


if ($conn->query($table_sql) === TRUE) {
    echo "Table 'inventory2008' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}


$conn->close();
?>