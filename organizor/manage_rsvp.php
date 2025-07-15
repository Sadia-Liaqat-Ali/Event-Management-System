<?php
session_start();
require_once '../db.php';

// Only for organizers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: ../login.php");
    exit;
}

// Fetch RSVPs for events created by this organizer
try {
    $stmt = $pdo->prepare("
        SELECT er.id, e.event_name, er.attendee_name, er.status, er.created_at
        FROM event_rsvps er
        JOIN events e ON er.event_id = e.id
        WHERE e.organizer_id = ?
        ORDER BY er.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $rsvps = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error loading RSVPs: " . $e->getMessage();
    $rsvps = [];
}

// Generate download file
if (isset($_GET['download']) && count($rsvps) > 0) {
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=rsvps_report.txt");

    echo "RSVPs Report\n\n";
    foreach ($rsvps as $r) {
        echo "Event: {$r['event_name']}\n";
        echo "Attendee: {$r['attendee_name']}\n";
        echo "Status: {$r['status']}\n";
        echo "Date: {$r['created_at']}\n";
        echo "--------------------------\n";
    }
    exit;
}

// Count totals
$total = count($rsvps);
$attending = count(array_filter($rsvps, fn($r) => $r['status'] === 'attending'));
$maybe = count(array_filter($rsvps, fn($r) => $r['status'] === 'maybe'));
$declined = count(array_filter($rsvps, fn($r) => $r['status'] === 'declined'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage RSVPs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .table th {
      background-color: #4e73df;
      color: white;
    }
    .count-box {
      margin-bottom: 20px;
    }
    .btn-download {
      background-color: #28a745;
      color: #fff;
      border: none;
    }
    .btn-download:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
<?php include("header.php"); ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0"><i class="fas fa-users me-2"></i>RSVPs Summary</h4>
        </div>
        <div class="card-body">

          <!-- RSVP Counts -->
          <div class="row text-center count-box">
            <div class="col-md-3"><strong>Total:</strong> <?= $total ?></div>
            <div class="col-md-3"><strong>Attending:</strong> <?= $attending ?></div>
            <div class="col-md-3"><strong>Maybe:</strong> <?= $maybe ?></div>
            <div class="col-md-3"><strong>Declined:</strong> <?= $declined ?></div>
          </div>

          <?php if ($total > 0): ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle">
                <thead>
                  <tr>
                    <th>Event</th>
                    <th>Attendee</th>
                    <th>Status</th>
                    <th>RSVP Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rsvps as $rsvp): ?>
                    <tr>
                      <td><?= htmlspecialchars($rsvp['event_name']) ?></td>
                      <td><?= htmlspecialchars($rsvp['attendee_name']) ?></td>
                      <td><?= ucfirst($rsvp['status']) ?></td>
                      <td><?= date('M j, Y g:i A', strtotime($rsvp['created_at'])) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <a href="?download=1" class="btn btn-download btn-outline-success">
              <i class="fas fa-download me-1"></i> Download
            </a>
          <?php else: ?>
            <div class="text-center py-5">
              <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
              <h5>No RSVPs Found</h5>
              <p class="text-muted">No one has RSVP'd to your events yet.</p>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
