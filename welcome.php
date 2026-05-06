<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Clinic Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --blue-deep: #0A2472;
      --blue-mid: #1D4ED8;
      --blue-bright: #3B82F6;
      --blue-light: #DBEAFE;
      --blue-pale: #EFF6FF;
      --accent: #F59E0B;
      --accent2: #10B981;
      --white: #ffffff;
      --text-dark: #0F172A;
      --text-mid: #334155;
      --text-muted: #64748B;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--white);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* NAV */
    nav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: var(--blue-deep);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2.5rem;
      height: 68px;
      box-shadow: 0 4px 24px rgba(10, 36, 114, 0.25);
    }

    .nav-brand {
      font-family: 'Sora', sans-serif;
      font-weight: 800;
      font-size: 1.3rem;
      color: var(--white);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .brand-icon {
      width: 36px;
      height: 36px;
      background: var(--blue-bright);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 2rem;
      list-style: none;
    }

    .nav-links a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 500;
      transition: color 0.2s;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--white);
    }

    .nav-search {
      display: flex;
      gap: 8px;
    }

    .nav-search input {
      background: rgba(255, 255, 255, 0.12);
      border: 1.5px solid rgba(255, 255, 255, 0.2);
      color: white;
      padding: 7px 16px;
      border-radius: 50px;
      font-size: 0.875rem;
      outline: none;
      width: 180px;
      transition: all 0.2s;
      font-family: 'DM Sans', sans-serif;
    }

    .nav-search input::placeholder {
      color: rgba(255, 255, 255, 0.45);
    }

    .nav-search input:focus {
      background: rgba(255, 255, 255, 0.2);
      border-color: var(--blue-bright);
    }

    .nav-search button {
      background: var(--blue-bright);
      color: white;
      border: none;
      padding: 7px 18px;
      border-radius: 50px;
      font-size: 0.875rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
      font-family: 'DM Sans', sans-serif;
    }

    .nav-search button:hover {
      background: #2563EB;
    }

    /* HERO */
    .hero {
      background: linear-gradient(135deg, var(--blue-deep) 0%, #1E3A8A 45%, #1D4ED8 100%);
      min-height: 530px;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
      padding: 5rem 2.5rem;
    }

    .hero-blob1 {
      position: absolute;
      top: -100px;
      right: -100px;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.28) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .hero-blob2 {
      position: absolute;
      bottom: -120px;
      left: 35%;
      width: 420px;
      height: 420px;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.13) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .hero-content {
      max-width: 1100px;
      margin: 0 auto;
      width: 100%;
      position: relative;
      z-index: 2;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(59, 130, 246, 0.22);
      border: 1px solid rgba(59, 130, 246, 0.45);
      color: #93C5FD;
      padding: 6px 16px;
      border-radius: 50px;
      font-size: 0.78rem;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      animation: fadeUp 0.6s ease both;
    }

    .hero h1 {
      font-family: 'Sora', sans-serif;
      font-size: clamp(2.4rem, 5.5vw, 4rem);
      font-weight: 800;
      color: var(--white);
      line-height: 1.08;
      margin-bottom: 1.25rem;
      animation: fadeUp 0.7s ease 0.1s both;
    }

    .hero h1 .accent {
      color: var(--accent);
    }

    .hero p {
      color: rgba(255, 255, 255, 0.72);
      font-size: 1.1rem;
      max-width: 500px;
      line-height: 1.75;
      margin-bottom: 2.25rem;
      animation: fadeUp 0.7s ease 0.2s both;
    }

    .hero-ctas {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeUp 0.7s ease 0.3s both;
    }

    .btn-primary {
      background: var(--blue-bright);
      color: white;
      padding: 14px 34px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 1rem;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 4px 22px rgba(59, 130, 246, 0.45);
      font-family: 'DM Sans', sans-serif;
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(59, 130, 246, 0.55);
    }

    .btn-ghost {
      background: transparent;
      color: white;
      padding: 14px 34px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1rem;
      text-decoration: none;
      border: 2px solid rgba(255, 255, 255, 0.3);
      transition: all 0.2s;
      font-family: 'DM Sans', sans-serif;
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: rgba(255, 255, 255, 0.7);
    }

    .hero-stats {
      display: flex;
      gap: 2.5rem;
      margin-top: 3.5rem;
      flex-wrap: wrap;
      animation: fadeUp 0.7s ease 0.4s both;
    }

    .stat-num {
      font-family: 'Sora', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      color: var(--white);
      line-height: 1;
    }

    .stat-label {
      font-size: 0.78rem;
      color: rgba(255, 255, 255, 0.5);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-top: 4px;
    }

    /* WAVE */
    .wave {
      display: block;
      background: var(--blue-pale);
      line-height: 0;
    }

    .wave svg {
      display: block;
      width: 100%;
    }

    /* LOGIN SECTION */
    .section-login {
      background: var(--blue-pale);
      padding: 5rem 2.5rem 6rem;
    }

    .section-title {
      text-align: center;
      margin-bottom: 3.5rem;
    }

    .eyebrow {
      display: inline-block;
      background: var(--blue-light);
      color: var(--blue-mid);
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 5px 16px;
      border-radius: 50px;
      margin-bottom: 1rem;
    }

    .section-title h2 {
      font-family: 'Sora', sans-serif;
      font-size: clamp(1.8rem, 3vw, 2.6rem);
      font-weight: 800;
      color: var(--blue-deep);
    }

    .section-title p {
      color: var(--text-muted);
      margin-top: 0.75rem;
      font-size: 1rem;
    }

    .cards-grid {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.75rem;
    }

    .login-card {
      background: var(--white);
      border-radius: 24px;
      padding: 2.5rem 2rem 2rem;
      text-align: center;
      border: 2px solid transparent;
      transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
      overflow: hidden;
    }

    .login-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
    }

    .card-patient::after {
      background: linear-gradient(90deg, #10B981, #34D399);
    }

    .card-doctor::after {
      background: linear-gradient(90deg, #3B82F6, #818CF8);
    }

    .card-admin::after {
      background: linear-gradient(90deg, #F59E0B, #FB923C);
    }

    .login-card:hover {
      transform: translateY(-8px) scale(1.01);
      box-shadow: 0 24px 60px rgba(10, 36, 114, 0.13);
    }

    .card-patient:hover {
      border-color: #A7F3D0;
    }

    .card-doctor:hover {
      border-color: var(--blue-light);
    }

    .card-admin:hover {
      border-color: #FDE68A;
    }

    .card-icon {
      width: 78px;
      height: 78px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2rem;
    }

    .card-patient .card-icon {
      background: #D1FAE5;
    }

    .card-doctor .card-icon {
      background: #DBEAFE;
    }

    .card-admin .card-icon {
      background: #FEF3C7;
    }

    .login-card h3 {
      font-family: 'Sora', sans-serif;
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--blue-deep);
      margin-bottom: 0.75rem;
    }

    .login-card p {
      color: var(--text-muted);
      font-size: 0.92rem;
      line-height: 1.65;
      margin-bottom: 2rem;
    }

    .btn-card {
      display: inline-block;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 0.93rem;
      text-decoration: none;
      transition: all 0.2s;
      font-family: 'DM Sans', sans-serif;
    }

    .card-patient .btn-card {
      background: #10B981;
      color: white;
      box-shadow: 0 4px 16px rgba(16, 185, 129, 0.32);
    }

    .card-patient .btn-card:hover {
      background: #059669;
      transform: scale(1.05);
    }

    .card-doctor .btn-card {
      background: #3B82F6;
      color: white;
      box-shadow: 0 4px 16px rgba(59, 130, 246, 0.32);
    }

    .card-doctor .btn-card:hover {
      background: #2563EB;
      transform: scale(1.05);
    }

    .card-admin .btn-card {
      background: #F59E0B;
      color: white;
      box-shadow: 0 4px 16px rgba(245, 158, 11, 0.32);
    }

    .card-admin .btn-card:hover {
      background: #D97706;
      transform: scale(1.05);
    }

    /* FEATURES */
    .features-strip {
      background: var(--blue-deep);
      padding: 4rem 2.5rem;
    }

    .features-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 2rem;
    }

    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }

    .feature-icon {
      width: 46px;
      height: 46px;
      background: rgba(59, 130, 246, 0.18);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    .feature-item h4 {
      font-family: 'Sora', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--white);
      margin-bottom: 5px;
    }

    .feature-item p {
      font-size: 0.82rem;
      color: rgba(255, 255, 255, 0.5);
      line-height: 1.55;
    }

    /* FOOTER */
    footer {
      background: #040E2B;
      padding: 1.75rem 2.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }

    footer p {
      color: rgba(255, 255, 255, 0.35);
      font-size: 0.85rem;
    }

    .footer-links {
      display: flex;
      gap: 1.5rem;
    }

    .footer-links a {
      color: rgba(255, 255, 255, 0.45);
      text-decoration: none;
      font-size: 0.85rem;
      transition: color 0.2s;
    }

    .footer-links a:hover {
      color: white;
    }

    /* CAROUSEL */
    .carousel-wrapper {
      position: relative;
      width: 100%;
      overflow: hidden;
      background: #000;
    }

    .carousel-track {
      position: relative;
      width: 100%;
    }

    .carousel-slide {
      display: none;
      position: relative;
      width: 100%;
      height: 420px;
    }

    .carousel-slide.active {
      display: block;
    }

    .carousel-slide img {
      width: 100%;
      height: 420px;
      object-fit: cover;
      opacity: 0.55;
    }

    .carousel-caption {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 2.5rem 3rem;
      background: linear-gradient(to top, rgba(10, 36, 114, 0.85) 0%, transparent 100%);
      animation: fadeUp 0.5s ease both;
    }

    .carousel-caption h2 {
      font-family: 'Sora', sans-serif;
      font-size: clamp(1.4rem, 3vw, 2.2rem);
      font-weight: 800;
      color: white;
      margin-bottom: 0.5rem;
    }

    .carousel-caption p {
      color: rgba(255, 255, 255, 0.8);
      font-size: 1rem;
      margin-bottom: 1.25rem;
    }

    .carousel-btns {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .cbtn {
      padding: 8px 22px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 0.85rem;
      border: none;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: transform 0.2s, opacity 0.2s;
    }

    .cbtn:hover {
      transform: scale(1.06);
      opacity: 0.9;
    }

    .cbtn-red {
      background: #EF4444;
      color: white;
    }

    .cbtn-blue {
      background: #3B82F6;
      color: white;
    }

    .cbtn-green {
      background: #10B981;
      color: white;
    }

    .carousel-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.3);
      color: white;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background 0.2s;
      backdrop-filter: blur(4px);
      z-index: 10;
    }

    .carousel-arrow:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .carousel-prev {
      left: 1.5rem;
    }

    .carousel-next {
      right: 1.5rem;
    }

    .carousel-dots {
      position: absolute;
      bottom: 1.25rem;
      right: 1.5rem;
      display: flex;
      gap: 8px;
      z-index: 10;
    }

    .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      border: none;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
      padding: 0;
    }

    .dot.active {
      background: white;
      transform: scale(1.3);
    }

    /* slide fade transition */
    .carousel-slide.fade-in {
      animation: slideFade 0.5s ease both;
    }

    @keyframes slideFade {
      from {
        opacity: 0;
        transform: scale(1.02);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 640px) {
      nav {
        padding: 0 1.25rem;
      }

      .nav-links,
      .nav-search {
        display: none;
      }

      .hero {
        padding: 3.5rem 1.25rem;
      }

      .section-login {
        padding: 3.5rem 1.25rem 4rem;
      }

      .features-strip {
        padding: 2.5rem 1.25rem;
      }

      footer {
        flex-direction: column;
        text-align: center;
      }

      .footer-links {
        justify-content: center;
      }
    }
  </style>
</head>

<body>

  <nav>
    <a class="nav-brand" href="#">
      <span class="brand-icon">🏥</span>
      Clinic Management
    </a>
    <ul class="nav-links">
      <li><a href="#" class="active">Home</a></li>
      <li><a href="about.html">About</a></li>
      <li><a href="contact.html">Contact Us</a></li>
    </ul>
    <div class="nav-search">
      <input type="search" placeholder="Search...">
      <button>Search</button>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-blob1"></div>
    <div class="hero-blob2"></div>
    <div class="hero-content">
      <div class="hero-badge">✦ Trusted Healthcare Platform</div>
      <h1>Here for you,<br>Every <span class="accent">Step of the way</span></h1>
      <p>A modern clinic management system connecting patients, doctors, and administrators — all in one seamless platform.</p>
      <div class="hero-ctas">
        <a href="patient_login.php" class="btn-primary">Get Started</a>
        <a href="about.html" class="btn-ghost">Learn More</a>
      </div>
      <div class="hero-stats">
        <div>
          <div class="stat-num">10K+</div>
          <div class="stat-label">Patients Served</div>
        </div>
        <div>
          <div class="stat-num">200+</div>
          <div class="stat-label">Doctors</div>
        </div>
        <div>
          <div class="stat-num">98%</div>
          <div class="stat-label">Satisfaction</div>
        </div>
      </div>
    </div>
  </section>

  <!-- CAROUSEL -->
  <div class="carousel-wrapper">
    <div class="carousel-track" id="carouselTrack">
      <div class="carousel-slide active">
        <img src="doctor2.jpg" alt="Welcome to Healthy Living">
        <div class="carousel-caption">
          <h2>Welcome to Healthy Living</h2>
          <p>Your Guide to Health, Wellness, and Vitality</p>
          <div class="carousel-btns">
            <button class="cbtn cbtn-red">Health</button>
            <button class="cbtn cbtn-blue">Wealthy</button>
            <button class="cbtn cbtn-green">Fitness</button>
          </div>
        </div>
      </div>
      <div class="carousel-slide">
        <img src="1.jpg" alt="The Best Wellness Blog">
        <div class="carousel-caption">
          <h2>The Best Wellness Blog</h2>
          <p>Your Path to a Healthy and Happy Life</p>
          <div class="carousel-btns">
            <button class="cbtn cbtn-red">Health</button>
            <button class="cbtn cbtn-blue">Wealthy</button>
            <button class="cbtn cbtn-green">Fitness</button>
          </div>
        </div>
      </div>
      <div class="carousel-slide">
        <img src="7.jpg" alt="Award Winning Wellness Blog">
        <div class="carousel-caption">
          <h2>Award Winning Wellness Blog</h2>
          <p>Empowering You to Lead a Healthier Life</p>
          <div class="carousel-btns">
            <button class="cbtn cbtn-red">Health</button>
            <button class="cbtn cbtn-blue">Wealthy</button>
            <button class="cbtn cbtn-green">Fitness</button>
          </div>
        </div>
      </div>
    </div>
    <button class="carousel-arrow carousel-prev" id="carouselPrev">&#8592;</button>
    <button class="carousel-arrow carousel-next" id="carouselNext">&#8594;</button>
    <div class="carousel-dots" id="carouselDots"></div>
  </div>

  <div class="wave">
    <svg viewBox="0 0 1440 56" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0,28 C480,70 960,-14 1440,28 L1440,0 L0,0 Z" fill="#1E3A8A" />
    </svg>
  </div>

  <section class="section-login">
    <div class="section-title">
      <span class="eyebrow">Access Portal</span>
      <h2>Who Are You?</h2>
      <p>Choose your role to access your personalized dashboard</p>
    </div>

    <div class="cards-grid">

      <div class="login-card card-patient">
        <div class="card-icon">🩺</div>
        <h3>Patient</h3>
        <p>Access your health records, book appointments, and get personalized wellness tips for a healthier lifestyle.</p>
        <a class="btn-card" href="patient_login.php">Log In as Patient</a>
      </div>

      <div class="login-card card-doctor">
        <div class="card-icon">👨‍⚕️</div>
        <h3>Doctor</h3>
        <p>Access patient records, the latest medical research, and clinical tools to enhance your practice.</p>
        <a class="btn-card" href="Doctor_login.php">Log In as Doctor</a>
      </div>

      <div class="login-card card-admin">
        <div class="card-icon"></div>
        <h3>Admin</h3>
        <p>Manage systems, organize patient data, and oversee all operational and administrative tasks efficiently.</p>
        <a class="btn-card" href="admin_login.php">Log In as Admin</a>
      </div>

    </div>
  </section>

  <section class="features-strip">
    <div class="features-inner">
      <div class="feature-item">
        <div class="feature-icon">🔒</div>
        <div>
          <h4>Secure & Private</h4>
          <p>All health data is fully encrypted and protected at every level.</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">⚡</div>
        <div>
          <h4>Fast Access</h4>
          <p>Instant access to records and appointments 24/7.</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">📱</div>
        <div>
          <h4>Mobile Friendly</h4>
          <p>Works seamlessly on any device, anywhere you are.</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">🤝</div>
        <div>
          <h4>24/7 Support</h4>
          <p>Our team is always available when you need help.</p>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <p>© 2025–2026 Laflame, Inc. All rights reserved.</p>
    <div class="footer-links">
      <a href="#">Back to top ↑</a>
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
    </div>
  </footer>

  <script>
    const slides = document.querySelectorAll('.carousel-slide');
    const dotsContainer = document.getElementById('carouselDots');
    let current = 0;
    let timer;

    // Build dots
    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'dot' + (i === 0 ? ' active' : '');
      dot.addEventListener('click', () => goTo(i));
      dotsContainer.appendChild(dot);
    });

    function getDots() {
      return document.querySelectorAll('.dot');
    }

    function goTo(index) {
      slides[current].classList.remove('active', 'fade-in');
      getDots()[current].classList.remove('active');
      current = (index + slides.length) % slides.length;
      slides[current].classList.add('active', 'fade-in');
      getDots()[current].classList.add('active');
      resetTimer();
    }

    function resetTimer() {
      clearInterval(timer);
      timer = setInterval(() => goTo(current + 1), 4500);
    }

    document.getElementById('carouselPrev').addEventListener('click', () => goTo(current - 1));
    document.getElementById('carouselNext').addEventListener('click', () => goTo(current + 1));

    resetTimer();
  </script>
</body>

</html>