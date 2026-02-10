<?php
require_once '../config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];
$message = '';


$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    
    $sql = "UPDATE users SET fullname = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $phone, $admin_id);
    
    if($stmt->execute()) {
        $_SESSION['fullname'] = $fullname;
        $message = "Profile updated successfully!";
     
        $admin['fullname'] = $fullname;
        $admin['phone'] = $phone;
    } else {
        $message = "Error updating profile";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Perfume Paradise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h2>Admin Profile</h2>
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
            <h2>Admin Profile</h2>
            
            <?php if($message): ?>
                <div class="<?php echo strpos($message, 'successfully') ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="profile-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?php echo $admin['username']; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo $admin['email']; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="fullname" value="<?php echo $admin['fullname']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $admin['phone']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="Administrator" readonly>
                </div>
                
                <div class="form-group">
                    <label>Account Created</label>
                    <input type="text" value="<?php echo date('M d, Y', strtotime($admin['created_at'])); ?>" readonly>
                </div>
                
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </main>
    </div>
</body>
</html>
