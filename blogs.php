<?php include 'includes/header.php'; ?>

<div class="header-bg" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=1000'); background-size: cover; padding: 100px 0; text-align: center; color: white;">
    <h1>Greenly Blogs & News</h1>
    <p>Tips, tricks, and latest updates from the gardening world.</p>
</div>

<div class="container section-padding">
    <div class="grid-sidebar" style="grid-template-columns: 2fr 1fr; gap: 40px;">
        
        <!-- Blog Posts -->
        <div class="blog-list">
            <article class="blog-card horizontal" style="flex-direction: column;">
                <img src="https://images.unsplash.com/photo-1591143834372-e1cbfa6017b2?q=80&w=800" alt="Blog 1" style="width: 100%; height: 300px;">
                <div class="blog-content">
                    <span style="color: var(--primary-color); font-weight: bold;">Gardening Tips</span>
                    <h2 style="margin: 10px 0;">5 Essential Tips for Indoor Plant Care</h2>
                    <p style="margin-bottom: 20px;">Indoor plants can be tricky. Learn the secrets to keeping them lush and green all year round. From lighting to watering schedules, we cover it all...</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </article>

            <article class="blog-card horizontal" style="flex-direction: column;">
                <img src="https://images.unsplash.com/photo-1416879855530-63131c4027da?q=80&w=800" alt="Blog 2" style="width: 100%; height: 300px;">
                <div class="blog-content">
                    <span style="color: var(--primary-color); font-weight: bold;">Seasonal</span>
                    <h2 style="margin: 10px 0;">Preparing Your Garden for Winter</h2>
                    <p style="margin-bottom: 20px;">As the temperature drops, your garden needs special attention. Here is a checklist to ensure your plants survive the cold...</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </article>

            <article class="blog-card horizontal" style="flex-direction: column;">
                <img src="https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?q=80&w=800" alt="Blog 3" style="width: 100%; height: 300px;">
                <div class="blog-content">
                    <span style="color: var(--primary-color); font-weight: bold;">News</span>
                    <h2 style="margin: 10px 0;">Greenly Wins 'Best Startup 2025' Award</h2>
                    <p style="margin-bottom: 20px;">We are proud to announce that Greenly has been recognized as the best emerging startup in the Agritech space...</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="widget">
                <h3>Categories</h3>
                <ul class="tips-list">
                    <li><a href="#">Gardening Tips</a></li>
                    <li><a href="#">Plant Care</a></li>
                    <li><a href="#">Landscape Design</a></li>
                    <li><a href="#">News & Awards</a></li>
                </ul>
            </div>

            <div class="widget">
                <h3>Subscribe to Newsletter</h3>
                <form>
                    <input type="email" placeholder="Your Email" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <button type="button" class="btn btn-primary" style="width: 100%;">Subscribe</button>
                </form>
            </div>
        </aside>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
