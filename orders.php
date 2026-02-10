<?php
require_once '../config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}


if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $notes = isset($_GET['notes']) ? $_GET['notes'] : '';
    
    if($action == 'approve' || $action == 'reject') {
        $sql = "UPDATE orders SET status=?, admin_notes=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $action, $notes, $id);
        $stmt->execute();
        
        
        if($action == 'approve') {
          
            $order_sql = "SELECT * FROM orders WHERE id = ?";
            $order_stmt = $conn->prepare($order_sql);
            $order_stmt->bind_param("i", $id);
            $order_stmt->execute();
            $order = $order_stmt->get_result()->fetch_assoc();
            
           
            $update_sql = "UPDATE perfumes SET quantity = quantity - ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $order['quantity'], $order['perfume_id']);
            $update_stmt->execute();
        }
        
        header('Location: orders.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Perfume Paradise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h2>Manage Orders</h2>
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
            <h2>All Orders</h2>
            
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Perfume</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT o.*, u.username, p.name as perfume_name 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            JOIN perfumes p ON o.perfume_id = p.id 
                            ORDER BY o.order_date DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['perfume_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo $row['total_price']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['order_date'])); ?></td>
                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <button onclick="approveOrder(<?php echo $row['id']; ?>)">Approve</button>
                                <button onclick="rejectOrder(<?php echo $row['id']; ?>)">Reject</button>
                            <?php else: ?>
                                <span>Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="8">No orders found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
    function approveOrder(id) {
        let notes = prompt("Enter approval notes (optional):");
        window.location.href = `orders.php?action=approve&id=${id}&notes=${encodeURIComponent(notes || '')}`;
    }
    
    function rejectOrder(id) {
        let notes = prompt("Enter reason for rejection:");
        if(notes) {
            window.location.href = `orders.php?action=reject&id=${id}&notes=${encodeURIComponent(notes)}`;
        }
    }
    </script>
</body>
</html>
