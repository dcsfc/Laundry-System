<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latino Laundry Station - Professional Laundry Scheduling</title>
    <meta name="description" content="Professional laundry scheduling made simple. Book your laundry service online with Latino Laundry Station - clean, fresh, and on-time delivery guaranteed.">
    <meta name="keywords" content="laundry service, laundry scheduling, professional cleaning, same day service, online booking, Latino Laundry">
    <meta name="author" content="Latino Laundry Station">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Latino Laundry Station - Professional Laundry Scheduling">
    <meta property="og:description" content="Professional laundry scheduling made simple. Book your laundry service online with Latino Laundry Station - clean, fresh, and on-time delivery guaranteed.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <meta property="og:image" content="<?php echo e(asset('images/logo-removebg-preview_imgupscaler.ai_General_16K.png')); ?>">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Latino Laundry Station - Professional Laundry Scheduling">
    <meta name="twitter:description" content="Professional laundry scheduling made simple. Book your laundry service online with Latino Laundry Station - clean, fresh, and on-time delivery guaranteed.">
    <meta name="twitter:image" content="<?php echo e(asset('images/logo-removebg-preview_imgupscaler.ai_General_16K.png')); ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/landing.css')); ?>">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="<?php echo e(asset('images/logo-removebg-preview_imgupscaler.ai_General_16K.png')); ?>" alt="Latino Laundry" class="logo-image">
                </div>
                
                <nav class="nav">
                    <a href="#about" class="nav-link">About</a>
                    <a href="#services" class="nav-link">Services</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </nav>
                
                <div class="header-actions">
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>(555) 123-LAUNDRY</span>
                    </div>
                    <a href="#contact" class="btn btn--outline btn--sm">Get Quote</a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn--primary btn--sm">Schedule Now</a>
                </div>
                
                <button class="mobile-menu-toggle" aria-label="Toggle mobile menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden">
        <div class="mobile-menu-content">
            <a href="#about" class="mobile-nav-link">About</a>
            <a href="#services" class="mobile-nav-link">Services</a>
            <a href="#contact" class="mobile-nav-link">Contact</a>
            <div class="mobile-menu-actions">
                <a href="#contact" class="btn btn--outline btn--full-width">Get Quote</a>
                <a href="<?php echo e(route('login')); ?>" class="btn btn--primary btn--full-width">Schedule Now</a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Professional Laundry Scheduling Made Simple</h1>
                    <p class="hero-subtitle">Book your laundry service online. Clean, fresh, and on-time delivery with our easy scheduling system.</p>
                    
                    <div class="hero-buttons">
                        <a href="<?php echo e(route('login')); ?>" class="btn btn--primary btn--lg">Schedule Now</a>
                        <a href="#services" class="btn btn--outline btn--lg">Learn More</a>
                    </div>
                    
                    <div class="trust-indicators">
                        <div class="trust-item">
                            <i class="fas fa-users"></i>
                            <span>500+ Happy Customers</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-star"></i>
                            <span>4.9/5 Rating</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-clock"></i>
                            <span>Same Day Service</span>
                        </div>
                    </div>
                    
                </div>
                
                <div class="hero-image">
                    <div class="hero-image-container">
                        <img src="<?php echo e(asset('images/1.jpg')); ?>" alt="Professional Laundry Service" class="hero-image-main" id="heroImage1">
                        <img src="<?php echo e(asset('images/2.jpg')); ?>" alt="Professional Laundry Service" class="hero-image-main" id="heroImage2" style="display: none;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="services" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Simple steps to get your laundry professionally cleaned</p>
            </div>
            
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="step-number">1</div>
                    <h3 class="step-title">Schedule Online</h3>
                    <p class="step-description">Book your preferred date and time through our easy online scheduling system</p>
                </div>
                
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div class="step-number">2</div>
                    <h3 class="step-title">Drop Off Service</h3>
                    <p class="step-description">Bring your laundry at your scheduled appointment time to our location</p>
                </div>
                
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="step-number">3</div>
                    <h3 class="step-title">Pickup Ready</h3>
                    <p class="step-description">Collect your clean, fresh laundry when it's ready for pickup</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="about" class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose Our Scheduling System?</h2>
                <p class="section-subtitle">Experience the convenience of professional laundry service</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Easy Online Scheduling</h3>
                    <p class="feature-description">Book your laundry appointment in just a few clicks with our user-friendly system</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Guaranteed Time Slots</h3>
                    <p class="feature-description">No more waiting - we guarantee your scheduled appointment times</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">24/7 Convenience</h3>
                    <p class="feature-description">Schedule, reschedule, or manage appointments anytime from any device</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">What Our Customers Say</h2>
                <p class="section-subtitle">Real feedback from satisfied customers who trust our laundry service</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"The online scheduling system is incredibly convenient. I can book my laundry service anytime, and they always deliver on time. Highly recommended!"</p>
                    <div class="testimonial-author">- Maria Rodriguez</div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"Professional service with excellent results. The staff is friendly and the quality is consistently great. My clothes always come back fresh and clean."</p>
                    <div class="testimonial-author">- John Smith</div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"The scheduling system saves me so much time. I can plan my laundry drop-offs around my busy schedule. Excellent service!"</p>
                    <div class="testimonial-author">- Sarah Johnson</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Contact Us</h2>
                <p class="section-subtitle">Get in touch to schedule your laundry service</p>
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <h3 class="contact-info-title">Get In Touch</h3>
                    
                    <div class="contact-items">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>(555) 123-LAUNDRY</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@latinolaundry.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Laundry Street, City, State 12345</span>
                        </div>
                    </div>
                    
                    <div class="business-hours">
                        <h4>Business Hours</h4>
                        <div class="hours-item">
                            <span>Mon-Fri: 7AM-7PM</span>
                        </div>
                        <div class="hours-item">
                            <span>Sat: 8AM-5PM</span>
                        </div>
                        <div class="hours-item">
                            <span>Sun: Closed</span>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <h4>Follow Us</h4>
                        <div class="social-icons">
                            <a href="#" class="social-link" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="social-link" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Your Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn--primary btn--full-width">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <h3 class="footer-logo">Latino Laundry Station</h3>
                    <p>Professional laundry scheduling made simple</p>
                </div>
                <div class="footer-copyright">
                    <p>&copy; 2025 Latino Laundry Station. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo e(asset('js/landing.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/landing.blade.php ENDPATH**/ ?>