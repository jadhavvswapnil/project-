<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-text animate-fadeInLeft">
            <span class="hero-badge">🌿 #1 Plant Store in India</span>
            <h1>Shop Plants & <span class="highlight">Hire a Gardener</span></h1>
            <p>Transform your space with nature. Buy premium plants or get expert help for your garden today.</p>
            <div class="hero-btns">
                <a href="shop.php" class="btn btn-primary btn-lg pulse-btn">
                    <i class="fa-solid fa-leaf"></i> Shop Now
                </a>
                <a href="services.php" class="btn btn-outline btn-lg">
                    <i class="fa-solid fa-user-tie"></i> Book Service
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <h3 class="counter">5000+</h3>
                    <p>Happy Clients</p>
                </div>
                <div class="stat-item">
                    <h3 class="counter">100+</h3>
                    <p>Expert Gardeners</p>
                </div>
                <div class="stat-item">
                    <h3 class="counter">500+</h3>
                    <p>Plant Varieties</p>
                </div>
            </div>
        </div>
        <div class="hero-image animate-fadeInRight">
            <div class="hero-image-wrapper">
                <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=800&auto=format&fit=crop" alt="Beautiful Plants" class="main-hero-img">
                <div class="floating-card card-1">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>Free Delivery</span>
                </div>
                <div class="floating-card card-2">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>100% Organic</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Offer Bar -->
<div class="offer-bar">
    <div class="container">
        <p><i class="fa-solid fa-truck-fast"></i> Free Shipping on orders over ₹999</p>
        <p><i class="fa-solid fa-star"></i> New Collection Added!</p>
        <p><i class="fa-solid fa-tag"></i> Flat 20% OFF for new users</p>
    </div>
</div>

<!-- Key Features -->
<section class="features section-padding">
    <div class="container">
        <h2 class="section-title">Why Choose Greenly?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon-box"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Fast Delivery</h3>
                <p>Get your plants delivered safely to your doorstep within 2-3 days.</p>
            </div>
            <div class="feature-card">
                <div class="icon-box"><i class="fa-solid fa-book-open"></i></div>
                <h3>Plant Care Guide</h3>
                <p>Detailed care instructions for every plant you buy.</p>
            </div>
            <div class="feature-card">
                <div class="icon-box"><i class="fa-solid fa-user-check"></i></div>
                <h3>Expert Gardeners</h3>
                <p>Book verified professionals for your garden maintenance.</p>
            </div>
        </div>
    </div>
</section>

<!-- Shop by Category -->
<section class="categories section-padding bg-light">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="category-grid">
            <a href="shop.php?category=plants" class="category-card">
                <img src="assets/images/monstera deliciosa.jpg" alt="Plants">
                <h3>Plants</h3>
            </a>
            <a href="shop.php?category=pots" class="category-card">
                <img src="assets/images/ceramic pots.webp" alt="Pots">
                <h3>Pots & Planters</h3>
            </a>
            <a href="shop.php?category=fertilizers" class="category-card">
                <img src="assets/images/organic fertilizer.jpeg" alt="Fertilizers">
                <h3>Fertilizers</h3>
            </a>
            <a href="shop.php?category=gardening tools" class="category-card">
                <img src="assets/images/mini gardening tool set.webp" alt="Tools">
                <h3>Tools</h3>
            </a>
        </div>
    </div>
</section>

<!-- Trending Products (Top Picks) -->
<section class="trending section-padding">
    <div class="container">
        <h2 class="section-title">Trending Now</h2>
        <div class="products-grid">
            <!-- Product 1 -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/snake plant.webp" alt="Snake Plant">
                    <a href="add_to_wishlist.php?product_id=1" class="add-to-wishlist"><i class="fa-regular fa-heart"></i></a>
                    <a href="shop.php" class="add-to-cart-btn">Add to Cart</a>
                </div>
                <div class="product-info">
                    <h4>Snake Plant</h4>
                    <p class="price">₹350.00</p>
                    <div class="rating">
                        <i class="fa-solid fa-star"></i> 4.5
                    </div>
                </div>
            </div>
             <!-- Product 2 -->
             <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/ceramic pots.webp" alt="Ceramic Pot">
                    <a href="add_to_wishlist.php?product_id=2" class="add-to-wishlist"><i class="fa-regular fa-heart"></i></a>
                    <a href="shop.php" class="add-to-cart-btn">Add to Cart</a>
                </div>
                <div class="product-info">
                    <h4>Premium Ceramic Pot</h4>
                    <p class="price">₹500.00</p>
                    <div class="rating">
                        <i class="fa-solid fa-star"></i> 4.8
                    </div>
                </div>
            </div>
             <!-- Product 3 -->
             <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/monstera deliciosa.jpg" alt="Monstera">
                    <a href="add_to_wishlist.php?product_id=3" class="add-to-wishlist"><i class="fa-regular fa-heart"></i></a>
                    <a href="shop.php" class="add-to-cart-btn">Add to Cart</a>
                </div>
                <div class="product-info">
                    <h4>Monstera Deliciosa</h4>
                    <p class="price">₹899.00</p>
                    <div class="rating">
                        <i class="fa-solid fa-star"></i> 4.9
                    </div>
                </div>
            </div>
             
        </div>
    </div>
</section>

<!-- Gardener Booking CTA -->
<section class="gardener-cta section-padding">
    <div class="container cta-box">
        <div class="cta-text">
            <h2>Need a hand in your garden?</h2>
            <p>Our expert gardeners are just a click away. Book for maintenance, design, or specialized care.</p>
            <a href="services.php" class="btn btn-white">Book a Gardener</a>
        </div>
    </div>
</section>

<!-- Blog & Sidebar Section -->
<section class="blogs-section section-padding">
    <div class="container grid-sidebar">
        <!-- Main Blog Area -->
        <div class="blog-main">
            <h2 class="section-title left-align">Latest from Blog</h2>
            <div class="blog-list">
                <article class="blog-card horizontal">
                    <img src="https://img.freepik.com/premium-photo/photo-indoor-plants-isolated-white-background_763111-135207.jpg?w=2000" alt="Blog 1">
                    <div class="blog-content">
                        <h3>5 Tips for Indoor Plant Care</h3>
                        <p>Learn how to keep your indoor garden thriving with these simple tips...</p>
                        <a href="#" class="read-more">Read More <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
                <article class="blog-card horizontal">
                    <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=300" alt="Blog 2">
                    <div class="blog-content">
                        <h3>Best Plants for Air Purification</h3>
                        <p>Discover which plants can help improve the air quality in your home...</p>
                        <a href="#" class="read-more">Read More <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="widget">
                <h3>Gardening Tips <i class="fa-solid fa-lightbulb"></i></h3>
                <ul class="tips-list">
                    <li><i class="fa-solid fa-check"></i> Water explicitly when soil is dry.</li>
                    <li><i class="fa-solid fa-check"></i> Use organic fertilizers monthly.</li>
                    <li><i class="fa-solid fa-check"></i> Prune dead leaves regularly.</li>
                </ul>
            </div>
            <div class="widget">
                <h3>Top Rated Gardeners</h3>
                <div class="mini-profile">
                    <div class="avatar">RK</div>
                    <div>
                        <h4>Ramesh Kumar</h4>
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                    </div>
                </div><br>
                <div class="mini-profile">
                    <div class="avatar">KP</div>
                    <div>
                        <h4>Krushna Patil</h4>
                        <div class="stars">⭐⭐⭐⭐</div>
                    </div>
                </div><br>
                <div class="mini-profile">
                    <div class="avatar">NJ</div>
                    <div>
                        <h4>Ninad Jadhav</h4>
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
