<?php
include 'db.php';

ini_set('display_errors', 1);  // Enable error reporting for debugging
error_reporting(E_ALL);         // Show all errors

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the item to edit
    $sql = "SELECT * FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind the parameter to avoid SQL injection
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // If no item found, redirect to the inventory page with an error message
    if (!$row) {
        echo "Item not found!";
        exit; // Exit the script if item not found
    }
} else {
    echo "Invalid request. No item ID provided.";
    exit; // Exit the script if no ID is provided in the URL
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated data from the form
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Handle image upload if a new image is selected
    if ($image != "") {
        // Check if the uploaded file is an image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            die("File is not an image.");
        }

        // Check file size (5MB limit)
        if ($_FILES['image']['size'] > 5000000) {
            die("Sorry, your file is too large.");
        }

        // Allow only certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            die("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Try uploading the file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "The file has been uploaded.";
            $sql = "UPDATE inventory SET 
                    item_name = ?, 
                    category = ?, 
                    quantity = ?, 
                    price = ?, 
                    image = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssidsi", $item_name, $category, $quantity, $price, $target_file, $id);
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit; // Exit the script if file upload fails
        }
    } else {
        // If no new image is selected, just update the other details
        $sql = "UPDATE inventory SET 
                item_name = ?, 
                category = ?, 
                quantity = ?, 
                price = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssidi", $item_name, $category, $quantity, $price, $id);
    }

    if ($stmt->execute()) {
        echo "Item updated successfully.";
        header('Location: mainpage.php'); // Redirect to the main inventory page
        exit(); // Ensure script stops after redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Item</h2>
    <!-- Check if $row is set, if not, exit the script or show an error -->
    <?php if (isset($row)): ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="item_name">Item Name:</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?= htmlspecialchars($row['item_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($row['category']) ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($row['quantity']) ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($row['price']) ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Product Image (Leave blank if no change):</label>
                <input type="file" class="form-control-file" id="image" name="image">
                <?php if ($row['image']): ?>
                    <p>Current Image: <img src="<?= htmlspecialchars($row['image']) ?>" alt="Current Image" width="100"></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">Update Item</button>
        </form>
    <?php else: ?>
        <p>No item found to edit.</p>
    <?php endif; ?>
</div>

</body>
</html>