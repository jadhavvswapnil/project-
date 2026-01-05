<?php
include 'includes/db.php';
include 'includes/header.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Login as User or Gardener

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        if ($role === 'gardener') {
            $sql = "SELECT * FROM gardeners WHERE email = :email";
        } else {
            $sql = "SELECT * FROM users WHERE email = :email";
        }

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            if($stmt->execute()){
                // Fetch directly - rowCount() doesn't work reliably with SQLite for SELECT
                $row = $stmt->fetch();
                if($row){
                    // Verify password
                    if(password_verify($password, $row['password'])){
                        // Password is correct
                        $_SESSION['user_id'] = ($role === 'gardener') ? $row['gardener_id'] : $row['user_id'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['role'] = ($role === 'gardener') ? 'gardener' : $row['role']; 

                         // Redirect
                         if($_SESSION['role'] === 'admin'){
                            echo "<script>window.location.href='admin/dashboard.php';</script>";
                         } else if ($_SESSION['role'] === 'gardener') {
                            echo "<script>window.location.href='gardener_dashboard.php';</script>";
                         } else {
                            echo "<script>window.location.href='index.php';</script>";
                         }
                         exit;
                    } else {
                        $error = "Invalid password.";
                    }
                } else {
                    $error = "No account found with that email.";
                }
            } else {
                $error = "Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
}
?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Login to Greenly</h2>
        <?php if($error): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Login As</label>
                <select name="role">
                    <option value="customer">Customer / Admin</option>
                    <option value="gardener">Gardener</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        <div class="auth-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
