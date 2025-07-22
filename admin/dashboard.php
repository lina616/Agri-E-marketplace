<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}
include '../config/db.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Fetch user count
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
// Fetch product count
$productCount = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
// Fetch inquiry count
$inquiryCount = $conn->query("SELECT COUNT(*) as count FROM inquiries")->fetch_assoc()['count'];

// Fetch users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 10");
// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 10");
// Fetch inquiries
$inquiries = $conn->query(
    "SELECT i.*, u.name as buyer_name, p.title as product_title
     FROM inquiries i
     JOIN users u ON i.buyer_id = u.id
     JOIN products p ON i.product_id = p.id
     ORDER BY i.id DESC LIMIT 10"
);
?>

<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <p class="display-6"><?php echo $userCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <p class="display-6"><?php echo $productCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Total Inquiries</h5>
                    <p class="display-6"><?php echo $inquiryCount; ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4>Recent Users</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Registered</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo ucfirst($u['role']); ?></td>
                <td><?php echo $u['id']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h4>Recent Products</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Title</th><th>Farmer ID</th><th>Price</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['title']); ?></td>
                <td><?php echo $p['farmer_id']; ?></td>
                <td><?php echo $p['price']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h4>Recent Inquiries</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Product</th><th>Buyer</th><th>Message</th><th>Time</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($i = $inquiries->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i['id']; ?></td>
                <td><?php echo htmlspecialchars($i['product_title']); ?></td>
                <td><?php echo htmlspecialchars($i['buyer_name']); ?></td>
                <td><?php echo htmlspecialchars($i['message']); ?></td>
                <td><?php echo $i['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
