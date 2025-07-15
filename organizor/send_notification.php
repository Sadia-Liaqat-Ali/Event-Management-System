<?php
session_start();
require_once '../db.php';

// Only organizers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit;
}

$organizer_id = $_SESSION['user_id'];

// Fetch organizer's events
$stmt = $pdo->prepare("SELECT * FROM events WHERE organizer_id = ?");
$stmt->execute([$organizer_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Delete notification
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $del = $pdo->prepare("DELETE FROM event_notifications WHERE id = ? AND sender_id = ?");
    $del->execute([$delete_id, $organizer_id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
    $event_id = $_POST['event_id'];
    $notification_title = trim($_POST['notification_title']);
    $notification_message = trim($_POST['notification_message']);

    try {
        $stmt = $pdo->prepare("INSERT INTO event_notifications (event_id, sender_id, notification_title, notification_message, status) VALUES (?, ?, ?, ?, 'sent')");
        $stmt->execute([$event_id, $organizer_id, $notification_title, $notification_message]);
        echo "<script>alert('Notification sent successfully.'); window.location='send_notifications.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Fetch sent notifications by this organizer
$sentStmt = $pdo->prepare("
    SELECT n.id, n.notification_title, n.notification_message, n.created_at, e.event_name 
    FROM event_notifications n
    JOIN events e ON n.event_id = e.id
    WHERE n.sender_id = ?
    ORDER BY n.created_at DESC
");
$sentStmt->execute([$organizer_id]);
$notifications = $sentStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send Notifications</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .card-header {
      background-color: #007bff;
      color: white;
      font-weight: bold;
    }
    .notification-box {
      background-color: #ffffff;
      padding: 15px;
      margin-bottom: 15px;
      border-left: 4px solid #007bff;
    }
    .delete-btn {
      float: right;
      color: red;
      text-decoration: none;
    }
    .delete-btn:hover {
      color: darkred;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container py-4">
  <div class="row">
    <!-- Left: Form -->
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-header">Send Notification</div>
        <div class="card-body">
          <?php if (count($events) > 0): ?>
          <form method="POST">
            <div class="mb-3">
              <label for="event_id" class="form-label">Select Event</label>
              <select name="event_id" id="event_id" class="form-select" required>
                <?php foreach ($events as $event): ?>
                  <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['event_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="notification_title" class="form-label">Notification Title</label>
              <input type="text" name="notification_title" id="notification_title" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="notification_message" class="form-label">Notification Message</label>
              <textarea name="notification_message" id="notification_message" rows="5" class="form-control" required></textarea>
            </div>

            <button type="submit" name="send_notification" class="btn btn-primary w-100">Send Notification</button>
          </form>
          <?php else: ?>
            <div class="alert alert-info">No events found. Please create an event first.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Right: Sent Notifications -->
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-header">Sent Notifications</div>
        <div class="card-body">
          <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $note): ?>
              <div class="notification-box">
                <strong><?= htmlspecialchars($note['notification_title']) ?></strong> 
                <a class="delete-btn btn-danger" href="?delete_id=<?= $note['id'] ?>"  onclick="return confirm('Delete this notification?');">Delete</a>
                <div><small><i><?= htmlspecialchars($note['event_name']) ?> | <?= date('M j, Y g:i A', strtotime($note['created_at'])) ?></i></small></div>
                <p class="mb-0"><?= nl2br(htmlspecialchars($note['notification_message'])) ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-muted">No notifications sent yet.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
