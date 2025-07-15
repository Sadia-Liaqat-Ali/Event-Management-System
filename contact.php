<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Event Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
    }

    .contact-header {
      background: linear-gradient(to right, #004080, #007bff);
      color: white;
      padding: 60px 0;
      text-align: center;
    }

    .contact-header h1 {
      font-size: 3rem;
      font-weight: bold;
    }

    .contact-box {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .contact-box input, .contact-box textarea {
      border-radius: 8px;
    }

    .contact-info {
      padding: 40px;
    }

    .contact-info h4 {
      color: #004080;
      font-weight: bold;
    }

    .contact-info i {
      color: #007bff;
      margin-right: 10px;
    }

    .map-container {
      height: 300px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>
<body>

<!-- Contact Header -->
<div class="contact-header">
  <h1>Contact Us</h1>
  <p>We’d love to hear from you — reach out anytime!</p>
</div>

<!-- Contact Section -->
<div class="container my-5">
  <div class="row g-4">
    <!-- Contact Form -->
    <div class="col-md-6">
      <div class="contact-box">
        <h4 class="mb-4"><i class="bi bi-envelope-fill me-2"></i>Send Us a Message</h4>
        <form>
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="example@example.com" required>
          </div>
          <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" placeholder="Write your subject" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" rows="5" placeholder="Type your message here..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100">Send Message</button>
        </form>
      </div>
    </div>

    <!-- Contact Info & Map -->
    <div class="col-md-6">
      <div class="contact-info">
        <h4><i class="bi bi-geo-alt-fill"></i>Our Office</h4>
        <p>123 Event Street, Lahore, Pakistan</p>

        <h4><i class="bi bi-telephone-fill"></i>Phone</h4>
        <p>+92 300 1234567</p>

        <h4><i class="bi bi-envelope-fill"></i>Email</h4>
        <p>support@eventsystem.com</p>

        <h4><i class="bi bi-clock-fill"></i>Working Hours</h4>
        <p>Mon - Sat: 9:00 AM - 6:00 PM</p>
      </div>

      <div class="map-container mt-4">
        <iframe src="https://maps.google.com/maps?q=lahore&t=&z=13&ie=UTF8&iwloc=&output=embed" 
          width="100%" height="100%" frameborder="0" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
  <p>&copy; <?= date('Y'); ?> Event Management System. All rights reserved.</p>
</footer>

</body>
</html>
