<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        header("Location: attendee_dashboard.php");
        exit;
    }
} else {
    header("Location: attendee_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($event['event_name']); ?> - Event Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #f8f9fc;
        --accent-color: #ff6b6b;
        --dark-color: #5a5c69;
        --light-color: #f8f9fa;
    }

    body {
        background-color: var(--secondary-color);
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--dark-color);
    }

    .event-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease;
        margin-top: 100px;
        margin-bottom: 40px;
    }

    .event-card:hover {
        transform: translateY(-5px);
    }

    .event-card img {
        height: 400px;
        object-fit: cover;
        width: 100%;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .event-card-body {
        padding: 30px;
    }

    .event-title {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-size: 2rem;
    }

    .event-description {
        line-height: 1.8;
        margin-bottom: 2rem;
        color: var(--dark-color);
    }

    .event-meta {
        background-color: rgba(78, 115, 223, 0.05);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 1.5rem;
    }

    .event-meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .event-meta-item i {
        color: var(--primary-color);
        width: 24px;
        text-align: center;
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .btn-rsvp {
        background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        transition: all 0.3s ease;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-rsvp:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
    }

    .back-link {
        color: var(--primary-color);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        margin-top: 20px;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: #224abe;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .event-card {
            margin-top: 80px;
        }

        .event-card img {
            height: 250px;
        }

        .event-title {
            font-size: 1.6rem;
        }
    }
  </style>
</head>
<body>
  <?php include('header.php'); ?>

  <div class="container">
    <a href="attendee_dashboard.php" class="back-link">
      <i class="fas fa-arrow-left me-2"></i> Back to Events
    </a>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="event-card bg-light">
          <?php
            $imgPath = '../organizor/' . $event['image'];
            if (!empty($event['image']) && file_exists($imgPath)):
          ?>
            <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
          <?php else: ?>
            <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a328?auto=format&fit=crop&w=1470&q=80" alt="Event placeholder">
          <?php endif; ?>

          <div class="event-card-body">
            <h1 class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></h1>
            <p class="event-description"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

            <div class="event-meta">
              <div class="event-meta-item">
                <i class="fas fa-calendar-day"></i>
                <span><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></span>
              </div>
              <div class="event-meta-item">
                <i class="fas fa-clock"></i>
                <span><strong>Time:</strong> <?php echo htmlspecialchars($event['event_time']); ?></span>
              </div>
              <div class="event-meta-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></span>
              </div>
            </div>

            <a href="rsvp.php?id=<?php echo $event['id']; ?>" class="btn btn-rsvp">
              <i class="fas fa-calendar-check me-2"></i> RSVP Now
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
