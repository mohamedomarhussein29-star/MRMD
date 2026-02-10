<?php
require_once '../config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}


$edit_mode = false;
$perfume = null;

if(isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM perfumes WHERE id = $id");
    if($result->num_rows == 1) {
        $perfume = $result->fetch_assoc();
    }
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    
   
    $image_name = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_name = time() . '_' . $image['name'];
        move_uploaded_file($image['tmp_name'], "../uploads/perfumes/" . $image_name);
    }
    
    if($edit_mode && isset($_POST['id'])) {
        $id = $_POST['id'];
        if($image_name) {
            $sql = "UPDATE perfumes SET name=?, brand=?, price=?, quantity=?, description=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdisss", $name, $brand, $price, $quantity, $description, $image_name, $id);
        } else {
            $sql = "UPDATE perfumes SET name=?, brand=?, price=?, quantity=?, description=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdiss", $name, $brand, $price, $quantity, $description, $id);
        }
        $success = "Perfume updated successfully!";
    } else {
        $sql = "INSERT INTO perfumes (name, brand, price, quantity, description, image) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $name, $brand, $price, $quantity, $description, $image_name);
        $success = "Perfume added successfully!";
    }
    
    if($stmt->execute()) {
        $message = $success;
    } else {
        $error = "Failed to save perfume";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_mode ? 'Edit' : 'Add'; ?> Perfume - Perfume Paradise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h2><?php echo $edit_mode ? 'Edit' : 'Add'; ?> Perfume</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="users.php">Manage Users</a></li>
                    <li><a href="perfumes.php">Perfumes</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard">
        <aside class="sidebar">
            <h3>Welcome, <?php echo $_SESSION['fullname']; ?></h3>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="users.php">Manage Users</a></li>
                <li><a href="perfumes.php">Perfumes</a></li>
                <li><a href="add_perfume.php">Add Perfume</a></li>
                <li><a href="orders.php">Manage Orders</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2><?php echo $edit_mode ? 'Edit' : 'Add New'; ?> Perfume</h2>
            
            <?php if(isset($message)): ?>
                <div class="success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data" class="perfume-form">
                <?php if($edit_mode): ?>
                    <input type="hidden" name="id" value="<?php echo $perfume['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Perfume Name *</label>
                    <input type="text" name="name" value="<?php echo $edit_mode ? $perfume['name'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" name="brand" value="<?php echo $edit_mode ? $perfume['brand'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Price *</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_mode ? $perfume['price'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Stock Quantity *</label>
                    <input type="number" name="quantity" value="<?php echo $edit_mode ? $perfume['quantity'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"><?php echo $edit_mode ? $perfume['description'] : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" accept="image/*">
                    <?php if($edit_mode && !empty($perfume['image'])): ?>
                        <p>Current: <?php echo $perfume['image']; ?></p>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn"><?php echo $edit_mode ? 'Update' : 'Add'; ?> Perfume</button>
                <a href="perfumes.php" class="btn-cancel">Cancel</a>
            </form>
        </main>
    </div>
</body>
</html>
