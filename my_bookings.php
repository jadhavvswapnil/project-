<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle cancel service
if (isset($_GET['cancel'])) {
    $service_id = intval($_GET['cancel']);
    $update = $pdo->prepare("UPDATE services SET service_status = 'cancelled' WHERE service_id = :sid AND user_id = :uid");
    $update->execute([':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert warning"><i class="fa-solid fa-ban"></i> Service cancelled!</div>';
}

// Handle complete service
if (isset($_GET['complete'])) {
    $service_id = intval($_GET['complete']);
    $update = $pdo->prepare("UPDATE services SET service_status = 'completed' WHERE service_id = :sid AND user_id = :uid");
    $update->execute([':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert success"><i class="fa-solid fa-check"></i> Service marked as completed!</div>';
}

// Handle delete service
if (isset($_GET['delete'])) {
    $service_id = intval($_GET['delete']);
    $del = $pdo->prepare("DELETE FROM services WHERE service_id = :sid AND user_id = :uid");
    $del->execute([':sid' => $service_id, ':uid' => $user_id]);
    $message = '<div class="alert success"><i class="fa-solid fa-trash"></i> Service deleted from history!</div>';
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $service_id = intval($_POST['service_id']);
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback']);
    
    // Add feedback to feedback table
    $insert = $pdo->prepare("INSERT INTO feedback (user_id, rating, comment) VALUES (:uid, :rating, :comment)");
    $insert->execute([':uid' => $user_id, ':rating' => $rating, ':comment' => $feedback]);
    
    // Update service as completed
    $update = $pdo->prepare("UPDATE services SET service_status = 'completed' WHERE service_id = :sid AND user_id = :uid");
    $update->execute([':sid' => $service_id, ':uid' => $user_id]);
    
    $message = '<div class="alert success"><i class="fa-solid fa-star"></i> Thank you for your feedback!</div>';
}

// Fetch services (bookings) - using correct table and column names
$stmt = $pdo->prepare("SELECT s.*, g.name as gardener_name, g.experience FROM services s LEFT JOIN gardeners g ON s.gardener_id = g.gardener_id WHERE s.user_id = :uid ORDER BY s.created_at DESC");
$stmt->execute([':uid' => $user_id]);
$services = $stmt->fetchAll();
?>

<div class="container section-padding">
    <div class="page-header">
        <h2><i class="fa-solid fa-calendar-check"></i> My Service Bookings</h2>
        <a href="services.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Book New Service</a>
    </div>
    
    <?php echo $message; ?>
    
    <?php if(count($services) > 0): ?>
        <div class="bookings-list">
            <?php foreach($services as $service): 
                $status = $service['service_status'];
                $status_class = '';
                switch($status) {
                    case 'completed': $status_class = 'status-success'; break;
                    case 'cancelled': $status_class = 'status-danger'; break;
                    case 'confirmed': $status_class = 'status-info'; break;
                    default: $status_class = 'status-warning';
                }
            ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div>
                            <span class="booking-id">#SVC<?php echo str_pad($service['service_id'], 4, '0', STR_PAD_LEFT); ?></span>
                            <span class="booking-date"><?php echo date('d M Y', strtotime($service['service_date'])); ?></span>
                        </div>
                        <span class="booking-status <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-row">
                            <i class="fa-solid fa-tools"></i>
                            <div>
                                <strong>Service Type</strong>
                                <span><?php echo ucfirst(htmlspecialchars($service['service_type'])); ?></span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <i class="fa-solid fa-user-tie"></i>
                            <div>
                                <strong>Gardener</strong>
                                <span><?php echo $service['gardener_name'] ? htmlspecialchars($service['gardener_name']) : 'Pending Assignment'; ?></span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <i class="fa-solid fa-clock"></i>
                            <div>
                                <strong>Booked On</strong>
                                <span><?php echo date('d M Y, h:i A', strtotime($service['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="booking-footer">
                        <div class="booking-actions">
                            <a href="invoice.php?type=service&id=<?php echo $service['service_id']; ?>" class="btn btn-invoice btn-sm" target="_blank">
                                <i class="fa-solid fa-file-invoice"></i> Invoice
                            </a>
                            <?php if($status == 'pending' || $status == 'confirmed'): ?>
                                <a href="my_bookings.php?cancel=<?php echo $service['service_id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Cancel this booking?')">
                                    <i class="fa-solid fa-ban"></i> Cancel
                                </a>
                                <a href="my_bookings.php?complete=<?php echo $service['service_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Mark as completed?')">
                                    <i class="fa-solid fa-check"></i> Done
                                </a>
                            <?php endif; ?>
                            
                            <?php if($status == 'completed'): ?>
                                <button class="btn btn-feedback btn-sm" onclick="showFeedbackModal(<?php echo $service['service_id']; ?>)">
                                    <i class="fa-solid fa-star"></i> Give Feedback
                                </button>
                            <?php endif; ?>
                            
                            <a href="my_bookings.php?delete=<?php echo $service['service_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this from history?')">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <h3>No bookings yet</h3>
            <p>Book a gardening service to see your bookings here!</p>
            <a href="services.php" class="btn btn-primary">Book a Service</a>
        </div>
    <?php endif; ?>
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" onclick="closeFeedbackModal()">&times;</button>
        <h3><i class="fa-solid fa-star" style="color: #ffc107;"></i> Rate Your Experience</h3>
        <form method="post" action="my_bookings.php">
            <input type="hidden" name="service_id" id="feedback_service_id">
            
            <div class="rating-select">
                <label>Rating</label>
                <div class="star-rating">
                    <input type="radio" name="rating" value="5" id="star5" required><label for="star5"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="4" id="star4"><label for="star4"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="3" id="star3"><label for="star3"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="2" id="star2"><label for="star2"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" name="rating" value="1" id="star1"><label for="star1"><i class="fa-solid fa-star"></i></label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Your Feedback (Optional)</label>
                <textarea name="feedback" rows="4" placeholder="Share your experience..."></textarea>
            </div>
            
            <button type="submit" name="submit_feedback" class="btn btn-primary" style="width: 100%;">Submit Feedback</button>
        </form>
    </div>
</div>

<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.bookings-list { display: flex; flex-direction: column; gap: 20px; }
.booking-card { background: var(--card-bg); border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
.booking-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
.booking-id { font-weight: 700; font-size: 1.1rem; margin-right: 15px; }
.booking-date { color: #666; }
.booking-status { padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.status-success { background: #e8f5e9; color: #2e7d32; }
.status-danger { background: #ffebee; color: #c62828; }
.status-warning { background: #fff3e0; color: #e65100; }
.status-info { background: #e3f2fd; color: #1565c0; }
.booking-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
.detail-row { display: flex; align-items: flex-start; gap: 12px; }
.detail-row i { color: var(--primary-color); font-size: 1.2rem; margin-top: 3px; }
.detail-row strong { display: block; font-size: 0.85rem; color: #666; }
.booking-footer { padding-top: 15px; border-top: 1px solid var(--border-color); }
.booking-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.btn-sm { padding: 8px 15px; font-size: 0.85rem; }
.btn-warning { background: #ff9800; color: white; }
.btn-danger { background: #f44336; color: white; }
.btn-success { background: #4caf50; color: white; }
.btn-feedback { background: #ffc107; color: #333; }
.btn-invoice { background: #2196f3; color: white; }
.alert { padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.alert.success { background: #e8f5e9; color: #2e7d32; }
.alert.warning { background: #fff3e0; color: #e65100; }
.empty-state { text-align: center; padding: 60px 20px; background: var(--card-bg); border-radius: 20px; }
.empty-state i { font-size: 4rem; color: var(--primary-color); margin-bottom: 20px; display: block; }

/* Modal */
.modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999; }
.modal-content { background: var(--card-bg); padding: 30px; border-radius: 20px; width: 90%; max-width: 450px; position: relative; }
.modal-close { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; background: none; border: none; cursor: pointer; color: #666; }
.modal-content h3 { margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; margin: 10px 0 20px; }
.star-rating input { display: none; }
.star-rating label { font-size: 2rem; color: #ddd; cursor: pointer; transition: 0.2s; }
.star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #ffc107; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
.form-group textarea { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 10px; resize: vertical; font-family: inherit; }
</style>

<script>
function showFeedbackModal(serviceId) {
    document.getElementById('feedback_service_id').value = serviceId;
    document.getElementById('feedbackModal').style.display = 'flex';
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').style.display = 'none';
}

document.getElementById('feedbackModal').addEventListener('click', function(e) {
    if (e.target === this) closeFeedbackModal();
});
</script>

<?php include 'includes/footer.php'; ?>
