<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle Order Actions
if (isset($_GET['cancel_order'])) {
    $order_id = intval($_GET['cancel_order']);
    $update = $pdo->prepare("UPDATE orders SET payment_status = 'cancelled' WHERE order_id = :oid AND user_id = :uid");
    $update->execute([':oid' => $order_id, ':uid' => $user_id]);
    $message = '<div class="alert warning"><i class="fa-solid fa-ban"></i> Order cancelled successfully!</div>';
}

if (isset($_GET['delete_order'])) {
    $order_id = intval($_GET['delete_order']);
    $del_items = $pdo->prepare("DELETE FROM order_items WHERE order_id = :oid");
    $del_items->execute([':oid' => $order_id]);
    $del_order = $pdo->prepare("DELETE FROM orders WHERE order_id = :oid AND user_id = :uid");
    $del_order->execute([':oid' => $order_id, ':uid' => $user_id]);
    $message = '<div class="alert success"><i class="fa-solid fa-trash"></i> Order deleted from history!</div>';
}

// Handle Service Actions
if (isset($_GET['cancel_service'])) {
    $service_id = intval($_GET['cancel_service']);
    $update = $pdo->prepare("UPDATE services SET service_status = 'cancelled' WHERE service_id = :sid AND user_id = :uid");
    $update->execute([':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert warning"><i class="fa-solid fa-ban"></i> Service cancelled!</div>';
}

if (isset($_GET['complete_service'])) {
    $service_id = intval($_GET['complete_service']);
    $update = $pdo->prepare("UPDATE services SET service_status = 'completed' WHERE service_id = :sid AND user_id = :uid");
    $update->execute([':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert success"><i class="fa-solid fa-check"></i> Service marked as completed!</div>';
}

if (isset($_GET['delete_service'])) {
    $service_id = intval($_GET['delete_service']);
    $del = $pdo->prepare("DELETE FROM services WHERE service_id = :sid AND user_id = :uid");
    $del->execute([':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert success"><i class="fa-solid fa-trash"></i> Service deleted from history!</div>';
}

// Handle Feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $service_id = intval($_POST['service_id']);
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback_text']);
    
    // Save feedback (you can create a separate table or update services)
    $update = $pdo->prepare("UPDATE services SET service_status = 'completed', rating = :rating, feedback = :feedback WHERE service_id = :sid AND user_id = :uid");
    $update->execute([':rating' => $rating, ':feedback' => $feedback, ':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert success"><i class="fa-solid fa-star"></i> Thank you for your feedback!</div>';
}

// Get Orders
$order_stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY order_date DESC");
$order_stmt->bindParam(':uid', $user_id);
$order_stmt->execute();
$orders = $order_stmt->fetchAll();

// Get Services
$service_stmt = $pdo->prepare("SELECT s.*, g.name as gardener_name FROM services s LEFT JOIN gardeners g ON s.gardener_id = g.gardener_id WHERE s.user_id = :uid ORDER BY s.created_at DESC");
$service_stmt->bindParam(':uid', $user_id);
$service_stmt->execute();
$services = $service_stmt->fetchAll();
?>

<div class="container section-padding">
    <h2><i class="fa-solid fa-gauge"></i> My Dashboard</h2>
    <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong></p>

    <?php echo $message; ?>

    <div class="dashboard-grid">
        
        <!-- Orders Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fa-solid fa-box"></i> My Orders</h3>
                <a href="shop.php" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> New Order</a>
            </div>
            <?php if(count($orders) > 0): ?>
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): 
                                $status = $order['payment_status'];
                                $status_class = ($status == 'completed') ? 'status-success' : (($status == 'cancelled') ? 'status-danger' : 'status-warning');
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $order['order_id']; ?></strong></td>
                                    <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                                    <td>₹<?php echo number_format($order['total_amount'], 0); ?></td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span></td>
                                    <td class="actions-cell">
                                        <?php if($status == 'pending' || $status == 'processing'): ?>
                                            <a href="dashboard.php?cancel_order=<?php echo $order['order_id']; ?>" class="action-btn-sm cancel" onclick="return confirm('Cancel this order?')" title="Cancel Order">
                                                <i class="fa-solid fa-ban"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="dashboard.php?delete_order=<?php echo $order['order_id']; ?>" class="action-btn-sm delete" onclick="return confirm('Delete this order from history?')" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-msg">
                    <i class="fa-solid fa-box-open"></i>
                    <p>No orders yet. <a href="shop.php">Start shopping!</a></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Services Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fa-solid fa-calendar-check"></i> My Service Requests</h3>
                <a href="services.php" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> Book Service</a>
            </div>
            <?php if(count($services) > 0): ?>
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Gardener</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($services as $service): 
                                $s_status = $service['service_status'];
                                $s_status_class = ($s_status == 'completed') ? 'status-success' : (($s_status == 'cancelled') ? 'status-danger' : 'status-warning');
                            ?>
                                <tr>
                                    <td><strong><?php echo ucfirst($service['service_type']); ?></strong></td>
                                    <td><?php echo date('d M Y', strtotime($service['service_date'])); ?></td>
                                    <td>
                                        <?php echo $service['gardener_name'] ? htmlspecialchars($service['gardener_name']) : '<span class="text-muted">Pending</span>'; ?>
                                    </td>
                                    <td><span class="status-badge <?php echo $s_status_class; ?>"><?php echo ucfirst($s_status); ?></span></td>
                                    <td class="actions-cell">
                                        <?php if($s_status == 'pending' || $s_status == 'confirmed'): ?>
                                            <a href="dashboard.php?cancel_service=<?php echo $service['service_id']; ?>" class="action-btn-sm cancel" onclick="return confirm('Cancel this service?')" title="Cancel">
                                                <i class="fa-solid fa-ban"></i>
                                            </a>
                                            <a href="dashboard.php?complete_service=<?php echo $service['service_id']; ?>" class="action-btn-sm complete" onclick="return confirm('Mark as completed?')" title="Mark Done">
                                                <i class="fa-solid fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($s_status == 'completed'): ?>
                                            <button class="action-btn-sm feedback" onclick="showFeedback(<?php echo $service['service_id']; ?>)" title="Give Feedback">
                                                <i class="fa-solid fa-star"></i>
                                            </button>
                                        <?php endif; ?>
                                        <a href="dashboard.php?delete_service=<?php echo $service['service_id']; ?>" class="action-btn-sm delete" onclick="return confirm('Delete from history?')" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-msg">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>No services booked. <a href="services.php">Book now!</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeFeedback()">&times;</button>
        <h3><i class="fa-solid fa-star" style="color: #ffc107;"></i> Rate Your Experience</h3>
        <form method="post" action="dashboard.php">
            <input type="hidden" name="service_id" id="feedback_service_id">
            
            <div class="rating-group">
                <label>Your Rating</label>
                <div class="star-rating">
                    <input type="radio" name="rating" value="5" id="r5" required><label for="r5"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="4" id="r4"><label for="r4"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="3" id="r3"><label for="r3"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="2" id="r2"><label for="r2"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="1" id="r1"><label for="r1"><i class="fa-solid fa-star"></i></label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Your Feedback (Optional)</label>
                <textarea name="feedback_text" rows="3" placeholder="Share your experience with the gardener..."></textarea>
            </div>
            
            <button type="submit" name="submit_feedback" class="btn btn-primary" style="width: 100%;">
                <i class="fa-solid fa-paper-plane"></i> Submit Feedback
            </button>
        </form>
    </div>
</div>

<style>
.dashboard-grid {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-top: 30px;
}

.dashboard-card {
    background: var(--card-bg);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
}

.card-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h3 i {
    color: var(--primary-color);
}

.btn-sm {
    padding: 8px 15px;
    font-size: 0.85rem;
}

.table-responsive {
    overflow-x: auto;
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
}

.dashboard-table th,
.dashboard-table td {
    padding: 12px 15px;
    text-align: left;
}

.dashboard-table th {
    background: var(--bg-color);
    font-weight: 600;
    color: #666;
    font-size: 0.85rem;
    text-transform: uppercase;
}

.dashboard-table tr {
    border-bottom: 1px solid var(--border-color);
}

.dashboard-table tr:hover {
    background: rgba(46, 125, 50, 0.05);
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-success { background: #e8f5e9; color: #2e7d32; }
.status-danger { background: #ffebee; color: #c62828; }
.status-warning { background: #fff3e0; color: #e65100; }

.actions-cell {
    display: flex;
    gap: 8px;
}

.action-btn-sm {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.action-btn-sm.cancel { background: #fff3e0; color: #e65100; }
.action-btn-sm.cancel:hover { background: #e65100; color: white; }

.action-btn-sm.complete { background: #e8f5e9; color: #2e7d32; }
.action-btn-sm.complete:hover { background: #2e7d32; color: white; }

.action-btn-sm.feedback { background: #fff8e1; color: #f9a825; }
.action-btn-sm.feedback:hover { background: #f9a825; color: white; }

.action-btn-sm.delete { background: #ffebee; color: #c62828; }
.action-btn-sm.delete:hover { background: #c62828; color: white; }

.text-muted { color: #999; font-style: italic; }

.empty-msg {
    text-align: center;
    padding: 40px;
    color: #666;
}

.empty-msg i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 15px;
    display: block;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert.success { background: #e8f5e9; color: #2e7d32; }
.alert.warning { background: #fff3e0; color: #e65100; }

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.modal-box {
    background: var(--card-bg);
    padding: 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 400px;
    position: relative;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
}

.modal-box h3 {
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.rating-group {
    margin-bottom: 20px;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
    margin-top: 10px;
}

.star-rating input { display: none; }

.star-rating label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: 0.2s;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffc107;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    resize: vertical;
    font-family: inherit;
}
</style>

<script>
function showFeedback(serviceId) {
    document.getElementById('feedback_service_id').value = serviceId;
    document.getElementById('feedbackModal').style.display = 'flex';
}

function closeFeedback() {
    document.getElementById('feedbackModal').style.display = 'none';
}

// Close modal on outside click
document.getElementById('feedbackModal').addEventListener('click', function(e) {
    if (e.target === this) closeFeedback();
});
</script>

<?php include 'includes/footer.php'; ?>
