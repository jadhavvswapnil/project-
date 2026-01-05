<?php include 'includes/header.php'; ?>

<div class="container section-padding">
    <h2 class="section-title">Contact Us</h2>
    <div class="grid-sidebar" style="grid-template-columns: 1fr 1fr; align-items: start;">
        <div>
            <h3>Get in Touch</h3>
            <p style="margin-bottom: 20px;">Have questions? We'd love to hear from you.</p>
            
            <div style="margin-bottom: 20px;">
                <p><strong>Address:</strong><br>123 CILIV LINE , NAGPUR 440016</p>
            </div>
            <div style="margin-bottom: 20px;">
                <p><strong>Phone:</strong><br>+91 72491 64457</p>
            </div>
            <div style="margin-bottom: 20px;">
                <p><strong>Email:</strong><br>help@greenly.com</p>
            </div>
        </div>
        
        <div class="auth-box" style="padding: 30px;">
            <form>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" placeholder="Your Name">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="Your Email">
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea rows="5" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px;" placeholder="How can we help?"></textarea>
                </div>
                <button type="button" class="btn btn-primary" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
