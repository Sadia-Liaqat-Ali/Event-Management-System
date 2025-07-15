<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Event Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      color: #333;
    }

    .about-hero {
      background: linear-gradient(to right, #004080, #007bff);
      color: white;
      text-align: center;
      padding: 100px 20px;
    }

    .about-hero h1 {
      font-size: 3.2rem;
      font-weight: bold;
    }

    .about-hero p {
      font-size: 1.2rem;
      margin-top: 10px;
    }

    .about-section {
      padding: 60px 20px;
    }

    .about-box {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .about-box h3 {
      color: #004080;
      font-weight: bold;
      margin-top: 20px;
    }

    .about-box p {
      font-size: 1.1rem;
      line-height: 1.6;
    }

    .values-section {
      background-color: #fff0f6;
      padding: 50px 20px;
    }

    .value-box {
      text-align: center;
      padding: 20px;
    }

    .value-box i {
      font-size: 2.5rem;
      color: #007bff;
    }

    .value-box h5 {
      margin-top: 15px;
      font-weight: 600;
    }

    footer {
      background: #004080;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 50px;
    }
  </style>
</head>
<body>

<!-- Hero Section -->
<div class="about-hero">
  <h1>About Event Management System</h1>
  <p>Empowering you to organize, manage, and attend events like never before.</p>
</div>

<!-- About Content -->
<div class="container about-section">
  <div class="about-box">
    <h3><i class="bi bi-people-fill me-2"></i>Who We Are</h3>
    <p>We are a passionate team focused on revolutionizing the way events are created and experienced. Our system empowers organizers with intuitive tools, while attendees enjoy a seamless interface to discover and join events effortlessly.</p>

    <h3><i class="bi bi-bullseye me-2"></i>Our Mission</h3>
    <p>To deliver a robust and elegant platform that supports all event needs – from planning to participation – ensuring both hosts and guests have a smooth and enjoyable experience.</p>

    <h3><i class="bi bi-graph-up-arrow me-2"></i>Our Vision</h3>
    <p>To become the go-to platform for every kind of event—personal, corporate, educational, and beyond—bridging communities and enhancing interaction through smarter event solutions.</p>
  </div>
</div>

<!-- Values -->
<section class="values-section">
  <div class="container text-center">
    <div class="row">
      <div class="col-md-4 value-box">
        <i class="bi bi-lightning-fill"></i>
        <h5>Efficiency</h5>
        <p>Streamline event workflows for organizers and automate key processes.</p>
      </div>
      <div class="col-md-4 value-box">
        <i class="bi bi-hand-thumbs-up-fill"></i>
        <h5>Trust</h5>
        <p>We value transparency and deliver what we promise to both users and hosts.</p>
      </div>
      <div class="col-md-4 value-box">
        <i class="bi bi-stars"></i>
        <h5>Innovation</h5>
        <p>Continually improving features to meet modern digital event demands.</p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <p>&copy; <?= date('Y'); ?> Event Management System. All rights reserved.</p>
</footer>

</body>
</html>
