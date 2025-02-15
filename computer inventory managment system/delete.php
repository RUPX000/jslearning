<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

  
    $sql = "DELETE FROM inventory2008 WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Item deleted successfully";
        header('Location: mainpage.php'); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
