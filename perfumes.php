<?php
require_once '../config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}


if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM perfumes WHERE id = $id");
    header('Location: perfumes.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Perfumes - Perfume Paradise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h2>Manage Perfumes</h2>
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
            <h2>All Perfumes</h2>
            <a href="add_perfume.php" class="btn">Add New Perfume</a>
            
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM perfumes ORDER BY id DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['brand']; ?></td>
                        <td>Tzs <?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <a href="add_perfume.php?edit=<?php echo $row['id']; ?>">Edit</a>
                            <a href="perfumes.php?delete=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Delete this perfume?')">Delete</a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="6">No perfumes found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
