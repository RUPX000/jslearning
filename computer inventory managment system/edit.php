<?php
include 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    echo "Invalid request. No item ID provided.";
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM inventory2008 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "Item not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    if ($image != "") {
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
            $sql = "UPDATE inventory2008 SET item_name = ?, category = ?, quantity = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssidsi", $item_name, $category, $quantity, $price, $target_file, $id);
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        $sql = "UPDATE inventory2008 SET item_name = ?, category = ?, quantity = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssidi", $item_name, $category, $quantity, $price, $id);
    }

    if ($stmt->execute()) {
        header('Location: mainpage.php');
        exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .product-image {
            width: 100px;
            height: auto;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.1);
        }
        .no-image {
            font-style: italic;
            color: #888;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Inventory Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Item</h2>
  
    <?php if (isset($row)): ?>
        <div class="card p-4">
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
                        <p>Current Image: <img src="<?= htmlspecialchars($row['image']) ?>" alt="Current Image" class="product-image"></p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Item</button>
            </form>
        </div>
    <?php else: ?>
        <p>No item found to edit.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>