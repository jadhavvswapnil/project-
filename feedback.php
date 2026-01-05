<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO feedback (user_id, rating, comment) VALUES (:uid, :rating, :comment)");
    if($stmt->execute([':uid'=>$user_id, ':rating'=>$rating, ':comment'=>$comment])){
        $msg = "Thank you for your feedback!";
    }
}
?>

<div class="container section-padding">
    <div class="auth-box" style="margin: 0 auto;">
        <h2>Rate Your Experience</h2>
        <p style="margin-bottom: 20px;">We value your opinion.</p>
        
        <?php if($msg): ?>
            <div style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Rating</label>
                <div class="rating-input" style="font-size: 1.5rem; color: #ddd; cursor: pointer;">
                    <i class="fa-solid fa-star" data-val="1"></i>
                    <i class="fa-solid fa-star" data-val="2"></i>
                    <i class="fa-solid fa-star" data-val="3"></i>
                    <i class="fa-solid fa-star" data-val="4"></i>
                    <i class="fa-solid fa-star" data-val="5"></i>
                    <input type="hidden" name="rating" id="rating-val" value="5">
                </div>
            </div>
            
            <div class="form-group">
                <label>Comment</label>
                <textarea name="comment" rows="4" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 5px;"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Feedback</button>
        </form>
    </div>
</div>

<script>
// Simple Star Rating Script
const stars = document.querySelectorAll('.rating-input i');
const input = document.getElementById('rating-val');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const val = star.getAttribute('data-val');
        input.value = val;
        updateStars(val);
    });
});

function updateStars(val) {
    stars.forEach(star => {
        if (star.getAttribute('data-val') <= val) {
            star.style.color = '#ffc107';
        } else {
            star.style.color = '#ddd';
        }
    });
}
// Init
updateStars(5);
</script>

<?php include 'includes/footer.php'; ?>
