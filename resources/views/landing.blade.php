<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ get_setting('app_name', config('app.name')) }} — Construction ERP</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Landing Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/landing_page.css') }}">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <a href="#home" class="logo">
                <i class="fas fa-hard-hat"></i> 
                <span>{{ get_setting('app_name', config('app.name')) }}</span>
            </a>

            <!-- Navigation Links -->
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#about"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="#why-crm"><i class="fas fa-question-circle"></i> Why Us</a></li>
                <li><a href="#benefits"><i class="fas fa-gem"></i> Benefits</a></li>
                <li><a href="#features"><i class="fas fa-th-large"></i> Features</a></li>
                <li><a href="#contact"><i class="fas fa-envelope"></i> Contact</a></li>
                <!-- Mobile Login -->
                <li class="mobile-login"><a href="{{ route('tyro-login.login') }}" class="login-btn-mobile"><i class="fas fa-user"></i> Login</a></li>
            </ul>

            <!-- Right Side (Desktop) -->
            <div class="nav-right">
                <a href="{{ route('tyro-login.login') }}" class="login-btn"><i class="fas fa-user"></i> Login</a>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- ===== HERO SECTION ===== -->
    <section id="home" class="hero">
        <!-- Background Shapes -->
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <div class="hero-container">
            <div class="hero-content">
                <div class="badge">
                    <i class="fas fa-rocket"></i> Construction ERP 2026
                </div>
                <h1>
                    Build Smarter <br/>
                    <span class="gradient-text">Projects</span> 
                    with Intelligent ERP
                </h1>
                <p class="hero-description">
                    Manage projects, budgets, procurement, HR, safety, and documents — all from a single, powerful platform built for the construction industry.
                </p>
                <div class="btn-group">
                    <a href="#about" class="btn-primary">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                    <a href="#contact" class="btn-outline">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
                <div class="trust-badge">
                    <div class="trust-item">
                        <i class="fas fa-check-circle"></i>
                        <span><strong>22</strong> Modules</span>
                    </div>
                    <div class="trust-divider"></div>
                    <div class="trust-item">
                        <i class="fas fa-check-circle"></i>
                        <span><strong>124</strong> Features</span>
                    </div>
                    <div class="trust-divider"></div>
                    <div class="trust-item">
                        <i class="fas fa-check-circle"></i>
                        <span><strong>100%</strong> Complete</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="image-wrapper">
                    <div class="image-glow"></div>
                    <img src="{{ asset('assets/images/landing/images (2).jpg') }}" alt="Construction ERP Dashboard">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ABOUT SECTION ===== -->
    <section id="about" class="section">
        <div class="section-header">
            <span class="section-tag"><i class="fas fa-info-circle"></i> About Us</span>
            <h2>Who We <span>Are</span></h2>
            <p>We are a leading construction ERP provider dedicated to helping businesses streamline their operations and grow exponentially.</p>
        </div>
        <div class="about-container">
            <div class="about-content">
                <div class="about-text">
                    <h3>Empowering Construction with <span>Intelligent ERP</span></h3>
                    <p>At {{ get_setting('app_name', config('app.name')) }}, we believe in the power of data-driven construction management. Our platform is designed to help you manage projects better, control costs effectively, and deliver on time.</p>
                    <p>With comprehensive modules covering everything from budgeting and procurement to HR and safety compliance, we are the trusted partner for construction businesses of all sizes.</p>
                    <div class="about-stats">
                        <div class="about-stat">
                            <span class="about-number">22</span>
                            <span class="about-label">Modules</span>
                        </div>
                        <div class="about-stat">
                            <span class="about-number">124</span>
                            <span class="about-label">Features</span>
                        </div>
                        <div class="about-stat">
                            <span class="about-number">82</span>
                            <span class="about-label">Controllers</span>
                        </div>
                        <div class="about-stat">
                            <span class="about-number">119</span>
                            <span class="about-label">Migrations</span>
                        </div>
                    </div>
                    <a href="#features" class="btn-primary">Explore Features</a>
                </div>
                <div class="about-image">
                    <img src="{{ asset('assets/images/landing/images (1).jpg') }}" alt="About Us">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== WHY USE CRM SECTION ===== -->
    <section id="why-crm" class="section">
        <div class="section-header">
            <span class="section-tag"><i class="fas fa-question-circle"></i> Why Us</span>
            <h2>Why Choose <span>Our ERP</span></h2>
            <p>Discover how our ERP system can transform your construction operations and drive growth.</p>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-project-diagram"></i></div>
                <h3>Project Management</h3>
                <p>Track tasks, phases, milestones, and progress across all your construction projects in real-time.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Cost Control</h3>
                <p>Budget tracking with EVM, SPI/CPI analysis, cost overrun alerts, and forecasting to keep projects on budget.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-clock"></i></div>
                <h3>Save Time & Resources</h3>
                <p>Automate repetitive tasks like procurement, invoicing, and payroll to focus on what matters most.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Safety & Compliance</h3>
                <p>HSE checklists, incident reports, permits to work, safety audits, and toolbox talks — all in one place.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-file-contract"></i></div>
                <h3>Document Control</h3>
                <p>Manage drawings, RFIs, change orders, transmittals, and contracts with version control and approval workflows.</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-chart-pie"></i></div>
                <h3>Powerful Reports</h3>
                <p>8 financial reports, S-curves, PDF/Excel export — all built-in.</p>
            </div>
        </div>
    </section>

    <!-- ===== BENEFITS SECTION ===== -->
    <section id="benefits" class="section">
        <div class="benefits-bg-shapes">
            <div class="benefits-blob blob-1"></div>
            <div class="benefits-blob blob-2"></div>
        </div>
        <div class="section-header">
            <span class="section-tag"><i class="fas fa-gem"></i> Benefits</span>
            <h2>Key <span>Benefits</span></h2>
            <p>Discover how our ERP solution can transform your construction business with these key benefits.</p>
        </div>

        <div class="benefits-grid">
            <!-- Benefit 1 -->
            <div class="benefit-card">
                <div class="benefit-img-wrap">
                    <div class="benefit-img-bg"></div>
                    <div class="benefit-image">
                        <img src="{{ asset('assets/images/landing/images.jpg') }}" alt="Increased Productivity">
                    </div>
                </div>
                <div class="benefit-content">
                    <span class="benefit-num">01</span>
                    <h3>Increased Productivity</h3>
                    <p>Automate routine tasks and streamline workflows to boost team efficiency by up to 40%.</p>
                    <ul class="benefit-features">
                        <li><i class="fas fa-check-circle"></i> Task Automation</li>
                        <li><i class="fas fa-check-circle"></i> Workflow Optimization</li>
                        <li><i class="fas fa-check-circle"></i> Time Management</li>
                    </ul>
                </div>
            </div>

            <!-- Benefit 2 -->
            <div class="benefit-card reverse">
                <div class="benefit-img-wrap">
                    <div class="benefit-img-bg"></div>
                    <div class="benefit-image">
                        <img src="{{ asset('assets/images/landing/01-Enterprise-Architecture.webp') }}" alt="Enterprise Architecture">
                    </div>
                </div>
                <div class="benefit-content">
                    <span class="benefit-num">02</span>
                    <h3>Enterprise Architecture</h3>
                    <p>Build a scalable and robust architecture that supports your business growth and digital transformation.</p>
                    <ul class="benefit-features">
                        <li><i class="fas fa-check-circle"></i> Scalable Infrastructure</li>
                        <li><i class="fas fa-check-circle"></i> Cloud-Native Solutions</li>
                        <li><i class="fas fa-check-circle"></i> Microservices Architecture</li>
                        <li><i class="fas fa-check-circle"></i> API-First Design</li>
                    </ul>
                </div>
            </div>

            <!-- Benefit 3 -->
            <div class="benefit-card">
                <div class="benefit-img-wrap">
                    <div class="benefit-img-bg"></div>
                    <div class="benefit-image">
                        <img src="{{ asset('assets/images/landing/03-Security-and-reliability.webp') }}" alt="Security and Reliability">
                    </div>
                </div>
                <div class="benefit-content">
                    <span class="benefit-num">03</span>
                    <h3>Security & Reliability</h3>
                    <p>Financial-grade security ensures protection of project data and transactions with high-availability infrastructure.</p>
                    <ul class="benefit-features">
                        <li><i class="fas fa-check-circle"></i> End-to-end encryption</li>
                        <li><i class="fas fa-check-circle"></i> Secure authentication</li>
                        <li><i class="fas fa-check-circle"></i> Fraud protection</li>
                        <li><i class="fas fa-check-circle"></i> High-availability</li>
                    </ul>
                </div>
            </div>

            <!-- Benefit 4 -->
            <div class="benefit-card reverse">
                <div class="benefit-img-wrap">
                    <div class="benefit-img-bg"></div>
                    <div class="benefit-image">
                        <img src="{{ asset('assets/images/landing/04-Flexible-Deployment-Options.webp') }}" alt="Flexible Deployment Options">
                    </div>
                </div>
                <div class="benefit-content">
                    <span class="benefit-num">04</span>
                    <h3>Flexible Deployment</h3>
                    <p>Choose the deployment model that best suits your business needs with flexible options.</p>
                    <ul class="benefit-features">
                        <li><i class="fas fa-check-circle"></i> Cloud Deployment</li>
                        <li><i class="fas fa-check-circle"></i> On-premise Solutions</li>
                        <li><i class="fas fa-check-circle"></i> Hybrid Architecture</li>
                        <li><i class="fas fa-check-circle"></i> Multi-Cloud Support</li>
                    </ul>
                </div>
            </div>

            <!-- Benefit 5 -->
            <div class="benefit-card">
                <div class="benefit-img-wrap">
                    <div class="benefit-img-bg"></div>
                    <div class="benefit-image">
                        <img src="{{ asset('assets/images/landing/05-Why-iBank23.webp') }}" alt="Why Our ERP">
                    </div>
                </div>
                <div class="benefit-content">
                    <span class="benefit-num">05</span>
                    <h3>Complete Solution</h3>
                    <p>Experience the power of a comprehensive ERP with modules covering every aspect of construction management.</p>
                    <ul class="benefit-features">
                        <li><i class="fas fa-check-circle"></i> Project Planning</li>
                        <li><i class="fas fa-check-circle"></i> Financial Automation</li>
                        <li><i class="fas fa-check-circle"></i> Seamless Integrations</li>
                        <li><i class="fas fa-check-circle"></i> Client-Centric Design</li>
                    </ul>
                </div>
            </div>

            <!-- Benefit 6 -->
            <div class="benefit-card reverse">
                <div class="benefit-img-wrap">
                    <div class="benefit-img-bg"></div>
                    <div class="benefit-image">
                        <img src="{{ asset('assets/images/landing/02-Operational-Control-Compliance.webp') }}" alt="Operational Control & Compliance">
                    </div>
                </div>
                <div class="benefit-content">
                    <span class="benefit-num">06</span>
                    <h3>Operational Control</h3>
                    <p>Maintain complete control over your operations with built-in compliance frameworks and real-time monitoring.</p>
                    <ul class="benefit-features">
                        <li><i class="fas fa-check-circle"></i> Real-time Monitoring</li>
                        <li><i class="fas fa-check-circle"></i> Compliance Automation</li>
                        <li><i class="fas fa-check-circle"></i> Audit Trails</li>
                        <li><i class="fas fa-check-circle"></i> Risk Management</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES SECTION ===== -->
    <section id="features" class="section">
        <div class="section-header">
            <span class="section-tag"><i class="fas fa-th-large"></i> Features</span>
            <h2>All-in-One <span>Construction ERP</span></h2>
            <p>Everything you need to manage your entire construction operation efficiently.</p>
        </div>
        <div class="feature-grid">
            <div class="card">
                <div class="icon"><i class="fas fa-project-diagram"></i></div>
                <h3>Project Management</h3>
                <p>Tasks, phases, milestones, Gantt charts, and progress tracking.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Site Management</h3>
                <p>Daily logs, weather tracking, site photos, and multi-site support.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <h3>Budgeting & Cost</h3>
                <p>EVM analysis, SPI/CPI, forecasting, and cost overrun alerts.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-file-invoice"></i></div>
                <h3>Invoicing & AR</h3>
                <p>Client invoices, IPA, retention tracking, and aging reports.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <h3>Procurement</h3>
                <p>PR → PO → GRN flow with multi-level approvals.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-warehouse"></i></div>
                <h3>Inventory</h3>
                <p>Stocks, transfers, issue slips, wastage, and reconciliation.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-users"></i></div>
                <h3>HR & Payroll</h3>
                <p>Employees, attendance, timesheets, wage slips, and leaves.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-hard-hat"></i></div>
                <h3>Safety & Compliance</h3>
                <p>HSE checklists, incidents, PTW, safety audits, toolbox talks.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                <h3>Quality Control</h3>
                <p>ITPs, NCRs, CARs, punch lists, and material test certificates.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-file-alt"></i></div>
                <h3>Document Management</h3>
                <p>Drawings, RFIs, change orders, transmittals with version control.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-file-contract"></i></div>
                <h3>Contract Management</h3>
                <p>Contracts, amendments, claims, closeout, and bank guarantees.</p>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Reports & Analytics</h3>
                <p>8 financial reports, S-curve, custom builder, PDF/Excel export.</p>
            </div>
        </div>
    </section>

    <!-- ===== CONTACT SECTION ===== -->
    <section id="contact" class="section">
        <div class="section-header">
            <span class="section-tag"><i class="fas fa-envelope"></i> Contact</span>
            <h2>Get In <span>Touch</span></h2>
            <p>Have questions? We'd love to hear from you. Reach out to us today.</p>
        </div>

        <div class="contact-container">
            <!-- Contact Form -->
            <div class="contact-form">
                <h3>Send Us <span>a Message</span></h3>
                <form>
                    <div class="form-group">
                        <input type="text" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Your Message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3><i class="fas fa-hard-hat"></i> {{ get_setting('app_name', config('app.name')) }}</h3>
                <p>Empowering construction businesses with intelligent ERP solutions to manage projects, costs, and operations efficiently.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#why-crm">Why Us</a></li>
                    <li><a href="#benefits">Benefits</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Contact Us</h4>
                <p><i class="fas fa-envelope"></i> hello@inoodex.com</p>
                <p><i class="fas fa-phone"></i> +880 1234 567890</p>
                <p><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</p>
                <p><i class="fas fa-clock"></i> Mon-Fri: 9AM - 6PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ get_setting('app_name', config('app.name')) }}. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        // Toggle mobile menu
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            const hamburger = document.getElementById('hamburger');
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        }

        // Close menu when clicking a link (mobile)
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('navLinks').classList.remove('active');
                document.getElementById('hamburger').classList.remove('active');
            });
        });

        // Close menu when clicking outside (mobile)
        document.addEventListener('click', (e) => {
            const nav = document.getElementById('navbar');
            const hamburger = document.getElementById('hamburger');
            if (!nav.contains(e.target)) {
                document.getElementById('navLinks').classList.remove('active');
                hamburger.classList.remove('active');
            }
        });

        // Active link highlight on scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-links a:not(.login-btn-mobile)');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 120;
                if (window.scrollY >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

</body>
</html>
