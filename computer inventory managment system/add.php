<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

   
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/"; 
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        die("File is not an image.");
    }


    if ($_FILES['image']['size'] > 5000000) {
        die("Sorry, your file is too large.");
    }

 
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        die("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
       
        $sql = "INSERT INTO inventory2008 (item_name, category, quantity, price, image) 
                VALUES ('$item_name', '$category', '$quantity', '$price', '$target_file')";

        if ($conn->query($sql) === TRUE) {
            echo "New item added successfully";
            header('Location: mainpage.php'); 
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Add New Item</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="item_name">Item Name:</label>
            <input type="text" class="form-control" id="item_name" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" class="form-control" id="category" name="category" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="image">Product Image:</label>
            <input type="file" class="form-control-file" id="image" name="image" required>
        </div>
        <button type="submit" class="btn btn-success">Add Item</button>
    </form>
</div>

</body>
</html>
