
<?php
include 'db.php';


$sql = "SELECT * FROM inventory2008"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
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
    <h2 class="text-center mb-4">Inventory Management System</h2>
    <a href="add.php" class="btn btn-custom mb-4"><i class="fas fa-plus"></i> Add Item</a>
   
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['item_name'] ?></h5>
                        <p class="card-text"><strong>Category:</strong> <?= $row['category'] ?></p>
                        <p class="card-text"><strong>Quantity:</strong> <?= $row['quantity'] ?></p>
                        <p class="card-text"><strong>Price:</strong> $<?= $row['price'] ?></p>
                        <div class="text-center">
                            <?php if ($row['image']): ?>
                                <img src="<?= $row['image'] ?>" alt="Product Image" class="product-image mb-3">
                            <?php else: ?>
                                <span class="no-image">No Image</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-center">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Update</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>