<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latino Laundry System - Welcome</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #2563eb;
      --secondary-blue: #1e40af;
      --clean-white: #ffffff;
      --light-gray: #f8fafc;
      --medium-gray: #64748b;
      --dark-gray: #1e293b;
    }
    
    body { 
      margin: 0; 
      font-family: 'Segoe UI', Arial, sans-serif; 
      background: var(--light-gray); 
      scroll-behavior: smooth; 
      color: var(--dark-gray);
    }
    
    .header {
      background: var(--clean-white);
      color: var(--dark-gray);
      padding: 0.5rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 1000;
      height: 70px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
    
    .nav {
      display: flex;
      gap: 5rem;
      align-items: center;
    }
    
    .nav a {
      color: var(--dark-gray);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
      padding: 0.5rem 0;
    }
    
    .nav a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: #2563eb;
      transition: width 0.3s ease;
    }
    
    .nav a:hover { 
      color: #2563eb;
      transform: translateY(-1px);
    }
    
    .nav a:hover::after {
      width: 100%;
    }
    
    .header-actions {
      display: flex;
      gap: 2rem;
      align-items: center;
    }
    
    .phone-number {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--dark-gray);
      font-weight: 600;
      font-size: 0.95rem;
    }
    
    .phone-number i {
      color: #2563eb;
      font-size: 1rem;
    }
    
    .btn {
      padding: 0.6rem 1.5rem;
      border-radius: 0.6rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      font-size: 0.95rem;
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn:hover::before {
      left: 100%;
    }
    
    .btn-login {
      background: transparent;
      color: #2563eb;
      border: 2px solid #2563eb;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
      font-size: 1rem;
      padding: 0.6rem 1.5rem;
      border-radius: 0.6rem;
    }
    
    .btn-login:hover { 
      background: #2563eb; 
      color: var(--clean-white);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }
    
    .btn-cta {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: var(--clean-white);
      font-size: 1rem;
      padding: 0.6rem 1.5rem;
      border-radius: 0.6rem;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }
    
    .btn-cta:hover { 
      background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }
    
    .btn-secondary {
      background: transparent;
      color: var(--clean-white);
      border: 2px solid var(--clean-white);
      font-size: 1.1rem;
      padding: 0.8rem 2.2rem;
      border-radius: 0.8rem;
      box-shadow: 0 2px 8px rgba(255, 255, 255, 0.1);
    }
    
    .btn-secondary:hover {
      background: var(--clean-white);
      color: #2563eb;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    }
    
    .hero-buttons {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .hero {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: var(--clean-white);
      padding: 3rem 2rem;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      gap: 3rem;
      min-height: 60vh;
    }
    
    .hero-text {
      flex: 1;
      min-width: 280px;
      max-width: 500px;
    }
    
    .hero-text h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
      color: var(--clean-white);
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    
    .hero-text p {
      font-size: 1.3rem;
      margin-bottom: 2.5rem;
      color: var(--clean-white);
      line-height: 1.6;
      text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }
    
    .hero-img {
      flex: 1;
      min-width: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
      border-radius: 1.5rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .hero-img img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;
      transition: opacity 1s ease-in-out;
      border-radius: 1.5rem;
    }
    
    .hero-img img.active {
      opacity: 1;
      position: relative;
    }
    
    .logo {
      height: 100px;
      width: auto;
      display: inline-block;
      vertical-align: middle;
      max-height: 100%;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .logo:hover {
      transform: scale(1.05);
      filter: brightness(1.1);
    }
    
    section {
      padding: 5rem 2rem;
      text-align: center;
      background: var(--clean-white);
    }
    
    section:nth-child(even) {
      background: var(--light-gray);
    }
    
    section h2 {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      color: var(--dark-gray);
      font-weight: 700;
    }
    
    section p {
      font-size: 1.2rem;
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.7;
      color: var(--medium-gray);
    }
    
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }
    
    .service-card {
      background: var(--clean-white);
      padding: 2rem;
      border-radius: 1rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    
    .service-card:hover {
      transform: translateY(-5px);
    }
    
    .service-card i {
      font-size: 3rem;
      color: #2563eb;
      margin-bottom: 1rem;
    }
    
    .service-card h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--dark-gray);
    }
    
    .service-card p {
      color: var(--medium-gray);
      line-height: 1.6;
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }
    
    .feature {
      text-align: center;
      padding: 2rem;
    }
    
    .feature i {
      font-size: 3rem;
      color: #2563eb;
      margin-bottom: 1rem;
    }
    
    .feature h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--dark-gray);
    }
    
    .feature p {
      color: var(--medium-gray);
      line-height: 1.6;
    }
    
    .contact-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      margin-top: 3rem;
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
    }
    
    
    .contact-info h3 {
      font-size: 1.8rem;
      margin-bottom: 2rem;
      color: var(--dark-gray);
    }
    
    .contact-items-container {
      margin-bottom: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .contact-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      width: auto;
    }
    
    .contact-item i {
      color: #2563eb;
      font-size: 1.2rem;
      flex-shrink: 0;
      width: 20px;
      text-align: center;
      margin-top: 0.2rem;
    }
    
    .contact-item span {
      color: var(--medium-gray);
      line-height: 1.6;
      flex: 1;
      text-align: center;
    }
    
    .social-links {
      margin-top: 2rem;
      text-align: center;
    }
    
    .social-links h4 {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      color: var(--dark-gray);
    }
    
    .social-icons {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }
    
    .social-link {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background: #2563eb;
      color: var(--clean-white);
      border-radius: 50%;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .social-link:hover {
      background: #1e40af;
      transform: translateY(-2px);
    }
    
    .contact-form {
      background: var(--clean-white);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .contact-form .form-group {
      margin-bottom: 1.5rem;
    }
    
    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid #e5e7eb;
      border-radius: 0.5rem;
      font-size: 1rem;
      transition: border-color 0.3s ease;
      box-sizing: border-box;
    }
    
    .contact-form input:focus,
    .contact-form textarea:focus {
      outline: none;
      border-color: #2563eb;
    }
    
    
    .footer {
      background: var(--dark-gray);
      color: var(--clean-white);
      text-align: center;
      padding: 3rem 1rem;
      margin-top: 0;
    }
    
    @media (max-width: 768px) {
      .header { 
        flex-direction: column; 
        gap: 1rem; 
        height: auto;
        padding: 1rem;
      }
      
      .nav { 
        flex-wrap: wrap; 
        justify-content: center; 
        gap: 1rem;
      }
      
      .hero { 
        padding: 3rem 1rem; 
        min-height: 60vh;
        flex-direction: column;
        text-align: center;
      }
      
      .hero-text h1 { 
        font-size: 2.2rem; 
      }
      
      .hero-text p {
        font-size: 1.1rem;
      }
      
      .hero-img {
        min-width: 250px;
        max-width: 350px;
      }
      
      .hero-img img {
        height: 300px;
      }
      
      section h2 { 
        font-size: 2rem; 
      }
      
      section {
        padding: 3rem 1rem;
      }
      
      
      .contact-container {
        grid-template-columns: 1fr;
        gap: 2rem;
      }
      
      .services-grid {
        grid-template-columns: 1fr;
      }
      
      .features-grid {
        grid-template-columns: 1fr;
      }
      
      .hero-buttons {
        justify-content: center;
      }
      
      .phone-number {
        font-size: 0.9rem;
      }
    }
    
    @media (max-width: 480px) {
      .hero-text h1 { 
        font-size: 1.8rem; 
      }
      
      .hero-text p {
        font-size: 1rem;
      }
      
      section h2 { 
        font-size: 1.8rem; 
      }
      
      .header-actions {
        flex-direction: column;
        gap: 1rem;
      }
      
      .phone-number {
        order: -1;
      }
}
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
  <div>
    <img src="{{ asset('images/logo.png') }}" alt="Latino Laundry Logo" class="logo">
  </div>
  
  <nav class="nav">
    <a href="#about">About</a>
    <a href="#services">Services</a>
    <a href="#contacts">Contact</a>
  </nav>
  
  <div class="header-actions">
    <div class="phone-number">
      <i class="fa fa-phone"></i>
      <span>(63) 9123456789</span>
    </div>
    <a href="{{ route('login') }}" class="btn btn-login">Log in</a>
    <a href="{{ route('register') }}" class="btn btn-cta">Sign Up</a>
  </div>
</header>


  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-text">
      <h1>Schedule Your Laundry Service</h1>
      <p>Book your laundry appointment online. Clean, Fresh, On-Time - That's Our Promise. Easy Scheduling • Professional Service • Eco-Friendly</p>
      <div class="hero-buttons">
        <a href="{{ route('register') }}" class="btn btn-cta">Schedule Now</a>
      </div>
    </div>
    <div class="hero-img">
      <img src="{{ asset('images/1.jpg') }}" alt="Laundry basket" class="active">
      <img src="{{ asset('images/2.jpg') }}" alt="Laundry shop">
    </div>
  </section>

    <!-- Services -->
  <section id="services">
    <h2>How It Works</h2>
    <div class="services-grid">
      <div class="service-card">
        <i class="fas fa-calendar-alt"></i>
        <h3>1. Schedule Online</h3>
        <p>Book your preferred date and time for laundry drop-off</p>
      </div>
      <div class="service-card">
        <i class="fas fa-tshirt"></i>
        <h3>2. Drop Off</h3>
        <p>Bring your laundry at your scheduled appointment time</p>
      </div>
      <div class="service-card">
        <i class="fas fa-clock"></i>
        <h3>3. Pick Up</h3>
        <p>Collect your clean, fresh laundry when ready</p>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section id="about">
    <h2>Why Choose Our Scheduling System?</h2>
    <div class="features-grid">
      <div class="feature">
        <i class="fas fa-calendar-check"></i>
        <h3>Easy Scheduling</h3>
        <p>Book your laundry appointment in just a few clicks</p>
      </div>
      <div class="feature">
        <i class="fas fa-clock"></i>
        <h3>Time Management</h3>
        <p>No more waiting - guaranteed appointment times</p>
      </div>
      <div class="feature">
        <i class="fas fa-mobile-alt"></i>
        <h3>Online Convenience</h3>
        <p>Schedule, reschedule, or cancel anytime from your device</p>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contacts">
    <h2>Contact Us</h2>
    <div class="contact-container">
      <div class="contact-info">
        <h3>Get In Touch</h3>
        <div class="contact-items-container">
          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>(555) 123-LAUNDRY</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>support@latinolaundry.com</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>123 Laundry Street, City, State 12345</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-clock"></i>
            <span>Mon-Fri: 7AM-7PM<br>Sat: 8AM-5PM<br>Sun: Closed</span>
          </div>
        </div>
        <div class="social-links">
          <h4>Follow Us</h4>
          <div class="social-icons">
            <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
      <div class="contact-form">
        <form>
          <div class="form-group">
            <input type="text" placeholder="Your Name" required>
          </div>
          <div class="form-group">
            <input type="email" placeholder="Your Email" required>
          </div>
          <div class="form-group">
            <input type="tel" placeholder="Your Phone">
          </div>
          <div class="form-group">
            <textarea placeholder="Your Message" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-cta">Send Message</button>
        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2025 Latino Laundry Station. All rights reserved.</p>
  </footer>

  <script>
    // Simple Auto-Slider
    let currentIndex = 0;
    const slides = document.querySelectorAll('.hero-img img');
    const totalSlides = slides.length;

    function showSlide(index) {
      slides.forEach((img, i) => {
        img.classList.remove('active');
        if (i === index) img.classList.add('active');
      });
    }

    setInterval(() => {
      currentIndex = (currentIndex + 1) % totalSlides;
      showSlide(currentIndex);
    }, 4000); // change every 4s
  </script>
</body>
</html>
