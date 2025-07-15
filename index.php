<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('assets/bge.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .hero-section {
            text-align: center;
            padding: 100px 20px;
        }
        .hero-section h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.3rem;
        }
        .btn-group a {
            margin: 10px;
        }
        .features-section {
            padding: 60px 20px;
        }
        .features-section h2 {
            margin-bottom: 40px;
            font-size: 2.5rem;
        }
        .feature-box {
            text-align: center;
            padding: 30px;
            background-color: #FFA500;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .feature-box i {
            font-size: 60px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .feature-box h4 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .feature-box p {
            font-size: 1rem;
            color: #333;
        }
        .footer {
            background: #333;
            color: white;
            padding: 40px 20px;
        }
        .footer a {
            color: #ffa500;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .footer .footer-links {
            margin-top: 20px;
        }
        .footer .footer-icons i {
            font-size: 24px;
            margin: 0 10px;
            color: #ffa500;
            transition: 0.3s;
        }
        .footer .footer-icons i:hover {
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Welcome to the Event Management System</h1>
        <p>Your one-stop solution for planning, organizing, and attending events with ease.</p>
       <div class="btn-group">
    <a href="register.php" class="btn btn-primary btn-lg">Sign Up Now</a>
    <a href="login.php" class="btn btn-success btn-lg">Login to Your Account</a>
    <a href="about.php" class="btn btn-info btn-lg">About</a>
    <a href="contact.php" class="btn btn-danger btn-lg">Contact</a>
</div>

    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <h2 class="text-center">Explore Our Amazing Features</h2>
        <div class="row d-flex justify-content-center">
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <i class="bi bi-calendar-event"></i>
                    <h4>Effortless Event Creation</h4>
                    <p>Easily create and manage your events with just a few clicks.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <i class="bi bi-people"></i>
                    <h4>RSVP & Attendee Management</h4>
                    <p>Seamlessly track event attendance and RSVP responses in real-time.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <i class="bi bi-search"></i>
                    <h4>Discover Events Around You</h4>
                    <p>Find upcoming events and stay informed about what’s happening.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <i class="bi bi-chat-dots"></i>
                    <h4>Live Event Updates</h4>
                    <p>Receive real-time notifications and updates for your events.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <h5 class="mb-3">Event Management System</h5>
            <p>Empowering organizers and attendees to manage and enjoy events effortlessly.</p>
            <div class="footer-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-envelope"></i></a>
            </div>
            <div class="footer-links mt-3">
                <a href="about.php">About Us</a> |
                <a href="contact.php">Contact</a> |
                <a href="#">Privacy Policy</a> |
                <a href="#">Terms of Service</a> |
                <a href="login.php">Organizer Dashboard</a> |
                <a href="login.php">Attendee Dashboard</a>
            </div>
            <p class="mt-3">123 Event Street, City, Country | +123 456 7890 | contact@ems.com</p>
            <p>&copy; 2024 Event Management System. Designed with ❤️ by Sadia.</p>
        </div>
    </footer>

</body>
</html>