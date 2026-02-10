<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD PERFUMES TECH - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <h1>MD PERFUMES TECH</h1>
                <span class="tagline">Luxury Fragrances</span>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['role'] == 'admin'): ?>
                            <li><a href="admin/dashboard.php">Admin Panel</a></li>
                        <?php else: ?>
                            <li><a href="user/dashboard.php">My Account</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo $_SESSION['role'] == 'admin' ? 'admin/logout.php' : 'user/logout.php'; ?>" class="btn-logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php" class="btn-register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero" style="background-image: url('assets/images/perfume.webp'); background-size: cover;">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Discover Your Signature Scent</h2>
            <p>Luxury perfumes from around the world</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="hero-btn">Create Account</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="perfumes">
        <h2>Featured Perfumes</h2>
        <div class="perfume-grid">
            <?php
            $sql = "SELECT * FROM perfumes ORDER BY created_at DESC LIMIT 6";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $image = !empty($row['image']) ? "uploads/perfumes/" . $row['image'] : "assets/images/default.jpg";
            ?>
            <div class="perfume-card">
                <img src="<?php echo $image; ?>" alt="<?php echo $row['name']; ?>" class="perfume-img">
                <h3><?php echo $row['name']; ?></h3>
                <p class="brand"><?php echo $row['brand']; ?></p>
                <p class="price">Tzs<?php echo $row['price']; ?></p>
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'user'): ?>
                    <a href="user/dashboard.php?order=<?php echo $row['id']; ?>" class="btn-order">Order Now</a>
                <?php elseif(!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn-login">Login to Order</a>
                <?php endif; ?>
            </div>
            <?php
                }
            } else {
                echo "<p>No perfumes available</p>";
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <p>&copy; 2026 MD PERFUMES TECH. All rights reserved.</p>
            <p>Developed By MR MD</p>
            <p>Email: mohamedomar29@gmil.com | Phone: +255 677899593</p>
        </div>
    </footer>
</body>
</html>
