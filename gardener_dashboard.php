<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'gardener') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$gardener_id = $_SESSION['user_id']; // For gardeners user_id stored is actually gardener_id

// Handle Status Update
if(isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action']; // accept/reject/complete
    $service_id = $_GET['id'];
    
    $new_status = '';
    if($action == 'accept') $new_status = 'accepted';
    if($action == 'reject') $new_status = 'cancelled'; // Or rejected
    if($action == 'complete') $new_status = 'completed';
    
    if($new_status) {
        $upd = $pdo->prepare("UPDATE services SET service_status = :status WHERE service_id = :id AND gardener_id = :gid");
        $upd->bindParam(':status', $new_status);
        $upd->bindParam(':id', $service_id);
        $upd->bindParam(':gid', $gardener_id);
        $upd->execute();
        echo "<script>window.location.href='gardener_dashboard.php';</script>";
        exit;
    }
}

// Get Assigned Services
$stmt = $pdo->prepare("SELECT s.*, u.name as user_name, u.email as user_email FROM services s JOIN users u ON s.user_id = u.user_id WHERE s.gardener_id = :gid ORDER BY s.service_date ASC");
$stmt->bindParam(':gid', $gardener_id);
$stmt->execute();
$assignments = $stmt->fetchAll();
?>

<div class="container section-padding">
    <h2>Gardener Dashboard</h2>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>. Here are your assignments.</p>
    
    <div class="auth-box" style="width: 100%; text-align: left; margin-top: 30px;">
        <h3 style="margin-bottom: 20px;">Assigned Services</h3>
        <?php if(count($assignments) > 0): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9f9f9;">
                        <th style="padding: 10px;">ID</th>
                        <th style="padding: 10px;">Customer</th>
                        <th style="padding: 10px;">Service</th>
                        <th style="padding: 10px;">Date</th>
                        <th style="padding: 10px;">Status</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($assignments as $job): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">#<?php echo $job['service_id']; ?></td>
                            <td style="padding: 10px;">
                                <?php echo htmlspecialchars($job['user_name']); ?><br>
                                <small><?php echo htmlspecialchars($job['user_email']); ?></small>
                            </td>
                            <td style="padding: 10px;"><?php echo ucfirst($job['service_type']); ?></td>
                            <td style="padding: 10px;"><?php echo date('d M Y', strtotime($job['service_date'])); ?></td>
                            <td style="padding: 10px;">
                                <span style="font-weight: bold; color: var(--primary-color);">
                                    <?php echo ucfirst($job['service_status']); ?>
                                </span>
                            </td>
                            <td style="padding: 10px;">
                                <?php if($job['service_status'] == 'pending'): ?>
                                    <a href="gardener_dashboard.php?action=accept&id=<?php echo $job['service_id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Accept</a>
                                    <a href="gardener_dashboard.php?action=reject&id=<?php echo $job['service_id']; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem; border-color: red; color: red;">Reject</a>
                                <?php elseif($job['service_status'] == 'accepted'): ?>
                                    <a href="gardener_dashboard.php?action=complete&id=<?php echo $job['service_id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Mark Done</a>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No active assignments.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
